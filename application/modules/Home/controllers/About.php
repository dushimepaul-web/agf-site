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

    public function partnerships()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Partnerships \u2014 A.G.F';
        $this->load->view('partnerships', $data);
    }

    public function investment_projection()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Investment Projections \u2014 A.G.F';
        $this->load->view('investment-projection', $data);
    }

    public function investor_commitment()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Investor Commitment \u2014 A.G.F';
        $this->load->view('investor-commitment', $data);
    }

    public function strategic_partnerships()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Strategic Partnerships \u2014 A.G.F';
        $this->load->view('strategic-partnerships', $data);
    }

    public function relations()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Relations \u2014 A.G.F';
        $this->load->view('relations', $data);
    }

    public function broker_commission()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Broker Commission \u2014 A.G.F';
        $this->load->view('broker-commission', $data);
    }

    public function broker()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Become a Broker \u2014 A.G.F';
        $data['pays'] = $this->db->order_by('pays', 'ASC')->get('pays')->result_array();
        $this->load->view('broker', $data);
    }

    public function api_pays_search()
    {
        $this->output->set_content_type('application/json');
        $q = $this->input->get('q', TRUE);
        $q = $this->security->xss_clean(trim($q));

        if (strlen($q) < 1) {
            echo json_encode([]);
            return;
        }

        $results = $this->db
            ->select('id, pays, ISO_3166_1_2_Letter_Code')
            ->like('pays', $q)
            ->limit(15)
            ->get('pays')
            ->result_array();

        echo json_encode($results);
    }

    public function api_detect_country()
    {
        $this->output->set_content_type('application/json');
        $ip = $this->input->ip_address();

        if ($ip === '127.0.0.1' || $ip === '::1') {
            echo json_encode(['id' => '', 'pays' => '']);
            return;
        }

        $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,countryCode");
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);

        if (!$resp) {
            echo json_encode(['id' => '', 'pays' => '']);
            return;
        }

        $geo = json_decode($resp, true);
        if (empty($geo['countryCode'])) {
            echo json_encode(['id' => '', 'pays' => '']);
            return;
        }

        $row = $this->db
            ->where('ISO_3166_1_2_Letter_Code', $geo['countryCode'])
            ->get('pays')
            ->row_array();

        echo json_encode($row ? ['id' => $row['id'], 'pays' => $row['pays']] : ['id' => '', 'pays' => '']);
    }

    public function broker_store()
    {
        $this->output->set_content_type('application/json');

        $required_text = [
            'full_name' => 'Full Name',
            'email' => 'Email',
            'firm_name' => 'Firm Name',
            'id_pays' => 'Country',
            'mobile_phone' => 'Mobile Phone',
            'jurisdiction_of_incorporation' => 'Jurisdiction of Incorporation',
            'registration_number' => 'Registration Number',
            'regulatory_status' => 'Regulatory Status',
            'regulatory_authority' => 'Regulatory Authority',
        ];

        foreach ($required_text as $field => $label) {
            $val = $this->security->xss_clean(trim($this->input->post($field, TRUE)));
            if (empty($val)) {
                echo json_encode(['status' => 'error', 'message' => '"' . $label . '" is required.']);
                return;
            }
        }

        $email = $this->security->xss_clean(trim($this->input->post('email', TRUE)));
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
            return;
        }

        $exists = $this->db->where('email', $email)->get('brokers')->num_rows();
        if ($exists) {
            echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
            return;
        }

        $id_pays = intval($this->input->post('id_pays', TRUE));
        if ($id_pays <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please select a valid country.']);
            return;
        }

        $checkboxes = [
            'capacity_investment_broker', 'capacity_placement_agent', 'capacity_corporate_finance_advisor',
            'capacity_fund_manager', 'capacity_family_office_rep', 'capacity_esg_advisor',
            'capacity_independent_introducer',
            'investor_private_equity', 'investor_venture_capital', 'investor_esg_impact',
            'investor_dfi', 'investor_institutional', 'investor_hnwi', 'investor_sovereign',
            'mandate_equity', 'mandate_structured_debt', 'mandate_blended_finance',
            'mandate_grant', 'mandate_strategic_partnership', 'mandate_full_program',
            'confirm_authorized', 'confirm_aml_kyc', 'acknowledge_no_exclusivity',
            'understand_formal_mandate_required'
        ];

        $capacity_checks = ['capacity_investment_broker','capacity_placement_agent','capacity_corporate_finance_advisor','capacity_fund_manager','capacity_family_office_rep','capacity_esg_advisor','capacity_independent_introducer'];
        $has_capacity = false;
        foreach ($capacity_checks as $cb) {
            if ($this->input->post($cb) === '1') { $has_capacity = true; break; }
        }
        if (!$has_capacity) {
            echo json_encode(['status' => 'error', 'message' => 'Select at least one Broker Capacity.']);
            return;
        }

        $investor_checks = ['investor_private_equity','investor_venture_capital','investor_esg_impact','investor_dfi','investor_institutional','investor_hnwi','investor_sovereign'];
        $has_investor = false;
        foreach ($investor_checks as $cb) {
            if ($this->input->post($cb) === '1') { $has_investor = true; break; }
        }
        if (!$has_investor) {
            echo json_encode(['status' => 'error', 'message' => 'Select at least one Investor Type.']);
            return;
        }

        $confirm_checks = ['confirm_authorized','confirm_aml_kyc','acknowledge_no_exclusivity','understand_formal_mandate_required'];
        foreach ($confirm_checks as $cb) {
            if ($this->input->post($cb) !== '1') {
                echo json_encode(['status' => 'error', 'message' => 'All confirmations are required.']);
                return;
            }
        }

        $text_fields = [
            'full_name', 'firm_name', 'jurisdiction_of_incorporation', 'registration_number',
            'regulatory_status', 'regulatory_authority', 'mobile_phone', 'whatsapp',
            'corporate_website', 'capacity_other', 'typical_ticket_size', 'geographic_coverage',
            'engagement_model'
        ];

        $data = ['email' => $email, 'id_pays' => $id_pays];

        foreach ($text_fields as $f) {
            $data[$f] = $this->security->xss_clean(strip_tags(trim($this->input->post($f, TRUE))));
        }

        foreach ($checkboxes as $cb) {
            $data[$cb] = ($this->input->post($cb) === '1') ? 1 : 0;
        }

        if (!$this->db->insert('brokers', $data)) {
            echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
            return;
        }

        echo json_encode(['status' => 'success', 'message' => 'Registration submitted successfully! We will contact you soon.']);
    }

    public function investor()
    {
        $this->Model->log_visit();
        $data['site_title'] = 'Become an Investor \u2014 A.G.F';
        $data['pays'] = $this->db->order_by('pays', 'ASC')->get('pays')->result_array();
        $this->load->view('investor', $data);
    }

    public function investor_store()
    {
        $this->output->set_content_type('application/json');

        $required_text = [
            'full_name' => 'Full Name',
            'email' => 'Email',
            'id_pays' => 'Country',
            'organization' => 'Organization',
            'position_title' => 'Position / Title',
            'phone' => 'Phone',
            'commitment_range' => 'Commitment Range',
        ];

        foreach ($required_text as $field => $label) {
            $val = $this->security->xss_clean(trim($this->input->post($field, TRUE)));
            if (empty($val)) {
                echo json_encode(['status' => 'error', 'message' => '"' . $label . '" is required.']);
                return;
            }
        }

        $email = $this->security->xss_clean(trim($this->input->post('email', TRUE)));
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
            return;
        }

        $exists = $this->db->where('email', $email)->get('investors')->num_rows();
        if ($exists) {
            echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
            return;
        }

        $id_pays = intval($this->input->post('id_pays', TRUE));
        if ($id_pays <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please select a valid country.']);
            return;
        }

        $interest_checks = ['interest_equity','interest_debt','interest_blended_finance','interest_grant','interest_strategic_partnership','interest_technical_collaboration','interest_offtake_distribution'];
        $has_interest = false;
        foreach ($interest_checks as $cb) {
            if ($this->input->post($cb) === '1') { $has_interest = true; break; }
        }
        if (!$has_interest) {
            echo json_encode(['status' => 'error', 'message' => 'Select at least one Investment Interest.']);
            return;
        }

        $focus_checks = ['focus_research_lab','focus_gmp_facility','focus_medicinal_plant','focus_commercialization','focus_full_platform'];
        $has_focus = false;
        foreach ($focus_checks as $cb) {
            if ($this->input->post($cb) === '1') { $has_focus = true; break; }
        }
        if (!$has_focus) {
            echo json_encode(['status' => 'error', 'message' => 'Select at least one Focus Area.']);
            return;
        }

        $confirm_checks = ['agree_contact','non_binding_confirmation'];
        foreach ($confirm_checks as $cb) {
            if ($this->input->post($cb) !== '1') {
                echo json_encode(['status' => 'error', 'message' => 'Both confirmations are required.']);
                return;
            }
        }

        $checkboxes = [
            'interest_equity','interest_debt','interest_blended_finance','interest_grant',
            'interest_strategic_partnership','interest_technical_collaboration','interest_offtake_distribution',
            'focus_research_lab','focus_gmp_facility','focus_medicinal_plant','focus_commercialization','focus_full_platform',
            'agree_contact','non_binding_confirmation'
        ];

        $text_fields = ['full_name','organization','position_title','phone','interest_other','strategic_message'];

        $data = [
            'email' => $email,
            'id_pays' => $id_pays,
            'commitment_range' => $this->security->xss_clean(trim($this->input->post('commitment_range', TRUE))),
            'timeline' => $this->security->xss_clean(trim($this->input->post('timeline', TRUE))),
        ];

        foreach ($text_fields as $f) {
            $data[$f] = $this->security->xss_clean(strip_tags(trim($this->input->post($f, TRUE))));
        }

        foreach ($checkboxes as $cb) {
            $data[$cb] = ($this->input->post($cb) === '1') ? 1 : 0;
        }

        if (!$this->db->insert('investors', $data)) {
            echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
            return;
        }

        echo json_encode(['status' => 'success', 'message' => 'Registration submitted successfully! We will contact you soon.']);
    }

    public function investors_form()
    {
        redirect('investor');
    }
}
