<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller PatientForm
 * Handles the online advisory form with custom file upload.
 */
class PatientForm extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library([
            'form_validation', 
            'session'
        ]);
        
        $this->load->helper([
            'url', 
            'form', 
            'security',
            'string',
            'file'
        ]);
        
        $this->load->model('Model');
        
        // ============================================
        // CHARGER LA LIBRAIRIE EMAIL cPanel (optionnelle)
        // ============================================
        $cpanel_lib = APPPATH . 'libraries/Cpanel_email_lib.php';
        if (file_exists($cpanel_lib)) {
            $this->load->library('cpanel_email_lib');
        }
        
        $this->upload_path = FCPATH . 'attachments/FichierPatient/';
        
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, TRUE);
        }
        
        // ============================================
        // TOUS LES ENDPOINTS SONT PUBLICS (pas d'auth)
        // ============================================
    }

    public function index()
    {   
        // L'utilisateur peut être connecté ou non (formulaire public)
        $user_id = $this->session->userdata('user_id');
        $user = $user_id ? $this->getCurrentUser() : null;

        // Récupérer l'UUID de l'expert depuis GET ou POST
        $doctor_uuid = $this->input->get('doctor_uuid') ?: $this->input->post('selected_doctor_uuid');
        $medecin = null;

        if ($doctor_uuid) {
            $medecin = $this->Model->getDoctorByUUID($doctor_uuid);
            if (!$medecin) {
                $this->session->set_flashdata('error', 'Doctor not found or unavailable.');
                redirect('doctor');
                return;
            }
        } else {
            $doctor_data = $this->session->userdata('pending_doctor');
            if ($doctor_data && $doctor_data['expires_at'] >= time()) {
                $medecin = $this->Model->getDoctorByUUID($doctor_data['uuid']);
            }
            // Si pas d'expert en session, prendre le premier disponible
            if (!$medecin) {
                $disponibles = $this->Model->get_medecins_disponibles(1);
                if (!empty($disponibles)) {
                    $medecin = $disponibles[0];
                }
            }
            if (!$medecin) {
                $this->session->set_flashdata('error', 'No expert available at the moment.');
                redirect('doctor');
                return;
            }
        }

        $prix_usd = isset($medecin['honoraires_consultation']) ? (float)$medecin['honoraires_consultation'] : 50;
        $devise = $medecin['currency'] ?? 'USD';
        $equiv_bif = $medecin['USD_EUR_Equivalent_en_BIF'] ?? null;
        $prix_burundi = $medecin['prix_pour_residant_burundi'] ?? null;

        $data = [
            'title'          => 'New Advisory Request - A.G.F',
            'pays'           => $this->Model->read('pays', null, 'pays', 'ASC'),
            'products'       => $this->Model->read('produits', null, 'id', 'DESC'),
            'mode_payements' => $this->Model->getActivePaymentMethods(),
            'is_logged_in'   => (bool)$user_id,
            'user_id'        => $user_id,
            'user'           => $user,
            'medecin'        => $medecin,
            'doctor'         => $medecin,
            'doctor_count'   => $this->_count_active_doctors(),
            'prix_usd'       => $prix_usd,
            'devise'         => $devise,
            'equiv_bif'      => $equiv_bif,
            'prix_burundi'   => $prix_burundi
        ];

        $this->load->view('PatientForm_View', $data);
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    private function getCurrentUser()
    {
        if ($this->session->userdata('user_id')) {
            return $this->db->query("
                SELECT id, uuid, email, nom, prenom, photo 
                FROM users 
                WHERE id = ? AND is_active = 1
            ", [$this->session->userdata('user_id')])->row_array();
        }
        return null;
    }

    private function _count_active_doctors()
    {
        $this->db->from('medecins');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('users.is_active', 1);
        return (int)$this->db->count_all_results();
    }

    public function changeDoctor()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }
        $this->clearPatientData();
        redirect('doctor');
    }

    private function clearPatientData()
    {
        $session_data = [
            'pending_doctor',
            'consultation_form_data',
            'temp_files',
            'uploaded_files'
        ];
        foreach ($session_data as $key) {
            $this->session->unset_userdata($key);
        }
        $this->cleanTempFiles();
            log_message('info', 'Patient data cleared due to expert change');
    }

    private function cleanTempFiles()
    {
        $temp_files = $this->session->userdata('temp_files');
        if (!empty($temp_files) && is_array($temp_files)) {
            foreach ($temp_files as $file) {
                $file_path = $this->upload_path . $file;
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }
        }
        $this->session->unset_userdata('temp_files');
    }

    public function create()
    {    
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            redirect('patient-form');
        }

        // Patient connecté ou non (formulaire public)
        $patient_id = $this->session->userdata('user_id') ?: null;
        if (!$patient_id) {
            log_message('info', 'PatientForm: Soumission publique sans connexion');
        }

        $this->form_validation->set_rules('full_name', 'Nom complet', 'required|trim|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('age', 'Âge', 'required|integer|greater_than[0]|less_than[121]');
        $this->form_validation->set_rules('country', 'Pays de résidence', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('weight', 'Poids', 'required|numeric|greater_than[0]|less_than[300]');
        $this->form_validation->set_rules('height', 'Taille', 'required|integer|greater_than[50]|less_than[251]');
        $this->form_validation->set_rules('whatsapppatient', 'Numéro WhatsApp', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('symptoms', 'Condition', 'required|trim|min_length[20]|max_length[5000]');
        $this->form_validation->set_rules('symptoms_duration', 'Duration of condition', 'trim');
        $this->form_validation->set_rules('previous_consultation', 'First visit', 'trim|in_list[yes,no]');
        $this->form_validation->set_rules('terms', 'Conditions générales', 'required');
        $this->form_validation->set_rules('payment_method', 'Mode de paiement', 'required|trim|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect('patient-form');
        }

        $doctor_id = $this->input->post('doctor_id', TRUE);
        $doctor_uuid = $this->input->post('doctor_uuid', TRUE);
        $consultation_prix = $this->input->post('consultation_prix', TRUE) ?: 50;
        $consultation_devise = $this->input->post('consultation_devise', TRUE) ?: 'USD';

        $medecin = null;
        if ($doctor_uuid) {
            $medecin = $this->Model->getDoctorByUUID($doctor_uuid);
            if (!$medecin) {
                $this->session->set_flashdata('error', 'The selected expert is no longer available.');
                redirect('doctor');
            }
            $doctor_id = $medecin['id'];
        }

        // Sécurité : medecin_id est NOT NULL en base. Si aucun expert n'est fourni,
            // on attribue automatiquement le premier expert disponible.
        if (empty($doctor_id)) {
            $medecins_disponibles = $this->Model->get_medecins_disponibles(1);
            if (!empty($medecins_disponibles)) {
                $first = (array)$medecins_disponibles[0];
                $doctor_id = $first['id'];
            }
        }

        $country_id = null;
        $country_name = $this->input->post('country', TRUE);
        if (!empty($country_name)) {
            $pays = $this->Model->getPaysByName($country_name);
            if ($pays) {
                $country_id = $pays['id'];
            }
        }

        $age = $this->input->post('age', TRUE);
        $numero_consultation = $this->_generate_consultation_number();

        // Splitter le nom complet en patient_nom / patient_prenom
        $full_name = trim($this->input->post('full_name', TRUE));
        $name_parts = preg_split('/\s+/', $full_name, 2);
        $patient_prenom = $name_parts[0] ?? '';
        $patient_nom = $name_parts[1] ?? '';

        $medical_docs = [];
        if (!empty($_FILES['medical_docs']['name'][0])) {
            $medical_docs = $this->_upload_multiple_files_custom('medical_docs');
        }
        $prescriptions = [];
        if (!empty($_FILES['prescriptions']['name'][0])) {
            $prescriptions = $this->_upload_multiple_files_custom('prescriptions');
        }

        // Mode de paiement choisi
        $payment_method = $this->input->post('payment_method', TRUE) ?: null;

        // Preuve de paiement (capture d'écran) — optionnelle
        $payment_proof = null;
        if (!empty($_FILES['payment_proof']['name'])) {
            $payment_proof = $this->_upload_payment_proof_custom();
            if ($payment_proof === false) {
                $this->_cleanup_files(array_merge($medical_docs, $prescriptions));
                $this->session->set_flashdata('error', 'Preuve de paiement invalide. Formats acceptés : JPG, PNG, PDF (max 5 Mo).');
                redirect('patient-form');
            }
        }

        $consultation_data = [
            'uuid'                   => sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            ),
            'numero_consultation'    => $numero_consultation,
            'patient_id'             => $patient_id,
            'medecin_id'             => $doctor_id ?: NULL,
            'type'                   => 'whatsapp',
            'patient_nom'            => $patient_nom,
            'patient_prenom'         => $patient_prenom,
            'patient_poids'          => $this->input->post('weight', TRUE),
            'patient_taille'         => $this->input->post('height', TRUE),
            'poids'                  => $this->input->post('weight', TRUE),
            'taille'                 => $this->input->post('height', TRUE),
            'country_id'             => $country_id,
            'age'                    => $age,
            'whatsapppatient'        => $this->input->post('whatsapppatient', TRUE),
            'description_symptomes'  => $this->input->post('symptoms', TRUE),
            'duree_symptomes'        => $this->input->post('symptoms_duration', TRUE),
            'consultation_precedente' => $this->input->post('previous_consultation', TRUE),
            'examens_demandes'       => !empty($medical_docs) ? json_encode($medical_docs) : NULL,
            'ordonnances'            => !empty($prescriptions) ? json_encode($prescriptions) : NULL,
            'diagnostic'             => NULL,
            'traitement'             => NULL,
            'notes_medecin'          => NULL,
            'date_souhaitee'         => date('Y-m-d H:i:s', strtotime('+7 days')),
            'date_confirmee'         => NULL,
            'statut'                 => 'en_attente',
            'honoraires_consultation' => 0.00,
            'motif_annulation'       => NULL,
            'prix_ht'                => $consultation_prix,
            'devise'                 => $consultation_devise,
            'tva'                    => 0.00,
            'paiement_statut'        => $payment_proof ? 'paye' : 'en_attente',
            'mode_paiement'          => $payment_method,
            'preuve_paiement'        => $payment_proof,
            'ip_creation'            => $this->input->ip_address(),
        ];

        $insert_id = $this->Model->create_last_id('consultations', $consultation_data);

        if (!$insert_id) {
            $all_files = array_merge($medical_docs, $prescriptions);
            $this->_cleanup_files($all_files);
            $this->session->set_flashdata('error', 'Erreur lors de l\'enregistrement de la demande de advisory service.');
            redirect('patient-form');
        }

        $this->session->unset_userdata('pending_doctor');

        $email_data = [
            'consultation_id'     => $insert_id,
            'numero_consultation' => $numero_consultation,
            'patient_id'          => $patient_id,
            'doctor_id'           => $doctor_id,
            'full_name'           => $this->input->post('full_name', TRUE),
            'age'                 => $age,
            'whatsapppatient'     => $this->input->post('whatsapppatient', TRUE),
            'country'             => $country_name,
            'weight'              => $this->input->post('weight', TRUE),
            'height'              => $this->input->post('height', TRUE),
            'symptoms'            => $this->input->post('symptoms', TRUE),
            'symptoms_duration'   => $this->input->post('symptoms_duration', TRUE),
            'previous_consultation' => $this->input->post('previous_consultation', TRUE),
            'consultation_prix'   => $consultation_prix,
            'consultation_devise' => $consultation_devise,
            'payment_method'      => $payment_method,
            'payment_proof'       => $payment_proof,
            'medecin'             => $medecin,
            'medical_docs'        => $medical_docs,
            'prescriptions'       => $prescriptions
        ];
        
            $this->session->set_flashdata('success', 'Votre demande de advisory service a été créée avec succès.');
        $this->session->set_flashdata('tracking_number', $numero_consultation);
        
        // Sauvegarde OK → ouverture WhatsApp avec message préconfiguré
        $this->_redirect_to_whatsapp($numero_consultation, $medecin, $medical_docs, $prescriptions, $payment_method, $payment_proof, $consultation_prix, $consultation_devise);
    }

    /**
     * API AJAX — Soumission rapide du formulaire de consultation.
     * Retourne JSON { ok, whatsapp_url, tracking_number } au lieu de redirect.
     */
    public function api_submit()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
            exit;
        }

        $patient_id = $this->session->userdata('user_id') ?: null;

        $this->form_validation->set_rules('full_name', 'Nom complet', 'required|trim|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('age', 'Age', 'required|integer|greater_than[0]|less_than[121]');
        $this->form_validation->set_rules('country', 'Pays', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('weight', 'Poids', 'required|numeric|greater_than[0]|less_than[300]');
        $this->form_validation->set_rules('height', 'Taille', 'required|integer|greater_than[50]|less_than[251]');
        $this->form_validation->set_rules('whatsapppatient', 'WhatsApp', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('symptoms', 'Condition', 'required|trim|min_length[20]|max_length[5000]');
        $this->form_validation->set_rules('payment_method', 'Mode de paiement', 'required|trim|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['ok' => false, 'error' => strip_tags(validation_errors('<br>'))]);
            exit;
        }

        // Medecin
        $doctor_uuid = $this->input->post('doctor_uuid', TRUE);
        $medecin = null;
        $doctor_id = null;
        if ($doctor_uuid) {
            $medecin = $this->Model->getDoctorByUUID($doctor_uuid);
        }
        if ($medecin) {
            $doctor_id = $medecin['id'];
        } else {
            $dispos = $this->Model->get_medecins_disponibles(1);
            if (!empty($dispos)) {
                $medecin = (array)$dispos[0];
                $doctor_id = $medecin['id'];
            }
        }

        // Country
        $country_id = null;
        $country_name = $this->input->post('country', TRUE);
        if (!empty($country_name)) {
            $pays = $this->Model->getPaysByName($country_name);
            if ($pays) $country_id = $pays['id'];
        }

        // Split name
        $full_name = trim($this->input->post('full_name', TRUE));
        $name_parts = preg_split('/\s+/', $full_name, 2);
        $patient_prenom = $name_parts[0] ?? '';
        $patient_nom = $name_parts[1] ?? '';

        $numero_consultation = $this->_generate_consultation_number();
        $consultation_prix = $this->input->post('consultation_prix', TRUE) ?: 50;
        $consultation_devise = $this->input->post('consultation_devise', TRUE) ?: 'USD';
        $payment_method = $this->input->post('payment_method', TRUE) ?: null;

        // Uploads (en parallele logic)
        $medical_docs = [];
        if (!empty($_FILES['medical_docs']['name'][0])) {
            $medical_docs = $this->_upload_multiple_files_custom('medical_docs');
        }
        $prescriptions = [];
        if (!empty($_FILES['prescriptions']['name'][0])) {
            $prescriptions = $this->_upload_multiple_files_custom('prescriptions');
        }
        $payment_proof = null;
        if (!empty($_FILES['payment_proof']['name'])) {
            $payment_proof = $this->_upload_payment_proof_custom();
        }

        $consultation_data = [
            'uuid'                    => $this->_generate_uuid(),
            'numero_consultation'     => $numero_consultation,
            'patient_id'              => $patient_id,
            'medecin_id'              => $doctor_id,
            'type'                    => 'whatsapp',
            'patient_nom'             => $patient_nom,
            'patient_prenom'          => $patient_prenom,
            'patient_poids'           => $this->input->post('weight', TRUE),
            'patient_taille'          => $this->input->post('height', TRUE),
            'poids'                   => $this->input->post('weight', TRUE),
            'taille'                  => $this->input->post('height', TRUE),
            'country_id'              => $country_id,
            'age'                     => $this->input->post('age', TRUE),
            'whatsapppatient'         => $this->input->post('whatsapppatient', TRUE),
            'description_symptomes'   => $this->input->post('symptoms', TRUE),
            'duree_symptomes'         => $this->input->post('symptoms_duration', TRUE),
            'consultation_precedente' => $this->input->post('previous_consultation', TRUE),
            'examens_demandes'        => !empty($medical_docs) ? json_encode($medical_docs) : NULL,
            'ordonnances'             => !empty($prescriptions) ? json_encode($prescriptions) : NULL,
            'statut'                  => 'en_attente',
            'honoraires_consultation' => 0.00,
            'prix_ht'                 => $consultation_prix,
            'devise'                  => $consultation_devise,
            'tva'                     => 0.00,
            'paiement_statut'         => $payment_proof ? 'paye' : 'en_attente',
            'mode_paiement'           => $payment_method,
            'preuve_paiement'         => $payment_proof,
            'ip_creation'             => $this->input->ip_address(),
        ];

        $insert_id = $this->Model->create_last_id('consultations', $consultation_data);

        if (!$insert_id) {
            echo json_encode(['ok' => false, 'error' => 'Erreur lors de l\'enregistrement.']);
            exit;
        }

        // Construire l'URL WhatsApp
        $whatsapp_url = $this->_build_whatsapp_url($numero_consultation, $medecin, $medical_docs, $prescriptions, $payment_method, $payment_proof, $consultation_prix, $consultation_devise);

        echo json_encode([
            'ok'               => true,
            'tracking_number'  => $numero_consultation,
            'whatsapp_url'     => $whatsapp_url,
        ]);
        exit;
    }

    /**
     * Construire l'URL WhatsApp sans redirect (pour AJAX)
     */
    private function _build_whatsapp_url($numero_consultation, $medecin, $medical_docs = [], $prescriptions = [], $payment_method = null, $payment_proof = null, $consultation_prix = null, $consultation_devise = null)
    {
        $numero = preg_replace('/[^0-9]/', '', $this->Model->get_setting('whatsapp_number', '+260 777 844 844'));
        if (empty($numero)) return '';

        $doctor_name = 'A attribuer';
        if ($medecin) {
            $dp = trim((string)($medecin['prenom'] ?? ''));
            $dn = trim((string)($medecin['nom'] ?? ''));
            if ($dp !== '' || $dn !== '') $doctor_name = 'Dr. ' . trim($dp . ' ' . $dn);
        }

        $patient_email = $this->session->userdata('email') ?: 'Non renseigne';
        $prix = (float)($medecin['honoraires_consultation'] ?? $consultation_prix);
        $devise_medecin = $medecin['currency'] ?? $consultation_devise;
        $montant = number_format($prix, (floor($prix) == $prix ? 0 : 2), ',', ' ');
        $equiv_bif = $medecin['USD_EUR_Equivalent_en_BIF'] ?? null;
        $prix_burundi = $medecin['prix_pour_residant_burundi'] ?? null;

        $m = "*DEMANDE DE CONSULTATION - A.G.F*\n\n";
        $m .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $m .= "*INFORMATIONS PATIENT*\n";
        $m .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $m .= "Nom : " . $this->input->post('full_name', TRUE) . "\n";
        $m .= "WhatsApp : " . $this->input->post('whatsapppatient', TRUE) . "\n";
        $m .= "Email : " . $patient_email . "\n";
        $m .= "Date : " . date('d/m/Y H:i') . "\n\n";
        $m .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $m .= "*DETAILS DE LA CONSULTATION*\n";
        $m .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $m .= "N suivi : " . $numero_consultation . "\n";
        $m .= "Age : " . $this->input->post('age', TRUE) . " ans\n";
        $m .= "Pays : " . $this->input->post('country', TRUE) . "\n";
        $m .= "Poids : " . $this->input->post('weight', TRUE) . " kg | Taille : " . $this->input->post('height', TRUE) . " cm\n\n";
        $m .= "Condition : " . $this->input->post('symptoms', TRUE) . "\n\n";
        $dur = $this->input->post('symptoms_duration', TRUE);
        if ($dur) $m .= "Duration of condition : " . $dur . "\n";
        $m .= "*Service Fee*\n";
        $m .= "Residant du Burundi : " . ($prix_burundi ?: '-') . "\n";
        $m .= "Residant a l'etranger : " . $montant . " " . $devise_medecin . ($equiv_bif ? " (Equivalence BIF : " . $equiv_bif . ")" : "") . "\n";

        $docs = array_merge((array)$medical_docs, (array)$prescriptions);
        if (!empty($docs)) {
            $m .= "\n*Documents :*\n";
            foreach ($docs as $d) { if (!empty($d)) $m .= base_url('attachments/Consultations/' . $d) . "\n"; }
        }
        if (!empty($payment_method)) $m .= "\nMode de paiement : " . $payment_method . "\n";
        if (!empty($payment_proof)) {
            $m .= "Preuve de paiement : " . base_url($payment_proof) . "\n";
        } else {
            $m .= "\n*NB - Paiement en attente* - envoi de la preuve via WhatsApp des que possible.\n";
        }

        return 'https://wa.me/' . $numero . '?text=' . rawurlencode($m);
    }

    private function _generate_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Upload de la preuve de paiement (capture d'écran)
     * @return string|false Chemin relatif du fichier ou false si erreur
     */
    private function _upload_payment_proof_custom()
    {
        if (empty($_FILES['payment_proof']['name'])) {
            return false;
        }

        $file = $_FILES['payment_proof'];
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($file_type, $allowed_types)) {
            return false;
        }

        $upload_dir = FCPATH . 'attachments/Payments/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, TRUE);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'payment_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'attachments/Payments/' . $filename;
        }

        return false;
    }

    /**
     * Rediriger vers WhatsApp avec un message de demande de consultation préconfiguré
     */
    private function _redirect_to_whatsapp($numero_consultation, $medecin, $medical_docs = [], $prescriptions = [], $payment_method = null, $payment_proof = null, $consultation_prix = null, $consultation_devise = null)
    {
        $numero = preg_replace('/[^0-9]/', '', $this->Model->get_setting('whatsapp_number', '+260 777 844 844'));
        if (empty($numero)) {
            redirect('patient-form');
            return;
        }

        // Nom de l'expert (tolérant aux données incomplètes)
        $doctor_name = 'À attribuer';
        if ($medecin) {
            $doctor_prenom = trim(preg_replace('/^[.\s]+$/', '', (string)($medecin['prenom'] ?? '')));
            $doctor_nom = trim(preg_replace('/^[.\s]+$/', '', (string)($medecin['nom'] ?? '')));
            if ($doctor_prenom !== '' || $doctor_nom !== '') {
                $doctor_name = 'Dr. ' . trim($doctor_prenom . ' ' . $doctor_nom);
            }
        }

        // Email (si connecté)
        $patient_email = $this->session->userdata('email');
        if (empty($patient_email)) {
            $patient_email = 'Non renseigné';
        }

        // Montant + équivalent BIF
        $prix = (float)($medecin['honoraires_consultation'] ?? $consultation_prix);
        $devise_medecin = $medecin['currency'] ?? $consultation_devise;
        $montant = number_format($prix, (floor($prix) == $prix ? 0 : 2), ',', ' ');
        $equiv_bif = $medecin['USD_EUR_Equivalent_en_BIF'] ?? null;
        $prix_burundi = $medecin['prix_pour_residant_burundi'] ?? null;

        $message = "*DEMANDE DE CONSULTATION - A.G.F*\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*INFORMATIONS PATIENT*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "• Nom : " . $this->input->post('full_name', TRUE) . "\n";
        $message .= "• WhatsApp : " . $this->input->post('whatsapppatient', TRUE) . "\n";
        $message .= "• Email : " . $patient_email . "\n";
        $message .= "• Date : " . date('d/m/Y H:i') . "\n\n";

        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*DÉTAILS DE LA CONSULTATION*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "• N° suivi : " . $numero_consultation . "\n";
        $message .= "• Âge : " . $this->input->post('age', TRUE) . " ans\n";
        $message .= "• Pays : " . $this->input->post('country', TRUE) . "\n";
        $message .= "• Poids : " . $this->input->post('weight', TRUE) . " kg | Taille : " . $this->input->post('height', TRUE) . " cm\n\n";
        $message .= "• Condition : " . $this->input->post('symptoms', TRUE) . "\n\n";
        if (!empty($this->input->post('symptoms_duration', TRUE))) {
            $message .= "• Duration of condition : " . $this->input->post('symptoms_duration', TRUE) . "\n";
        }
        $message .= "*Service Fee*\n";
        $message .= "• Résidant du Burundi : " . ($prix_burundi ?: '—') . "\n";
        $message .= "• Résidant à l'étranger : " . $montant . " " . $devise_medecin . ($equiv_bif ? " (Équivalent en BIF : " . $equiv_bif . ")" : "") . "\n";
        $message .= "\n";

        // Documents téléchargés : WhatsApp ne transfère pas les fichiers via wa.me,
        // on fournit un lien vers l'emplacement sur le site pour consultation en ligne
        $documents = array_merge((array)$medical_docs, (array)$prescriptions);
        if (!empty($documents)) {
            $message .= "\n*Documents joints (disponibles en ligne) :*\n";
            foreach ($documents as $document) {
                if (!empty($document)) {
                    $message .= "• " . base_url('attachments/Consultations/' . $document) . "\n";
                }
            }
        }

        // Mode de paiement et preuve de paiement
        if (!empty($payment_method)) {
            $message .= "\n*Mode de paiement :* " . $payment_method . "\n";
        }
        if (!empty($payment_proof)) {
            $message .= "*Epreuve de paiement*\n";
            $message .= base_url($payment_proof) . "\n";
        } else {
            $message .= "\n*NB — Paiement en attente :* je me précipite pour effectuer le paiement et je vous envoie ma preuve de paiement via WhatsApp sur ce numéro dès que possible.\n";
        }

        redirect('https://wa.me/' . $numero . '?text=' . rawurlencode($message));
    }

    private function _upload_multiple_files_custom($field_name)
    {
        $uploaded_files = [];
        if (empty($_FILES[$field_name]['name'][0])) {
            return $uploaded_files;
        }

        $file_count = count($_FILES[$field_name]['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES[$field_name]['error'][$i] !== UPLOAD_ERR_OK) {
                log_message('error', 'File upload error ' . $i . ': ' . $_FILES[$field_name]['error'][$i]);
                continue;
            }
            if ($_FILES[$field_name]['size'][$i] > 5 * 1024 * 1024) {
                log_message('error', 'File too large: ' . $_FILES[$field_name]['name'][$i]);
                continue;
            }
            $result = $this->upload_image(
                $_FILES[$field_name]['tmp_name'][$i],
                $_FILES[$field_name]['name'][$i]
            );
            if ($result !== NULL) {
                $uploaded_files[] = $result;
            }
        }
        return $uploaded_files;
    }

    public function upload_image($nom_file, $nom_champ)
    {
        $ref_folder = FCPATH . 'attachments/Consultations/';
        $code = date("YmdHis") . uniqid();
        $fichier = basename($code);
        $file_extension = strtolower(pathinfo($nom_champ, PATHINFO_EXTENSION));
        $valid_ext = array('gif', 'jpg', 'png', 'jpeg', 'webp', 'svg', 'pdf', 'doc', 'docx');

        if (!in_array($file_extension, $valid_ext)) {
            log_message('error', 'Invalid extension: ' . $file_extension);
            return NULL;
        }

        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }

        $destination = $ref_folder . $fichier . "." . $file_extension;
        if (move_uploaded_file($nom_file, $destination)) {
            return $fichier . "." . $file_extension;
        } else {
            log_message('error', 'Failed to move file: ' . $nom_champ);
            return NULL;
        }
    }

    private function _cleanup_files($files)
    {
        foreach ($files as $file) {
            if (!empty($file)) {
                $path = $this->upload_path . $file;
                if (file_exists($path)) {
                    unlink($path);
                    log_message('debug', 'File deleted: ' . $path);
                }
            }
        }
    }

    private function _generate_consultation_number()
    {
        return 'AGF-' . date('Ymd') . '-' . date('His');
    }

    public function get_countries()
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Unauthorized access', 403);
        }

        $search = $this->input->get('q', TRUE);
        if (strlen($search) < 2) {
            echo json_encode([]);
            return;
        }

        $this->db->like('pays', $search, 'both');
        $this->db->order_by('pays', 'ASC');
        $this->db->limit(10);
        $query = $this->db->get('pays');

        $results = [];
        foreach ($query->result_array() as $row) {
            $results[] = [
                'id'            => $row['id'],
                'pays'          => $row['pays'],
                'code_iso2'     => $row['ISO_3166_1_2_Letter_Code'],
                'phone_code'    => $row['ITU_T_Telephone_Code'],
                'currency_code' => $row['ISO_4217_Currency_Code']
            ];
        }
        echo json_encode($results);
    }

    public function Medicin()
    {    
        // Si un seul expert actif existe, rediriger directement vers le formulaire
        $this->db->from('medecins');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('users.is_active', 1);
        $total_medecins = $this->db->count_all_results();

        if ($total_medecins === 1) {
            $this->db->select('medecins.uuid');
            $this->db->from('medecins');
            $this->db->join('users', 'users.id = medecins.user_id');
            $this->db->where('users.is_active', 1);
            $unique_medecin = $this->db->get()->row_array();
            if (!empty($unique_medecin['uuid'])) {
                redirect('patient-form?doctor_uuid=' . $unique_medecin['uuid']);
            }
        }

        $this->db->select("
            medecins.id,
            medecins.uuid,
            medecins.user_id,
            medecins.numero_licence,
            medecins.annees_experience,
            medecins.honoraires_consultation,
            medecins.currency,
            medecins.USD_EUR_Equivalent_en_BIF,
            medecins.prix_pour_residant_burundi,
            medecins.est_disponible,
            medecins.note_moyenne,
            medecins.nombre_avis,
            medecins.created_at,
            medecins.updated_at,
            medecins.specialite AS specialite,
            medecins.diplomes AS diplomes,
            medecins.langues_parlees AS langues_parlees,
            users.nom,
            users.prenom,
            users.email,
            users.telephone,
            users.photo,
            users.is_active,
            users.est_verifie
        ");
        $this->db->from('medecins');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('users.is_active', 1);
        $this->db->order_by('medecins.id', 'DESC');
        $data['medecins'] = $this->db->get()->result_array();

        foreach ($data['medecins'] as &$medecin) {
            $this->db->from('horaires_medecins');
            $this->db->where('medecin_id', $medecin['id']);
            $this->db->where('est_actif', 1);
            $this->db->order_by("FIELD(jour_semaine, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche')");
            $query = $this->db->get();
            $medecin['horaires'] = $query->result_array();
            $medecin['est_disponible_maintenant'] = $this->checkDisponibiliteMaintenant($medecin['id']);
        }

        $data['jours_semaine'] = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
        $data['products'] = $this->Model->read('produits', null, 'id', 'DESC');

        $this->load->view('Medecin_present_View', $data);
    }

    private function checkDisponibiliteMaintenant($medecin_id) {
        $jour_actuel = strtolower(date('l'));
        $jours_map = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche'
        ];
        $jour_semaine = $jours_map[$jour_actuel] ?? '';
        $heure_actuelle = date('H:i:s');
        
        $this->db->where('medecin_id', $medecin_id);
        $this->db->where('jour_semaine', $jour_semaine);
        $this->db->where('heure_debut <=', $heure_actuelle);
        $this->db->where('heure_fin >=', $heure_actuelle);
        $this->db->where('est_actif', 1);
        
        return $this->db->get('horaires_medecins')->num_rows() > 0;
    }

    /**
     * Envoyer les emails de confirmation de advisory service avec cPanel_email_lib
     */
    private function _send_consultation_emails($data)
    {    
        try {
            // Vérifier que la librairie est chargée
            if (!isset($this->cpanel_email_lib) || !is_object($this->cpanel_email_lib)) {
                log_message('error', 'cpanel_email_lib non disponible pour l\'envoi des emails de consultation');
                return false;
            }
            
            // Récupérer les informations du patient
            $this->db->select('email, nom, prenom');
            $this->db->where('id', $data['patient_id']);
            $patient = $this->db->get('users')->row_array();

            // Récupérer les informations de l'expert
            $doctor = null;
            if (!empty($data['doctor_id'])) {
                $this->db->select('medecins.*, users.email, users.nom, users.prenom');
                $this->db->where('medecins.id', $data['doctor_id']);
                $this->db->from('medecins');
                $this->db->join('users', 'users.id = medecins.user_id');
                $doctor = $this->db->get()->row_array();
            }

            // Récupérer les informations du site
            $site_name = $this->Model->get_setting('site_name', 'A.G.F');
            $site_logo = $this->Model->get_setting('site_logo');
            $logo_url = !empty($site_logo) ? base_url('attachments/Configurations/' . $site_logo) : '';
            
            $message_data = [
                'data' => $data,
                'patient' => $patient,
                'doctor' => $doctor,
                'site_name' => $site_name,
                'logo_url' => $logo_url
            ];

            // Email au patient
            if ($patient && !empty($patient['email'])) {
                $subject = 'Confirmation de votre demande de advisory service - N°' . $data['numero_consultation'];
                $message = $this->_build_patient_email_cpanel($message_data);
                $result = $this->cpanel_email_lib->send_email($patient['email'], $subject, $message);
                if (!$result['success']) {
                    log_message('error', 'cPanel Email - Échec envoi au patient: ' . json_encode($result));
                }
            }

            // Email à l'expert
            if ($doctor && !empty($doctor['email'])) {
                $subject = 'Nouvelle demande de advisory service - N°' . $data['numero_consultation'];
                $message = $this->_build_doctor_email_cpanel($message_data);
                $result = $this->cpanel_email_lib->send_email($doctor['email'], $subject, $message);
                if (!$result['success']) {
                    log_message('error', 'cPanel Email - Échec envoi à l\'expert: ' . json_encode($result));
                }
            }

            // Email à l'admin
            $admin_email = $this->Model->get_setting('admin_email', 'admin@agf.com');
            if (!empty($admin_email)) {
                $subject = 'Nouvelle advisory service créée - N°' . $data['numero_consultation'];
                $message = $this->_build_admin_email_cpanel($message_data);
                $result = $this->cpanel_email_lib->send_email($admin_email, $subject, $message);
                if (!$result['success']) {
                    log_message('error', 'cPanel Email - Échec envoi à l\'admin: ' . json_encode($result));
                }
            }
            
            return true;
        } catch (Exception $e) {
            log_message('error', 'Exception in consultation email sending: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Email patient version cPanel
     */
    private function _build_patient_email_cpanel($data)
    {
        $data = $data['data'];
        $patient = $data['patient'];
        $doctor = $data['doctor'];
        $site_name = $data['site_name'];
        $logo_url = $data['logo_url'];
        
        $doctor_name = $doctor ? htmlspecialchars($doctor['prenom']) . ' ' . htmlspecialchars($doctor['nom']) : 'À attribuer';
        $appointment_date = date('Y-m-d H:i', strtotime('+1 day'));
        $whatsapp_num = preg_replace('/[^0-9]/', '', $this->Model->get_setting('whatsapp_number', '+260 777 844 844'));
        $whatsapp_url = 'https://wa.me/' . $whatsapp_num;
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmation advisory service - A.G.F</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                    background-color: #f4f6f9;
                    margin: 0;
                    padding: 20px;
                    line-height: 1.5;
                }
                .container {
                    max-width: 560px;
                    margin: 0 auto;
                    background: #ffffff;
                    border-radius: 16px;
                    overflow: hidden;
                    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
                }
                .header {
                    background: linear-gradient(135deg, #0a2540, #0f4c3a);
                    padding: 30px 24px;
                    text-align: center;
                }
                .header-logo { max-width: 100px; margin-bottom: 15px; }
                .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; }
                .header p { color: rgba(255,255,255,0.8); font-size: 14px; margin: 8px 0 0; }
                .content { padding: 28px; }
                .success-icon { text-align: center; margin-bottom: 20px; }
                .success-icon span { font-size: 50px; }
                .title { font-size: 22px; font-weight: 700; color: #1a2a3a; margin-bottom: 15px; text-align: center; }
                .info-box { background: #f7f9fc; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #e8ecf0; }
                .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eef2f6; }
                .info-row:last-child { border-bottom: none; }
                .info-label { font-weight: 600; color: #1a2a3a; }
                .info-value { color: #5a6a7a; }
                .btn { display: inline-block; background: #0a66c2; color: white; padding: 12px 28px; text-decoration: none; border-radius: 40px; font-weight: 600; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #eef2f6; }
                .footer-text { font-size: 12px; color: #9aaab9; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    ' . (!empty($logo_url) ? '<img src="' . $logo_url . '" alt="' . htmlspecialchars($site_name) . '" class="header-logo">' : '') . '
                    <h1>✅ Demande reçue</h1>
                    <p>' . htmlspecialchars($site_name) . '</p>
                </div>
                <div class="content">
                    <div class="success-icon"><span>📋</span></div>
                    <div class="title">Votre demande de advisory service a été enregistrée</div>
                    <div class="info-box">
                        <div class="info-row"><span class="info-label">Numéro de suivi</span><span class="info-value">' . htmlspecialchars($data['numero_consultation']) . '</span></div>
                        <div class="info-row"><span class="info-label">Expert</span><span class="info-value">Dr. ' . $doctor_name . '</span></div>
                        <div class="info-row"><span class="info-label">Date demandée</span><span class="info-value">' . $appointment_date . '</span></div>
                        <div class="info-row"><span class="info-label">Montant</span><span class="info-value">' . $data['consultation_prix'] . ' ' . $data['consultation_devise'] . '</span></div>
                        <div class="info-row"><span class="info-label">Statut</span><span class="info-value">En attente de confirmation</span></div>
                    </div>
                    <div style="text-align: center;"><a href="' . $whatsapp_url . '" class="btn">Suivre ma demande sur WhatsApp</a></div>
                </div>
                <div class="footer">
                    <div class="footer-text">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . ' - Tous droits réservés</div>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Email expert version cPanel
     */
    private function _build_doctor_email_cpanel($data)
    {
        $data = $data['data'];
        $patient = $data['patient'];
        $doctor = $data['doctor'];
        $site_name = $data['site_name'];
        $logo_url = $data['logo_url'];
        
        $attachments_list = '';
        if (!empty($data['medical_docs'])) {
            $attachments_list .= '<li>Supporting documents: ' . count($data['medical_docs']) . ' file(s)</li>';
        }
        if (!empty($data['prescriptions'])) {
            $attachments_list .= '<li>Medical documents: ' . count($data['prescriptions']) . ' file(s)</li>';
        }
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
             <title>Nouvelle advisory request - A.G.F</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
                .container { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05); }
                .header { background: linear-gradient(135deg, #0a2540, #0f4c3a); padding: 30px 24px; text-align: center; }
                .header-logo { max-width: 100px; margin-bottom: 15px; }
                .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; }
                .content { padding: 28px; }
                .title { font-size: 22px; font-weight: 700; color: #1a2a3a; margin-bottom: 15px; }
                .info-box { background: #f7f9fc; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #e8ecf0; }
                .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eef2f6; }
                .btn { display: inline-block; background: #0a66c2; color: white; padding: 12px 28px; text-decoration: none; border-radius: 40px; font-weight: 600; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #eef2f6; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    ' . (!empty($logo_url) ? '<img src="' . $logo_url . '" alt="' . htmlspecialchars($site_name) . '" class="header-logo">' : '') . '
                    <h1>🩺 Nouvelle advisory request</h1>
                </div>
                <div class="content">
                    <div class="title">Vous avez reçu une nouvelle demande</div>
                    <div class="info-box">
                        <div class="info-row"><strong>Patient:</strong> <span>' . htmlspecialchars($data['full_name']) . '</span></div>
                        <div class="info-row"><strong>Âge:</strong> <span>' . $data['age'] . ' ans</span></div>
                        <div class="info-row"><strong>Pays:</strong> <span>' . htmlspecialchars($data['country']) . '</span></div>
                        <div class="info-row"><strong>WhatsApp:</strong> <span>' . htmlspecialchars($data['whatsapppatient']) . '</span></div>
                        <div class="info-row"><strong>Numéro de suivi:</strong> <span>' . $data['numero_consultation'] . '</span></div>
                        <div class="info-row"><strong>Honoraires:</strong> <span>' . $data['consultation_prix'] . ' ' . $data['consultation_devise'] . '</span></div>
                    </div>
                    <div style="margin: 20px 0;"><strong>Condition:</strong><p style="margin-top: 5px;">' . nl2br(htmlspecialchars($data['symptoms'])) . '</p></div>
                    <div style="text-align: center;"><a href="' . base_url('Dashboard/doctor_dashboard') . '" class="btn">Voir dans le tableau de bord</a></div>
                </div>
                <div class="footer"><div class="footer-text">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . '</div></div>
            </div>
        </body>
        </html>';
    }

    /**
     * Email admin version cPanel
     */
    private function _build_admin_email_cpanel($data)
    {
        $data = $data['data'];
        $site_name = $data['site_name'];
        $logo_url = $data['logo_url'];
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
             <title>Nouvelle advisory request - Admin</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
                .container { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; }
                .header { background: #0a2540; padding: 20px; text-align: center; color: white; }
                .content { padding: 28px; }
                .info-box { background: #f7f9fc; border-radius: 12px; padding: 20px; margin: 20px 0; }
                .btn { background: #0a66c2; color: white; padding: 12px 28px; text-decoration: none; border-radius: 40px; display: inline-block; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header"><h2>📋 Nouvelle advisory request créée</h2></div>
                <div class="content">
                    <div class="info-box">
                        <p><strong>Numéro:</strong> ' . $data['numero_consultation'] . '</p>
                        <p><strong>Patient:</strong> ' . htmlspecialchars($data['full_name']) . '</p>
                        <p><strong>Montant:</strong> ' . $data['consultation_prix'] . ' ' . $data['consultation_devise'] . '</p>
                    </div>
                    <div style="text-align: center;"><a href="' . base_url('admin/consultations') . '" class="btn">Voir dans l\'admin</a></div>
                </div>
                <div class="footer" style="background:#f8fafc; padding:20px; text-align:center;">© ' . date('Y') . ' ' . htmlspecialchars($site_name) . '</div>
            </div>
        </body>
        </html>';
    }
}