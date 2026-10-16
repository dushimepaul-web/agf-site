<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Tableau de bord';
        $this->render_view('Dashboard_View', $data);
    }

    /**
     * Données analytiques du dashboard (JSON).
     * ?days=7|30|90 — fenêtre glissante d'analyse.
     */
    public function api_data()
    {
        $days = (int)$this->input->get('days');
        if ($days <= 0) $days = 30;
        $days = max(7, min(90, $days));
        $this->json_success($this->Dashboard_model->dashboard_payload($days));
    }

    /** Compat : anciens appels. */
    public function api_filters()
    {
        $this->json_success(array(
            'periods' => [
                ['days' => 7, 'label' => '7 jours'],
                ['days' => 30, 'label' => '30 jours'],
                ['days' => 90, 'label' => '90 jours'],
            ],
        ));
    }
}
