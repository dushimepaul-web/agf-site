<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brokers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
        $this->load->model('Finance/Finance_model');
    }

    public function index()
    {
        $data['title'] = 'Intermédiaires financiers';
        $this->load->view('brokers/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier l\'intermédiaire' : 'Ajouter un intermédiaire';
        $data['broker'] = $id ? $this->Finance_model->get_broker($id) : null;
        if ($id && !$data['broker']) show_404();
        $this->load->view('brokers/add_edit', $data);
    }

    public function api_list()
    {
        $rows = $this->Finance_model->get_brokers();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Finance_model->get_broker($id);
        if (!$r) { $this->json_error('Intermédiaire non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        if (empty($data['full_name']) || empty($data['firm_name']) || empty($data['email'])) {
            $this->json_error('Nom, firme et email obligatoires'); return;
        }
        $insert = [
            'full_name' => trim($data['full_name']),
            'firm_name' => trim($data['firm_name']),
            'email' => trim($data['email']),
            'jurisdiction_of_incorporation' => trim($data['jurisdiction_of_incorporation'] ?? ''),
            'registration_number' => trim($data['registration_number'] ?? ''),
            'regulatory_status' => $data['regulatory_status'] ?? null,
            'regulatory_authority' => trim($data['regulatory_authority'] ?? ''),
            'id_pays' => (int)($data['id_pays'] ?? 0),
            'mobile_phone' => trim($data['mobile_phone'] ?? ''),
            'whatsapp' => trim($data['whatsapp'] ?? ''),
            'corporate_website' => trim($data['corporate_website'] ?? ''),
            'typical_ticket_size' => trim($data['typical_ticket_size'] ?? ''),
            'geographic_coverage' => trim($data['geographic_coverage'] ?? ''),
            'capacity_investment_broker' => (int)($data['capacity_investment_broker'] ?? 0),
            'capacity_placement_agent' => (int)($data['capacity_placement_agent'] ?? 0),
            'capacity_corporate_finance_advisor' => (int)($data['capacity_corporate_finance_advisor'] ?? 0),
            'capacity_fund_manager' => (int)($data['capacity_fund_manager'] ?? 0),
            'capacity_family_office_rep' => (int)($data['capacity_family_office_rep'] ?? 0),
            'capacity_esg_advisor' => (int)($data['capacity_esg_advisor'] ?? 0),
            'capacity_independent_introducer' => (int)($data['capacity_independent_introducer'] ?? 0),
            'capacity_other' => trim($data['capacity_other'] ?? ''),
            'investor_private_equity' => (int)($data['investor_private_equity'] ?? 0),
            'investor_venture_capital' => (int)($data['investor_venture_capital'] ?? 0),
            'investor_esg_impact' => (int)($data['investor_esg_impact'] ?? 0),
            'investor_dfi' => (int)($data['investor_dfi'] ?? 0),
            'investor_institutional' => (int)($data['investor_institutional'] ?? 0),
            'investor_hnwi' => (int)($data['investor_hnwi'] ?? 0),
            'investor_sovereign' => (int)($data['investor_sovereign'] ?? 0),
            'engagement_model' => $data['engagement_model'] ?? null,
            'confirm_authorized' => (int)($data['confirm_authorized'] ?? 0),
            'confirm_aml_kyc' => (int)($data['confirm_aml_kyc'] ?? 0),
            'acknowledge_no_exclusivity' => (int)($data['acknowledge_no_exclusivity'] ?? 0),
            'understand_formal_mandate_required' => (int)($data['understand_formal_mandate_required'] ?? 0)
        ];
        $id = $this->Finance_model->create_broker($insert);
        if ($id) { $this->json_success(['id' => $id], 'Intermédiaire créé'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Finance_model->get_broker($id);
        if (!$existing) { $this->json_error('Intermédiaire non trouvé', 404); return; }
        $update = [];
        foreach (['full_name','firm_name','email','jurisdiction_of_incorporation','registration_number','regulatory_status','regulatory_authority','mobile_phone','whatsapp','corporate_website','typical_ticket_size','geographic_coverage','capacity_other','engagement_model'] as $f) {
            if (array_key_exists($f, $data)) $update[$f] = trim($data[$f]);
        }
        foreach (['id_pays','capacity_investment_broker','capacity_placement_agent','capacity_corporate_finance_advisor','capacity_fund_manager','capacity_family_office_rep','capacity_esg_advisor','capacity_independent_introducer','investor_private_equity','investor_venture_capital','investor_esg_impact','investor_dfi','investor_institutional','investor_hnwi','investor_sovereign','confirm_authorized','confirm_aml_kyc','acknowledge_no_exclusivity','understand_formal_mandate_required'] as $f) {
            if (array_key_exists($f, $data)) $update[$f] = (int)$data[$f];
        }
        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Finance_model->update_broker($id, $update)) {
            $this->json_success(null, 'Intermédiaire mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Finance_model->delete_broker($id);
        $this->json_success(null, 'Intermédiaire supprimé');
    }
}
