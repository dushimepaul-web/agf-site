<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Consultation/Consultation_model');
        $this->load->helper('url');
    }

    // Liste des médecins disponibles
    public function index()
    {
        $data['title'] = 'Consultation en ligne';
        $data['medecins'] = $this->Consultation_model->get_medecins();
        $this->load->view('consultation/index', $data);
    }

    // Formulaire de consultation pour un médecin
    public function formulaire($medecin_id)
    {
        $data['medecin'] = $this->Consultation_model->get_medecin($medecin_id);
        if (!$data['medecin']) show_404();
        $data['horaires'] = $this->Consultation_model->get_horaires($medecin_id);
        $data['title'] = 'Consultation - ' . $data['medecin']['prenom'] . ' ' . $data['medecin']['nom'];
        $this->load->view('consultation/formulaire', $data);
    }

    // Soumettre la consultation (API)
    public function api_submit()
    {
        if (strtoupper($this->input->server('REQUEST_METHOD', 'GET')) !== 'POST') {
            $this->json_error('Méthode non autorisée', 405);
        }

        $data = $this->get_json_input();
        if (!$data) {
            $this->json_error('Données invalides');
        }

        // Validation
        $required = ['medecin_id', 'nom', 'prenom', 'description_symptomes'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->json_error("Le champ '$field' est obligatoire");
            }
        }

        $insert = [
            'medecin_id' => (int)$data['medecin_id'],
            'nom' => trim($data['nom']),
            'prenom' => trim($data['prenom']),
            'poids' => trim($data['poids'] ?? ''),
            'taille' => trim($data['taille'] ?? ''),
            'adresse' => trim($data['adresse'] ?? ''),
            'description_symptomes' => trim($data['description_symptomes']),
            'duree_symptomes' => trim($data['duree_symptomes'] ?? ''),
        ];

        $id = $this->Consultation_model->create_consultation($insert);
        if ($id) {
            $this->json_success(['id' => $id], 'Consultation enregistrée');
        }
        $this->json_error('Erreur lors de l\'enregistrement');
    }

    // Upload média (images médicales + preuve paiement)
    public function api_upload_media()
    {
        if (strtoupper($this->input->server('REQUEST_METHOD', 'GET')) !== 'POST') {
            $this->json_error('Méthode non autorisée', 405);
        }

        $config['upload_path'] = FCPATH . 'attachments/Consultations/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 5120;
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $this->json_error($this->upload->display_errors());
        }

        $upload = $this->upload->data();
        $path = 'attachments/Consultations/' . $upload['file_name'];

        $this->json_success(['path' => $path, 'filename' => $upload['file_name']]);
    }

    // Lier un média à une consultation
    public function api_add_media()
    {
        if (strtoupper($this->input->server('REQUEST_METHOD', 'GET')) !== 'POST') {
            $this->json_error('Méthode non autorisée', 405);
        }

        $data = $this->get_json_input();
        if (empty($data['consultation_id']) || empty($data['fichier_url'])) {
            $this->json_error('Données incomplètes');
        }

        $insert = [
            'consultation_id' => (int)$data['consultation_id'],
            'fichier_url' => trim($data['fichier_url']),
            'type' => trim($data['type'] ?? 'medical'),
            'ordre' => (int)($data['ordre'] ?? 0),
        ];

        $id = $this->Consultation_model->add_media($insert);
        if ($id) {
            $this->json_success(['id' => $id], 'Média ajouté');
        }
        $this->json_error('Erreur lors de l\'ajout du média');
    }

    // Générer le lien WhatsApp
    public function api_whatsapp($consultation_id)
    {
        $c = $this->Consultation_model->get_consultation($consultation_id);
        if (!$c) {
            $this->json_error('Consultation non trouvée', 404);
        }

        $m = $this->Consultation_model->get_medecin($c['medecin_id']);
        $whatsapp = $m['telephone'] ?? '';

        $msg = "Nouvelle consultation\n";
        $msg .= "Médecin: Dr. {$m['prenom']} {$m['nom']}\n";
        $msg .= "Patient: {$c['prenom']} {$c['nom']}\n";
        if ($c['poids']) $msg .= "Poids: {$c['poids']}\n";
        if ($c['taille']) $msg .= "Taille: {$c['taille']}\n";
        if ($c['adresse']) $msg .= "Adresse: {$c['adresse']}\n";
        $msg .= "Symptômes: {$c['description_symptomes']}\n";
        if ($c['duree_symptomes']) $msg .= "Durée: {$c['duree_symptomes']}\n";

        $url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp) . '?text=' . urlencode($msg);

        $this->json_success(['url' => $url, 'whatsapp' => $whatsapp]);
    }
}
