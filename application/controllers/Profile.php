<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('uuid')) {
            redirect('Admin');
        }
    }

    public function index() {
        $uuid = $this->session->userdata('uuid');
        $data['user'] = $this->Model->readOne('users', ['uuid' => $uuid]);
        if (!$data['user']) show_404();
        $data['title'] = 'Mon Profil';
        $this->load->view('Profile_View', $data);
    }

    public function api_update() {
        $uuid = $this->session->userdata('uuid');
        $input = $this->get_json_input();
        if (empty($input['nom']) && empty($input['prenom']) && empty($input['email'])) {
            $this->json_error('Aucune donnée à modifier'); return;
        }
        $update = [];
        if (array_key_exists('nom', $input)) {
            $nom = trim($input['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (array_key_exists('prenom', $input)) $update['prenom'] = trim($input['prenom']);
        if (array_key_exists('email', $input)) {
            $email = trim($input['email']);
            if ($email === '') { $this->json_error('L\'email est obligatoire'); return; }
            $existing = $this->Model->readOne('users', ['email' => $email, 'uuid !=' => $uuid]);
            if ($existing) {
                $this->json_error('Cet email est déjà utilisé'); return;
            }
            $update['email'] = $email;
        }
        if (!empty($update)) {
            $this->Model->update('users', ['uuid' => $uuid], $update);
        }
        $user = $this->Model->readOne('users', ['uuid' => $uuid]);
        if ($user) {
            $this->session->set_userdata('nom_complet', $user['nom'] . ' ' . $user['prenom']);
            $this->session->set_userdata('email', $user['email']);
        }
        $this->json_success(null, 'Profil mis à jour');
    }

    public function api_change_password() {
        $uuid = $this->session->userdata('uuid');
        $input = $this->get_json_input();
        if (empty($input['current_password']) || empty($input['new_password']) || empty($input['confirm_password'])) {
            $this->json_error('Tous les champs sont requis'); return;
        }
        if ($input['new_password'] !== $input['confirm_password']) {
            $this->json_error('Les mots de passe ne correspondent pas'); return;
        }
        if (strlen($input['new_password']) < 6) {
            $this->json_error('Le mot de passe doit contenir au moins 6 caractères'); return;
        }
        $user = $this->Model->readOne('users', ['uuid' => $uuid]);
        if (!$user || !password_verify($input['current_password'], $user['password_hash'])) {
            $this->json_error('Mot de passe actuel incorrect'); return;
        }
        $this->Model->update('users', ['uuid' => $uuid], ['password_hash' => password_hash($input['new_password'], PASSWORD_DEFAULT)]);
        $this->json_success(null, 'Mot de passe changé avec succès');
    }
}
