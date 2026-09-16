<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
    public $group_name = "";
    public $menus_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->_hmvc_fixes();
        $this->_load_module_model();

        try {
            if (empty($this->session->userdata('logged_in'))) {
                $session_data = array('logged_in' => FALSE);
                $this->session->set_userdata($session_data);
            } else {
                $uuid = $this->session->userdata('uuid');
                $user_query = $this->db->where('uuid', $uuid)->get('users');
                $user_data = ($user_query !== false) ? $user_query->row_array() : null;
                if ($user_data) {
                    $role_query = $this->db->where('id', $user_data['role_id'])->get('roles');
                    $role_data = ($role_query !== false) ? $role_query->row_array() : null;
                    $this->group_name = $role_data ? $role_data['nom'] : '';
                    $this->session->set_userdata('role_libelle', $this->group_name);
                }
            }
        } catch (Throwable $e) {
            log_message('error', 'MY_Controller user load failed: ' . $e->getMessage());
        }
    }

    function _hmvc_fixes()
    {
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
    }

    private function _load_module_model()
    {
        $class = get_class($this);
        if ($class === 'MY_Controller' || $class === 'MX_Controller') return;
        $parts = explode('\\', $class);
        $class = end($parts);
        $model_name = $class . '_model';
        $model_file = APPPATH . 'modules/' . $class . '/models/' . $model_name . '.php';
        if (file_exists($model_file)) {
            $this->load->model($model_name);
        }
    }

    public function render_view($view, $data = array())
    {
        $data['user_fullname'] = $this->session->userdata('nom_complet');
        $data['user_role'] = $this->group_name;
        $this->load->view($view, $data);
    }

    public function not_logged_in()
    {
        if ($this->session->userdata('logged_in') == FALSE) {
            if ($this->input->is_ajax_request() || strpos($this->uri->uri_string(), 'api/') !== false) {
                $this->json_error('Session expirée', 401);
            }
            redirect(base_url('Admin'));
        }
    }

    protected function json_response($data, $status_code = 200)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $json = json_encode(array('success' => false, 'message' => 'Réponse non sérialisable'), JSON_UNESCAPED_UNICODE);
            $status_code = 500;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output($json)
            ->_display();
        exit;
    }

    protected function json_success($data = null, $message = 'Succès')
    {
        $this->json_response(['success' => true, 'message' => $message, 'data' => $data]);
    }

    protected function json_error($message = 'Erreur', $status_code = 400)
    {
        $this->json_response(['success' => false, 'message' => $message], $status_code);
    }

    /**
     * Vrai si la requête courante est une requête API ou AJAX.
     */
    protected function is_api_request()
    {
        return $this->input->is_ajax_request()
            || strpos($this->input->server('REQUEST_URI'), '/api/') !== false
            || strpos($this->uri->uri_string(), 'api/') !== false;
    }

    /**
     * Contrôle d'accès centralisé : rôle administrateur uniquement.
     */
    protected function require_admin()
    {
        $this->not_logged_in();
        if ((int)$this->session->userdata('id_role') !== 1) {
            if ($this->is_api_request()) {
                $this->json_error('Accès refusé : privilèges administrateur requis', 403);
            }
            show_error('Accès refusé : privilèges administrateur requis', 403);
        }
    }

    /**
     * Restreint une action aux requêtes POST (protège contre le CSRF par GET).
     */
    protected function require_post()
    {
        if (strtoupper($this->input->server('REQUEST_METHOD', 'GET')) !== 'POST') {
            $this->json_error('Méthode non autorisée (POST requis)', 405);
        }
    }

    /**
     * Génère le prochain identifiant séquentiel (patient_uid, case_code...)
     * de manière ATOMIQUE : verrou MySQL GET_LOCK pour éviter les collisions
     * entre deux insertions simultanées (requête d'incrément sérialisée).
     * Retourne null si le verrou n'a pas pu être acquis.
     */
    protected function next_uid($table, $column, $prefix)
    {
        $lock_key = 'uid_' . $table . '_' . $column . '_' . date('Y');
        $q = $this->db->query('SELECT GET_LOCK(' . $this->db->escape($lock_key) . ', 10) AS locked');
        $lock = ($q !== false) ? $q->row_array() : null;
        if (!$lock || (int)$lock['locked'] !== 1) return null;

        try {
            $like = $prefix . date('Y') . '-';
            $q = $this->db->select($column)->from($table)
                ->like($column, $like, 'after')
                ->order_by($column, 'DESC')->limit(1)->get();
            $last = ($q !== false) ? $q->row_array() : null;
            $seq = $last ? ((int)substr($last[$column], -6)) + 1 : 1;
            return sprintf('%s%s%06d', $prefix, date('Y') . '-', $seq);
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(' . $this->db->escape($lock_key) . ')');
        }
    }

    /**
     * Paramètres de pagination optionnels des listes (backward-compatible).
     * Accepte : ?limit=N&offset=N  ou  ?page=N&per_page=N  ou  ?start=N&length=N
     * Retourne array(limit, offset) ou null si aucun paramètre fourni (=> liste complète).
     */
    protected function paginate_params()
    {
        $get = function ($k) {
            $v = $this->input->get($k, true);
            return ($v !== null && $v !== '' && is_numeric($v) && (int)$v >= 0) ? (int)$v : null;
        };

        $limit = null; $offset = null;
        if (($v = $get('limit')) !== null) $limit = $v;
        if (($v = $get('per_page')) !== null) $limit = $v;
        if (($v = $get('length')) !== null) $limit = $v;
        if (($v = $get('offset')) !== null) $offset = $v;
        if (($v = $get('page')) !== null && $v >= 1) $offset = ($v - 1) * ($limit !== null ? $limit : 15);
        if (($v = $get('start')) !== null) $offset = $v;

        if ($limit === null && $offset === null) return null;
        return array($limit !== null ? $limit : 15, $offset !== null ? $offset : 0);
    }

    protected function get_json_input()
    {
        $input = file_get_contents('php://input');
        if (!$input) { return null; }
        // Tolérance encodage UTF-8 BOM
        if (substr($input, 0, 3) === "\xEF\xBB\xBF") { $input = substr($input, 3); }
        $data = json_decode($input, true);
        if (is_array($data)) {
            // Le jeton CSRF est injecté par api.js dans les corps JSON : il ne doit
            // jamais transiter vers les contrôleurs métier.
            $token_name = config_item('csrf_token_name') ?: 'csrf_token';
            if (isset($data[$token_name])) unset($data[$token_name]);
        }
        return $data;
    }
}
