<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Gestion des rôles';
        $this->load->view('roles/index', $data);
    }

    public function api_list() {
        $this->db->order_by('nom', 'ASC');
        $q = $this->db->get('roles');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_get($id) {
        $r = $this->Model->readOne('roles', ['uuid' => $id]);
        if (!$r) { $this->json_error('Rôle non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create() {
        $data = $this->get_json_input();
        $code = strtoupper(trim($data['code'] ?? ''));
        $nom = trim($data['nom'] ?? '');
        if ($code === '' || $nom === '') {
            $this->json_error('Code et nom obligatoires'); return;
        }
        if ($this->Model->readOne('roles', ['code' => $code])) {
            $this->json_error('Ce code existe déjà'); return;
        }
        $insert = [
            'code' => $code,
            'nom' => $nom,
            'description' => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null
        ];
        if ($this->db->insert('roles', $insert)) {
            $this->json_success(['id' => $this->db->insert_id()], 'Rôle créé');
        }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('roles', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Rôle non trouvé', 404); return; }

        $update = [];
        if (isset($data['code'])) {
            $code = strtoupper(trim($data['code']));
            if ($code === '') { $this->json_error('Le code est obligatoire'); return; }
            $dup = $this->Model->readOne('roles', ['code' => $code, 'id !=' => $existing['id']]);
            if ($dup) { $this->json_error('Ce code existe déjà'); return; }
            $update['code'] = $code;
        }
        if (isset($data['nom'])) {
            $nom = trim($data['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (array_key_exists('description', $data)) {
            $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null;
        }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('roles', ['uuid' => $id], $update)) {
            $this->json_success(null, 'Rôle mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('roles', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Rôle non trouvé', 404); return; }
        $nb = $this->db->where('role_id', $existing['id'])->count_all_results('users');
        if ($nb > 0) { $this->json_error('Impossible : des utilisateurs sont rattachés à ce rôle'); return; }

        $this->db->trans_start();
        $this->db->where('role_id', $existing['id'])->delete('roles_droits');
        $ok = $this->db->where('uuid', $id)->delete('roles');
        $this->db->trans_complete();

        if ($ok && $this->db->trans_status()) {
            $this->json_success(null, 'Rôle supprimé');
        }
        $this->json_error('Erreur lors de la suppression');
    }
}

