<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultations extends MY_Controller {

    private $role_code = '';

    public function __construct() {
        parent::__construct();
        $this->not_logged_in();
        $this->load->model('Consultation/Consultation_model');
        $this->role_code = strtoupper($this->session->userdata('role_code') ?? '');
    }

    private function is_admin() {
        return in_array($this->role_code, ['ADMIN', 'SUPER_ADMIN']);
    }

    private function is_medecin() {
        return $this->role_code === 'MEDECIN';
    }

    private function get_my_medecin_id() {
        if (!$this->is_medecin()) return null;
        $medecin = $this->Consultation_model->get_medecin_by_user_id($this->session->userdata('id'));
        return $medecin ? $medecin['id'] : null;
    }

    private function clean($val) {
        $v = trim($val ?? '');
        return $v !== '' ? $v : null;
    }

    public function index() {
        if (!$this->is_admin() && !$this->is_medecin()) {
            show_error('Accès refusé', 403);
        }
        $data['title'] = $this->is_medecin() ? 'Mes consultations' : 'Gestion des consultations';
        $this->load->view('admin/index', $data);
    }

    public function medecins() {
        if (!$this->is_admin()) {
            show_error('Accès refusé', 403);
        }
        $data['title'] = 'Gestion des experts';
        $this->load->view('admin/medecins', $data);
    }

    public function medecin_form($uuid = '') {
        if (!$this->is_admin()) {
            show_error('Accès refusé', 403);
        }
        $uuid = trim($uuid);
        if ($uuid === '' || $uuid === 'null' || $uuid === '0') { $uuid = ''; }
        $data['title'] = $uuid ? 'Modifier l\'expert' : 'Ajouter un expert';
        $data['medecin'] = null;
        if ($uuid !== '') {
            $data['medecin'] = $this->Consultation_model->get_medecin_by_uuid($uuid);
            if (!$data['medecin']) {
                show_error('Expert non trouvé', 404);
            }
        }
        $this->load->view('admin/medecin_form', $data);
    }

    public function api_medecins_get($uuid) {
        $r = $this->Model->readOne('medecins', ['uuid' => $uuid]);
        if (!$r) { $this->json_error('Expert non trouvé', 404); return; }
        $user = $this->Model->readOne('users', ['id' => $r['user_id']]);
        $r['user_nom'] = $user ? $user['prenom'] . ' ' . $user['nom'] : '';
        $r['user_email'] = $user ? $user['email'] : '';
        $this->json_success($r);
    }

    public function api_list() {
        $this->db->select('consultations.*, CONCAT(users.prenom, " ", users.nom) AS medecin_nom, medecins.specialite')
            ->join('medecins', 'medecins.id = consultations.medecin_id', 'left')
            ->join('users', 'users.id = medecins.user_id', 'left');

        if ($this->is_medecin()) {
            $medecin_id = $this->get_my_medecin_id();
            if ($medecin_id) {
                $this->db->where('consultations.medecin_id', $medecin_id);
            }
        }

        $this->db->order_by('consultations.created_at', 'DESC');
        $q = $this->db->get('consultations');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_get($id) {
        $r = $this->Model->readOne('consultations', ['uuid' => $id]);
        if (!$r) { $this->json_error('Consultation non trouvée', 404); return; }

        if ($this->is_medecin()) {
            $medecin_id = $this->get_my_medecin_id();
            if ($medecin_id && (int)$r['medecin_id'] !== (int)$medecin_id) {
                $this->json_error('Accès refusé', 403); return;
            }
        }

        $medecin = $this->Consultation_model->get_medecin($r['medecin_id']);
        $r['medecin_nom'] = $medecin ? $medecin['prenom'] . ' ' . $medecin['nom'] : '';
        $r['specialite'] = $medecin ? $medecin['specialite'] : '';
        $r['medias'] = $this->Consultation_model->get_medias($r['id']);

        $this->json_success($r);
    }

    public function api_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('consultations', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Consultation non trouvée', 404); return; }

        if ($this->is_medecin()) {
            $medecin_id = $this->get_my_medecin_id();
            if ($medecin_id && (int)$existing['medecin_id'] !== (int)$medecin_id) {
                $this->json_error('Accès refusé', 403); return;
            }
        }

        $update = [];
        if (isset($data['statut'])) {
            $allowed = ['en_attente', 'en_cours', 'terminee', 'annulee'];
            if (!in_array($data['statut'], $allowed)) { $this->json_error('Statut invalide'); return; }
            $update['statut'] = $data['statut'];
        }
        if (array_key_exists('whatsapp_envoye', $data)) { $update['whatsapp_envoye'] = (int)$data['whatsapp_envoye']; }
        if (array_key_exists('diagnostic', $data)) { $update['diagnostic'] = $this->clean($data['diagnostic']); }
        if (array_key_exists('traitement', $data)) { $update['traitement'] = $this->clean($data['traitement']); }
        if (array_key_exists('ordonnances', $data)) { $update['ordonnances'] = $this->clean($data['ordonnances']); }
        if (array_key_exists('notes_medecin', $data)) { $update['notes_medecin'] = $this->clean($data['notes_medecin']); }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('consultations', ['uuid' => $id], $update)) {
            $this->json_success(null, 'Consultation mise à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('consultations', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Consultation non trouvée', 404); return; }

        if ($this->is_medecin()) {
            $this->json_error('Accès refusé : suppression réservée à l\'administration', 403); return;
        }

        $this->db->trans_start();
        $this->db->where('consultation_id', $existing['id'])->delete('consultation_medias');
        $ok = $this->db->where('uuid', $id)->delete('consultations');
        $this->db->trans_complete();

        if ($ok && $this->db->trans_status()) {
            $this->json_success(null, 'Consultation supprimée');
        }
        $this->json_error('Erreur lors de la suppression');
    }

    // --- Medecins CRUD (admin only) ---
    public function api_medecins_list() {
        $medecins = $this->Consultation_model->get_medecins(false);
        $this->json_success($medecins);
    }

    public function api_medecins_create() {
        $data = $this->get_json_input();
        $user_id = (int)($data['user_id'] ?? 0);
        $specialite = trim($data['specialite'] ?? '');
        if ($user_id <= 0 || $specialite === '') {
            $this->json_error('Utilisateur et spécialité obligatoires'); return;
        }
        if ($this->Model->readOne('medecins', ['user_id' => $user_id])) {
            $this->json_error('Cet utilisateur est déjà enregistré comme expert'); return;
        }
        $insert = [
            'uuid' => $this->uuid->v4(),
            'user_id' => $user_id,
            'specialite' => $specialite,
            'numero_licence' => $this->clean($data['numero_licence'] ?? ''),
            'annees_experience' => (int)($data['annees_experience'] ?? 0),
            'honoraires_consultation' => (float)($data['honoraires_consultation'] ?? 0),
            'bio' => $this->clean($data['bio'] ?? ''),
            'diplomes' => $this->clean($data['diplomes'] ?? ''),
            'langues_parlees' => $this->clean($data['langues_parlees'] ?? ''),
            'actif' => isset($data['actif']) ? (int)$data['actif'] : 1,
        ];
        if ($this->Model->create('medecins', $insert)) {
            $this->json_success(['id' => $this->db->insert_id()], 'Expert créé');
        }
        $this->json_error('Erreur lors de la création');
    }

    public function api_medecins_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('medecins', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Expert non trouvé', 404); return; }

        $update = [];
        if (isset($data['specialite'])) { $update['specialite'] = trim($data['specialite']); }
        if (array_key_exists('numero_licence', $data)) { $update['numero_licence'] = $this->clean($data['numero_licence']); }
        if (array_key_exists('annees_experience', $data)) { $update['annees_experience'] = (int)$data['annees_experience']; }
        if (array_key_exists('honoraires_consultation', $data)) { $update['honoraires_consultation'] = (float)$data['honoraires_consultation']; }
        if (array_key_exists('bio', $data)) { $update['bio'] = $this->clean($data['bio']); }
        if (array_key_exists('diplomes', $data)) { $update['diplomes'] = $this->clean($data['diplomes']); }
        if (array_key_exists('langues_parlees', $data)) { $update['langues_parlees'] = $this->clean($data['langues_parlees']); }
        if (array_key_exists('actif', $data)) { $update['actif'] = (int)$data['actif']; }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('medecins', ['uuid' => $id], $update)) {
            $this->json_success(null, 'Expert mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_medecins_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('medecins', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Expert non trouvé', 404); return; }

        $nb = $this->db->where('medecin_id', $existing['id'])->count_all_results('consultations');
        if ($nb > 0) { $this->json_error('Impossible : des consultations sont rattachées à cet expert'); return; }

        $this->db->trans_start();
        $this->db->where('medecin_id', $existing['id'])->delete('horaires_medecins');
        $ok = $this->db->where('uuid', $id)->delete('medecins');
        $this->db->trans_complete();

        if ($ok && $this->db->trans_status()) {
            $this->json_success(null, 'Expert supprimé');
        }
        $this->json_error('Erreur lors de la suppression');
    }
}
