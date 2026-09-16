<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Investors extends MY_Controller
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
        $data['title'] = 'Investisseurs';
        $this->load->view('investors/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier l\'investisseur' : 'Ajouter un investisseur';
        $data['investor'] = $id ? $this->Finance_model->get_investor($id) : null;
        if ($id && !$data['investor']) show_404();
        $this->load->view('investors/add_edit', $data);
    }

    public function api_list()
    {
        $rows = $this->Finance_model->get_investors();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Finance_model->get_investor($id);
        if (!$r) { $this->json_error('Investisseur non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        if (empty($data['full_name']) || empty($data['email'])) {
            $this->json_error('Nom et email obligatoires'); return;
        }
        $insert = [
            'full_name' => trim($data['full_name']),
            'organization' => trim($data['organization'] ?? ''),
            'position_title' => trim($data['position_title'] ?? ''),
            'id_pays' => (int)($data['id_pays'] ?? 0),
            'email' => trim($data['email']),
            'phone' => trim($data['phone'] ?? ''),
            'commitment_range' => $data['commitment_range'] ?? null,
            'timeline' => $data['timeline'] ?? 'Exploratory',
            'strategic_message' => trim($data['strategic_message'] ?? ''),
            'interest_other' => trim($data['interest_other'] ?? ''),
            'agree_contact' => (int)($data['agree_contact'] ?? 0),
            'non_binding_confirmation' => (int)($data['non_binding_confirmation'] ?? 0),
            'interest_equity' => (int)($data['interest_equity'] ?? 0),
            'interest_debt' => (int)($data['interest_debt'] ?? 0),
            'interest_blended_finance' => (int)($data['interest_blended_finance'] ?? 0),
            'interest_grant' => (int)($data['interest_grant'] ?? 0),
            'interest_strategic_partnership' => (int)($data['interest_strategic_partnership'] ?? 0),
            'interest_technical_collaboration' => (int)($data['interest_technical_collaboration'] ?? 0),
            'interest_offtake_distribution' => (int)($data['interest_offtake_distribution'] ?? 0),
            'focus_research_lab' => (int)($data['focus_research_lab'] ?? 0),
            'focus_gmp_facility' => (int)($data['focus_gmp_facility'] ?? 0),
            'focus_medicinal_plant' => (int)($data['focus_medicinal_plant'] ?? 0),
            'focus_commercialization' => (int)($data['focus_commercialization'] ?? 0),
            'focus_full_platform' => (int)($data['focus_full_platform'] ?? 0)
        ];
        $id = $this->Finance_model->create_investor($insert);
        if ($id) { $this->json_success(['id' => $id], 'Investisseur créé'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Finance_model->get_investor($id);
        if (!$existing) { $this->json_error('Investisseur non trouvé', 404); return; }
        $update = [];
        foreach (['full_name','organization','position_title','email','phone','commitment_range','timeline','strategic_message','interest_other'] as $f) {
            if (array_key_exists($f, $data)) $update[$f] = trim($data[$f]);
        }
        foreach (['id_pays','agree_contact','non_binding_confirmation','interest_equity','interest_debt','interest_blended_finance','interest_grant','interest_strategic_partnership','interest_technical_collaboration','interest_offtake_distribution','focus_research_lab','focus_gmp_facility','focus_medicinal_plant','focus_commercialization','focus_full_platform'] as $f) {
            if (array_key_exists($f, $data)) $update[$f] = (int)$data[$f];
        }
        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Finance_model->update_investor($id, $update)) {
            $this->json_success(null, 'Investisseur mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Finance_model->delete_investor($id);
        $this->json_success(null, 'Investisseur supprimé');
    }
}
