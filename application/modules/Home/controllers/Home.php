<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('text');
        $this->load->helper('string');
    }

    public function index()
    {
        $this->load->model('Model');

        $data['produits'] = $this->db->where('est_actif', 1)->order_by('ordre', 'ASC')->limit(6)->get('produits')->result_array();
        $data['total_produits'] = $this->db->where('est_actif', 1)->count_all_results('produits');
        $data['unites'] = $this->db->where('est_actif', 1)->order_by('ordre', 'ASC')->get('unites_affaires')->result_array();
        $data['partenaires'] = $this->db->where('est_actif', 1)->order_by('created_at', 'ASC')->get('partenaires')->result_array();
        $data['stats_investissement'] = $this->Model->get_setting('investissement_recherche', 'USD 63,209,692');
        $data['stats_superficie'] = $this->Model->get_setting('superficie_plateforme', '97 ha');
        $data['stats_produits'] = $this->Model->get_setting('nombre_produits', '12');
        $data['stats_irr'] = $this->Model->get_setting('irr', '15.78%');
        $data['site_phone'] = $this->Model->get_setting('site_phone', '+260 777 844 844');

        $this->load->view('Home_View', $data);
    }
}
