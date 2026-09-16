<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
        $this->load->model('Public/Public_model');
    }

    public function index()
    {
        $data['title'] = 'Questions fréquentes';
        $this->load->view('faq/list', $data);
    }

    public function api_list()
    {
        $rows = $this->Public_model->get_faqs();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Public_model->get_faq($id);
        if (!$r) { $this->json_error('FAQ non trouvée', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        if (empty($data['question']) || empty($data['reponse'])) {
            $this->json_error('Question et réponse obligatoires'); return;
        }
        $insert = [
            'question' => trim($data['question']),
            'reponse' => trim($data['reponse']),
            'categorie' => trim($data['categorie'] ?? 'general'),
            'ordre' => (int)($data['ordre'] ?? 0),
            'est_publiee' => isset($data['est_publiee']) ? (int)$data['est_publiee'] : 1
        ];
        $id = $this->Public_model->create_faq($insert);
        if ($id) { $this->json_success(['id' => $id], 'FAQ créée'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Public_model->get_faq($id);
        if (!$existing) { $this->json_error('FAQ non trouvée', 404); return; }

        $update = [];
        if (isset($data['question'])) $update['question'] = trim($data['question']);
        if (isset($data['reponse'])) $update['reponse'] = trim($data['reponse']);
        if (isset($data['categorie'])) $update['categorie'] = trim($data['categorie']);
        if (array_key_exists('ordre', $data)) $update['ordre'] = (int)$data['ordre'];
        if (array_key_exists('est_publiee', $data)) $update['est_publiee'] = (int)$data['est_publiee'];

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Public_model->update_faq($id, $update)) {
            $this->json_success(null, 'FAQ mise à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Public_model->delete_faq($id);
        $this->json_success(null, 'FAQ supprimée');
    }
}
