<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
    }

    public function index()
    {
        $data['title'] = 'Journaux d\'activité';
        $this->load->view('logs/list', $data);
    }

    public function api_list()
    {
        $limit = (int)$this->input->get('limit', true) ?: 100;
        $offset = (int)$this->input->get('offset', true) ?: 0;
        $filters = [];
        if ($this->input->get('user_id', true)) $filters['user_id'] = $this->input->get('user_id', true);
        if ($this->input->get('action', true)) $filters['action'] = $this->input->get('action', true);
        if ($this->input->get('niveau', true)) $filters['niveau'] = $this->input->get('niveau', true);
        if ($this->input->get('date_from', true)) $filters['date_from'] = $this->input->get('date_from', true);
        if ($this->input->get('date_to', true)) $filters['date_to'] = $this->input->get('date_to', true);

        $total = $this->Logs_model->count_logs($filters);
        $rows = $this->Logs_model->get_logs($limit, $offset, $filters);
        $this->json_success(['total' => $total, 'data' => $rows]);
    }

    public function api_get($id)
    {
        $r = $this->Logs_model->get_log($id);
        if (!$r) { $this->json_error('Journal non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_clear()
    {
        $this->require_post();
        $this->Logs_model->clear_logs();
        $this->json_success(null, 'Journaux effacés');
    }
}
