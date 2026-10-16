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
        $data = $this->get_json_input() ?: [];
        $code = strtoupper(trim($data['code'] ?? ''));
        $nom = trim($data['nom'] ?? '');
        if ($code === '' || $nom === '') {
            $this->json_error('Code et nom obligatoires'); return;
        }
        if (mb_strlen($code) > 50 || mb_strlen($nom) > 100) {
            $this->json_error('Code ou nom trop long'); return;
        }
        if ($this->Model->readOne('roles', ['code' => $code])) {
            $this->json_error('Ce code existe déjà'); return;
        }
        // uuid NOT NULL + unique en base : obligatoire à l'insertion
        $insert = [
            'uuid' => generate_uuid(),
            'code' => $code,
            'nom' => $nom,
            'description' => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null
        ];
        if ($this->db->insert('roles', $insert)) {
            $new_id = $this->db->insert_id();
            $this->Model->log_history($this->session->userdata('id_utilisateur'), 'role_created', "Création du rôle {$code} (id {$new_id})");
            $this->json_success(['id' => $new_id], 'Rôle créé');
        }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id) {
        $data = $this->get_json_input() ?: [];
        $existing = $this->Model->readOne('roles', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Rôle non trouvé', 404); return; }
        $is_admin_role = (int)$existing['id'] === 1 || strtoupper($existing['code']) === 'ADMIN';

        $update = [];
        if (isset($data['code'])) {
            $code = strtoupper(trim($data['code']));
            if ($code === '') { $this->json_error('Le code est obligatoire'); return; }
            if ($is_admin_role && $code !== strtoupper($existing['code'])) {
                $this->json_error('Le code du rôle administrateur ne peut pas être modifié'); return;
            }
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
            $this->Model->log_history($this->session->userdata('id_utilisateur'), 'role_updated', "Mise à jour du rôle {$existing['code']} : " . implode(', ', array_keys($update)));
            $this->json_success(null, 'Rôle mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('roles', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Rôle non trouvé', 404); return; }
        if ((int)$existing['id'] === 1 || strtoupper($existing['code']) === 'ADMIN') {
            $this->json_error('Impossible de supprimer le rôle administrateur'); return;
        }
        $nb = $this->db->where('role_id', $existing['id'])->count_all_results('users');
        if ($nb > 0) { $this->json_error('Impossible : des utilisateurs sont rattachés à ce rôle'); return; }

        $this->db->trans_begin();
        $this->db->where('role_id', $existing['id'])->delete('roles_droits');
        $ok = $this->db->where('uuid', $id)->delete('roles');
        if (!$ok || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->json_error('Erreur lors de la suppression');
            return;
        }
        $this->db->trans_commit();

        $this->Model->log_history($this->session->userdata('id_utilisateur'), 'role_deleted', "Suppression du rôle {$existing['code']} (id {$existing['id']})");
        $this->json_success(null, 'Rôle supprimé');
    }
}
