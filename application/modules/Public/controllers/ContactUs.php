<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContactUs extends MY_Controller
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
        $data['title'] = 'Messages de contact';
        $this->load->view('contact_us/list', $data);
    }

    public function api_list()
    {
        $limit = (int)$this->input->get('limit', true) ?: 50;
        $offset = (int)$this->input->get('offset', true) ?: 0;
        $total = $this->Public_model->count_contacts();
        $rows = $this->Public_model->get_contacts($limit, $offset);
        $this->json_success(['total' => $total, 'data' => $rows]);
    }

    public function api_get($id)
    {
        $r = $this->Public_model->get_contact($id);
        if (!$r) { $this->json_error('Message non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_read($id)
    {
        $this->Public_model->mark_contact_read($id);
        $this->json_success(null, 'Marqué comme lu');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Public_model->delete_contact($id);
        $this->json_success(null, 'Message supprimé');
    }
}
