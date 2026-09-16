<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->not_logged_in();
        $data['title'] = 'Tableau de bord';
        $data['stats'] = $this->_get_statistics();
        $this->render_view('Dashboard_View', $data);
    }

    private function _get_statistics()
    {
        $stats = array();
        $stats['total_utilisateurs'] = $this->db->count_all('users');
        $stats['total_roles']        = $this->db->count_all('roles');
        $stats['total_menus']        = $this->db->count_all('menus');
        $stats['total_logs']         = $this->db->count_all('logs');

        $stats['users_actifs'] = $this->db->where('actif', 1)->count_all_results('users');
        $stats['sessions_actives'] = $this->db->where('is_active', 1)->count_all_results('user_sessions');

        $derniers_users = $this->db->select('nom, prenom, email, derniere_connexion')
            ->order_by('id', 'DESC')->limit(5)->get('users')->result_array();
        $stats['derniers_utilisateurs'] = $derniers_users;

        return $stats;
    }

    public function api_data()
    {
        $this->not_logged_in();
        $this->json_success($this->_get_statistics());
    }

    public function api_filters()
    {
        $this->json_success(array());
    }
}
