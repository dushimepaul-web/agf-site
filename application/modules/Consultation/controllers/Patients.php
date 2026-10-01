<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patients extends MY_Controller {

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

    public function index() {
        if (!$this->is_admin() && !$this->is_medecin()) {
            show_error('Accès refusé', 403);
        }
        $data['title'] = 'Gestion des patients';
        $this->load->view('admin/patients', $data);
    }

    public function api_list() {
        $this->db->select('
            patient_nom, patient_prenom, patient_poids, patient_taille,
            patient_adresse, whatsapppatient, country_id, age,
            COUNT(*) AS nb_consultations,
            MIN(created_at) AS premiere_consultation,
            MAX(created_at) AS derniere_consultation
        ');
        $this->db->group_by('patient_nom, patient_prenom, whatsapppatient');

        if ($this->is_medecin()) {
            $medecin_id = $this->get_my_medecin_id();
            if ($medecin_id) {
                $this->db->where('medecin_id', $medecin_id);
            }
        }

        $this->db->order_by('derniere_consultation', 'DESC');
        $q = $this->db->get('consultations');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_detail() {
        $nom = $this->input->get('nom', true);
        $prenom = $this->input->get('prenom', true);
        $whatsapp = $this->input->get('whatsapp', true);

        if (!$nom || !$prenom) { $this->json_error('Paramètres manquants'); return; }

        $this->db->where('patient_nom', $nom);
        $this->db->where('patient_prenom', $prenom);
        if ($whatsapp) $this->db->where('whatsapppatient', $whatsapp);

        if ($this->is_medecin()) {
            $medecin_id = $this->get_my_medecin_id();
            if ($medecin_id) $this->db->where('medecin_id', $medecin_id);
        }

        $this->db->join('medecins', 'medecins.id = consultations.medecin_id', 'left');
        $this->db->join('users', 'users.id = medecins.user_id', 'left');
        $this->db->select('consultations.*, CONCAT(users.prenom, " ", users.nom) AS medecin_nom');
        $this->db->order_by('consultations.created_at', 'DESC');
        $q = $this->db->get('consultations');

        $this->json_success($q !== false ? $q->result_array() : []);
    }
}
