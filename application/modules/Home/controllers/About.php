<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model');
    }

    public function index()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'About A.G.F — African Green Farmers';
        $this->load->view('About_View', $data);
    }

    public function detail($slug = '')
    {
        if (empty($slug)) {
            redirect('about');
        }

        $unit = $this->db->where('slug', $slug)->where('est_actif', 1)->get('unites_affaires')->row_array();

        if (empty($unit)) {
            show_404();
        }

        $this->Model->log_visit();
        $data['unit'] = $unit;
        $data['site_title'] = htmlspecialchars($unit['nom']) . ' — African Green Farmers';
        $this->load->view('Unit_Detail_View', $data);
    }

    public function profil_societe()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Profil de la soci\u00e9t\u00e9 — A.G.F';
        $this->load->view('profil-societe', $data);
    }

    public function strategie_investissement()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Strat\u00e9gie & investissement — A.G.F';
        $this->load->view('strategie-investissement', $data);
    }

    public function impact_strategique()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Impact strat\u00e9gique — A.G.F';
        $this->load->view('impact', $data);
    }

    public function produits_innovation()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Produits & innovation — A.G.F';
        $this->load->view('produits', $data);
    }

    public function commercialisation_financement()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Commercialisation & financement — A.G.F';
        $this->load->view('commercialisation', $data);
    }

    public function projections_financieres()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Projections financi\u00e8res — A.G.F';
        $this->load->view('projections', $data);
    }

    public function risques_viabilite()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Risques & viabilit\u00e9 — A.G.F';
        $this->load->view('risques-viabilite', $data);
    }

    public function mise_en_oeuvre()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Mise en \u0153uvre & approbation — A.G.F';
        $this->load->view('mise-en-oeuvre', $data);
    }

    // Investment
    public function investissement()
    {
        redirect('strategie-investissement');
    }

    public function investment_projection()
    {
        redirect('projections-financieres');
    }

    public function investor_commitment()
    {
        redirect('commercialisation-financement');
    }

    public function strategic_partnerships()
    {
        redirect('about');
    }

    public function broker_commission()
    {
        redirect('about');
    }

    public function broker()
    {
        redirect('about');
    }

    public function investor()
    {
        redirect('about');
    }

    public function investors_form()
    {
        redirect('about');
    }
}
