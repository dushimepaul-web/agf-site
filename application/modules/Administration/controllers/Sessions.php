<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Sessions utilisateurs';
        $this->load->view('sessions/index', $data);
    }

    public function api_list() {
        $q = $this->db->select('user_sessions.*, users.username, users.nom, users.prenom')
            ->join('users', 'users.id = user_sessions.user_id', 'left')
            ->order_by('user_sessions.login_time', 'DESC')
            ->limit(500)
            ->get('user_sessions');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_terminate($id) {
        $this->require_post();
        $existing = $this->Model->readOne('user_sessions', ['id' => $id]);
        if (!$existing) { $this->json_error('Session non trouvée', 404); return; }

        $this->db->where('id', $id)->update('user_sessions', [
            'is_active' => 0,
            'logout_time' => date('Y-m-d H:i:s'),
            'logout_reason' => 'force_admin'
        ]);
        $this->json_success(null, 'Session terminée');
    }
}
