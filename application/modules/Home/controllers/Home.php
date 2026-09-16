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
        $data['unites'] = $this->db->where('est_actif', 1)->order_by('ordre', 'ASC')->get('unites_affaires')->result_array();
        $data['partenaires'] = $this->db->where('est_actif', 1)->order_by('created_at', 'ASC')->get('partenaires')->result_array();
        $data['stats_investissement'] = $this->Model->get_setting('investissement_recherche', 'USD 63.2M');
        $data['stats_superficie'] = $this->Model->get_setting('superficie_plateforme', '2,000+ ha');
        $data['stats_produits'] = $this->Model->get_setting('nombre_produits', '11');
        $data['stats_irr'] = $this->Model->get_setting('irr', '15.78%');

        $this->load->view('Home_View', $data);
    }
}
