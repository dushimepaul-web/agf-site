<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitors extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Journal des visiteurs';
        $this->load->view('visitors/index', $data);
    }

    public function api_list() {
        $this->db->order_by('visit_date', 'DESC')->order_by('visit_time', 'DESC')->limit(500);
        $q = $this->db->get('visitors_logs');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_stats() {
        $today = $this->db->where('visit_date', date('Y-m-d'))->count_all_results('visitors_logs');
        $week = $this->db->where('visit_date >=', date('Y-m-d', strtotime('-7 days')))->count_all_results('visitors_logs');
        $month = $this->db->where('visit_date >=', date('Y-m-d', strtotime('-30 days')))->count_all_results('visitors_logs');
        $this->json_success(['today' => $today, 'week' => $week, 'month' => $month]);
    }
}
