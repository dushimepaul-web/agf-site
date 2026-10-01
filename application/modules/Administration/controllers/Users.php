<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Gestion des utilisateurs';
        $data['roles'] = $this->Model->read('roles', [], 'nom', 'ASC');
        $this->load->view('users/index', $data);
    }

    public function api_list() {
        $q = $this->db->select('users.*, roles.nom AS role_nom')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->order_by('users.created_at', 'DESC')
            ->get('users');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_get($id) {
        $r = $this->Model->readOne('users', ['uuid' => $id]);
        if (!$r) { $this->json_error('Utilisateur non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create() {
        $data = $this->get_json_input();
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $role_id = (int)($data['role_id'] ?? 0);
        $password = $data['password'] ?? '';

        if ($username === '' || $email === '' || $nom === '' || $role_id <= 0) {
            $this->json_error('Username, email, nom et rôle obligatoires'); return;
        }
        if ($password === '' || strlen($password) < 6) {
            $this->json_error('Le mot de passe doit faire au moins 6 caractères'); return;
        }
        if ($this->Model->readOne('users', ['username' => $username])) {
            $this->json_error('Ce nom d\'utilisateur existe déjà'); return;
        }
        if ($this->Model->readOne('users', ['email' => $email])) {
            $this->json_error('Cet email est déjà utilisé'); return;
        }

        $insert = [
            'uuid' => $this->uuid->v4(),
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => trim($data['telephone'] ?? '') !== '' ? trim($data['telephone']) : null,
            'role_id' => $role_id,
            'actif' => isset($data['actif']) ? (int)$data['actif'] : 1,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ];
        if ($this->Model->create('users', $insert)) {
            $this->json_success(['id' => $this->db->insert_id()], 'Utilisateur créé');
        }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('users', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Utilisateur non trouvé', 404); return; }

        $update = [];
        if (isset($data['username'])) {
            $username = trim($data['username']);
            if ($username === '') { $this->json_error('Le nom d\'utilisateur est obligatoire'); return; }
            $dup = $this->Model->readOne('users', ['username' => $username, 'id !=' => $existing['id']]);
            if ($dup) { $this->json_error('Ce nom d\'utilisateur existe déjà'); return; }
            $update['username'] = $username;
        }
        if (isset($data['email'])) {
            $email = trim($data['email']);
            if ($email === '') { $this->json_error('L\'email est obligatoire'); return; }
            $dup = $this->Model->readOne('users', ['email' => $email, 'id !=' => $existing['id']]);
            if ($dup) { $this->json_error('Cet email est déjà utilisé'); return; }
            $update['email'] = $email;
        }
        if (isset($data['nom'])) {
            $nom = trim($data['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (isset($data['prenom'])) { $update['prenom'] = trim($data['prenom']); }
        if (isset($data['telephone'])) { $update['telephone'] = trim($data['telephone']) !== '' ? trim($data['telephone']) : null; }
        if (isset($data['role_id']) && (int)$data['role_id'] > 0) { $update['role_id'] = (int)$data['role_id']; }
        if (array_key_exists('actif', $data)) { $update['actif'] = (int)$data['actif']; }
        if (array_key_exists('is_active', $data)) { $update['is_active'] = (int)$data['is_active']; }

        if (!empty($data['password']) && strlen($data['password']) >= 6) {
            $update['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('users', ['uuid' => $id], $update)) {
            $this->json_success(null, 'Utilisateur mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('users', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Utilisateur non trouvé', 404); return; }
        if ((int)$existing['role_id'] === 1) {
            $this->json_error('Impossible de supprimer un administrateur'); return;
        }

        if ($this->db->where('uuid', $id)->delete('users')) {
            $this->json_success(null, 'Utilisateur supprimé');
        }
        $this->json_error('Erreur lors de la suppression');
    }
}
