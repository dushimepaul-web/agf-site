<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Le contrôleur vit dans le module Administration mais le modèle est
        // dans Administration/models : MY_Controller ne peut pas l'auto-charger
        // (il cherche modules/Logs/models/...). Chargement explicite obligatoire.
        $this->load->model('Administration/Logs_model');
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
        $limit = (int)$this->input->get('limit', true);
        $limit = ($limit > 0) ? min($limit, 500) : 100;
        $offset = max(0, (int)$this->input->get('offset', true));
        $filters = [];
        if ($this->input->get('user_id', true)) $filters['user_id'] = (int)$this->input->get('user_id', true);
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
        $r = $this->Logs_model->get_log((int)$id);
        if (!$r) { $this->json_error('Journal non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_clear()
    {
        $this->require_post();
        $this->Logs_model->clear_logs();
        // L'effacement de l'audit doit être lui-même audité : on ré-injecte
        // une trace juste après le TRUNCATE (sinon elle serait supprimée).
        $this->Model->log_history(
            $this->session->userdata('id_utilisateur'),
            'logs_cleared',
            'Effacement intégral des journaux d\'activité par ' . $this->session->userdata('email')
        );
        $this->json_success(null, 'Journaux effacés');
    }
}
