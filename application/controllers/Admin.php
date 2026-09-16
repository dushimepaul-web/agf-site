<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function index()
    {
        $data['logo'] = $this->Model->get_setting('logo_app', 'assets/images/logo.png');
        $data['login_img'] = $this->Model->get_setting('login_img', '');
        $data['nom_ecole'] = $this->Model->get_setting('nom_app', 'Surveillance des maladies');
        $this->load->view('Login_View', $data);
    }

    public function register()
    {
        // L'inscription publique est désactivée par défaut (le cahier des
        // charges ne la prévoit pas : les comptes sont créés par un admin).
        // Réactivable via ALLOW_PUBLIC_REGISTRATION=1 (variable d'environnement).
        if (!config_item('allow_public_registration')) {
            $sms['sms'] = '<div id="message" class="alert alert-warning text-center">L\'inscription publique est désactivée. Contactez l\'administrateur pour créer un compte.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin'));
            return;
        }
        $data['logo'] = $this->Model->get_setting('logo_app', 'assets/images/logo.png');
        $data['nom_ecole'] = $this->Model->get_setting('nom_app', 'Surveillance des maladies');
        $this->load->view('Register_View', $data);
    }

    public function Login()
    {
        redirect(base_url('Admin'));
    }

    private function _get_login_attempts()
    {
        $this->load->driver('cache', array('adapter' => 'file'));
        $key = 'login_attempts_' . md5($this->input->ip_address());
        $attempts = $this->cache->file->get($key);
        return $attempts && is_array($attempts) ? $attempts : array('count' => 0, 'time' => time());
    }

    private function _set_login_attempts($attempts)
    {
        $this->load->driver('cache', array('adapter' => 'file'));
        $key = 'login_attempts_' . md5($this->input->ip_address());
        $this->cache->file->save($key, $attempts, 900);
    }

    private function _clear_login_attempts()
    {
        $this->load->driver('cache', array('adapter' => 'file'));
        $key = 'login_attempts_' . md5($this->input->ip_address());
        $this->cache->file->delete($key);
    }

    public function do_login()
    {
        $username = $this->input->post('email');
        $password = $this->input->post('password');

        // Rate limiting: max 5 tentatives en 15 min par adresse IP
        $attempts = $this->_get_login_attempts();
        if ($attempts['count'] >= 5 && (time() - $attempts['time']) < 900) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Trop de tentatives!</strong> Veuillez réessayer dans 15 minutes.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin'));
            return;
        }

        $user_query = $this->db->where('email', $username)->where('actif', 1)->get('users');
        $user = ($user_query !== false) ? $user_query->row_array() : null;

        if ($user && password_verify($password, $user['password_hash'])) {
            // Reset login attempts on success
            $this->_clear_login_attempts();

            $role_query = $this->db->where('id', $user['role_id'])->get('roles');
            $role = ($role_query !== false) ? $role_query->row_array() : null;

            $session = array(
                'id_utilisateur' => $user['id'],
                'uuid' => $user['uuid'],
                'email' => $user['email'],
                'nom_complet' => $user['nom'] . ' ' . $user['prenom'],
                'user' => $user['username'],
                'id_role' => $user['role_id'],
                'role_code' => $role ? $role['code'] : '',
                'role_libelle' => $role ? $role['nom'] : '',
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session);

            // Mettre à jour derniere_connexion
            $this->db->where('id', $user['id'])->update('users', ['derniere_connexion' => date('Y-m-d H:i:s')]);

            redirect(base_url('Dashboard'));
        } else {
            $attempts['count']++;
            $attempts['time'] = time();
            $this->_set_login_attempts($attempts);
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center">
                <strong>Oups!</strong> Email ou mot de passe incorrect.
            </div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin'));
        }
    }

    public function do_register()
    {
        // Garde-fou : même si la route est appelée directement, l'inscription
        // publique reste soumise au flag de configuration.
        if (!config_item('allow_public_registration')) {
            redirect(base_url('Admin'));
            return;
        }

        // Rate limiting identique au login (5 tentatives / 15 min par IP)
        $attempts = $this->_get_login_attempts();
        if ($attempts['count'] >= 5 && (time() - $attempts['time']) < 900) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Trop de tentatives!</strong> Veuillez réessayer dans 15 minutes.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        $nom_complet = $this->input->post('nom_complet');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $confirm = $this->input->post('confirm_password');

        if (!$nom_complet || !$email || !$password || !$confirm) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Tous les champs sont requis.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        if ($password !== $confirm) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Les mots de passe ne correspondent pas.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        if (strlen($password) < 6) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Le mot de passe doit contenir au moins 6 caractères.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Adresse email invalide.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        if (mb_strlen($nom_complet) > 150 || mb_strlen($email) > 150 || mb_strlen($password) > 72) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Données trop longues.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        $existing_query = $this->db->where('email', $email)->get('users');
        $existing = ($existing_query !== false) ? $existing_query->row_array() : null;
        if ($existing) {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Cet email est déjà utilisé.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
            return;
        }

        // Rôle par défaut sécurisé : Agent de saisie. Un nouvel inscrit ne reçoit
        // JAMAIS le rôle Administrateur (le rôle ADMIN est attribué uniquement par un admin).
        $role_query = $this->db->where('code', 'AGENT_SAISIE')->get('roles');
        $role = ($role_query !== false) ? $role_query->row_array() : null;
        if ($role) {
            $role_id = (int)$role['id'];
        } else {
            $this->db->insert('roles', array(
                'code' => 'AGENT_SAISIE',
                'nom' => 'Agent de saisie',
                'description' => 'Saisie des données de surveillance (rôle par défaut à l\'inscription)'
            ));
            $role_id = (int)$this->db->insert_id();
        }

        $parts = explode(' ', trim($nom_complet), 2);
        $nom = $parts[0];
        $prenom = isset($parts[1]) ? $parts[1] : '';
        $username = strtolower(str_replace([' ', '@', '.'], ['', '', ''], $email));

        $inserted = $this->db->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'nom' => $nom,
            'prenom' => $prenom,
            'role_id' => $role_id,
            'actif' => 1
        ]);

        if ($inserted) {
            $sms['sms'] = '<div id="message" class="alert alert-success text-center"><strong>Succès!</strong> Votre compte a été créé. Connectez-vous.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin'));
        } else {
            $sms['sms'] = '<div id="message" class="alert alert-danger text-center"><strong>Erreur!</strong> Une erreur est survenue lors de la création du compte.</div>';
            $this->session->set_flashdata($sms);
            redirect(base_url('Admin/register'));
        }
    }

    public function Logout()
    {
        // La déconnexion s'effectue exclusivement en POST (CSRF + anti-logout
        // forcé). Un GET ne détruit pas la session, il redirige simplement.
        if (strtoupper($this->input->server('REQUEST_METHOD', 'GET')) !== 'POST') {
            redirect(base_url('Dashboard'));
            return;
        }
        // Supprime les données de session côté serveur puis le cookie navigateur
        $this->session->sess_destroy();
        $cookie_name = config_item('sess_cookie_name') ?: 'ci_session';
        if (isset($_COOKIE[$cookie_name])) {
            unset($_COOKIE[$cookie_name]);
        }
        setcookie($cookie_name, '', time() - 42000, config_item('cookie_path') ?: '/');
        redirect(base_url('Admin'));
    }
}
