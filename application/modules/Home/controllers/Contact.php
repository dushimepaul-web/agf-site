<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    public function index()
    {
        $data = [
            'title' => 'Contact - A.G.F',
            'site_name'    => $this->Model->get_setting('site_name', 'African Green Farmers'),
            'site_email'   => $this->Model->get_setting('contact_email', 'agfcompany2026@gmail.com'),
            'site_phone'   => $this->Model->get_setting('whatsapp_number', '+260 777 844 844'),
            'site_address' => $this->Model->get_setting('contact_address', 'Lusaka, Zambie'),
        ];

        $this->load->view('Contact_View', $data);
    }

    public function send()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $this->form_validation->set_rules('name', 'Nom', 'required|trim|min_length[3]|max_length[250]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[250]');
        $this->form_validation->set_rules('subject', 'Sujet', 'required|trim|max_length[250]');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[10]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['ok' => false, 'message' => strip_tags(validation_errors(' '))]);
            exit;
        }

        $data = [
            'FullName'    => $this->input->post('name', TRUE),
            'Email'       => $this->input->post('email', TRUE),
            'PhoneNumber' => $this->input->post('phone', TRUE) ?: '0000000000',
            'Subject'     => $this->input->post('subject', TRUE),
            'Message'     => $this->input->post('message', TRUE),
            'Location'    => 'Site Web',
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => $this->input->server('HTTP_USER_AGENT'),
        ];

        $insert = $this->Model->create('contact_us', $data);

        if ($insert) {
            echo json_encode(['ok' => true, 'message' => 'Votre message a \u00e9t\u00e9 envoy\u00e9 avec succ\u00e8s ! Nous vous r\u00e9pondrons bient\u00f4t.']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'Erreur lors de l\u2019envoi. R\u00e9essayez.']);
        }
        exit;
    }
}
