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
            'title'     => 'Contact - A.G.F',
            'company'   => $this->Model->get_setting('site_name', 'African Green Farmers Limited'),
            'facility'  => $this->Model->get_setting('facility_projet', ''),
            'site_address'       => $this->Model->get_setting('adresse_siege', 'Plot No. 20, Chisoko Village, Along Mungule Road, Keembe Area, Liteta Chiefdom, Keembe Ward, Chibombo District, Central Province, Zambia'),
            'ceo'                 => $this->Model->get_setting('directeur_general', ''),
            'site_email'          => $this->Model->get_setting('site_email', 'agfcompany2026@gmail.com'),
            'site_phone'          => $this->Model->get_setting('site_phone', '+260 777 844 844'),
            'site_phone_alt'      => $this->Model->get_setting('site_phone_alt', '+260 764 346 468'),
            'licence_investissement' => $this->Model->get_setting('licence_investissement', 'ZDA/59004/10/2025'),
            'horaires'            => $this->Model->get_setting('horaires_travail', ''),
        ];

        $banque = [];
        foreach ([
            'bank_name'           => 'Bank',
            'bank_account_name'   => 'Corporate Account Name',
            'bank_account_number' => 'Corporate Account Number',
            'bank_branch'         => 'Branch',
            'bank_branch_address' => 'Branch Address',
            'bank_swift'          => 'Bank SWIFT/BIC',
            'bank_sort_code'      => 'Bank Sort Code',
            'bank_rm_name'        => 'Relationship Manager',
            'bank_rm_phone'       => 'Relationship Manager Telephone',
            'bank_rm_email'       => 'Relationship Manager Email',
            'bank_hq_phone'       => 'Bank Head Office / Corporate Banking Telephone',
            'bank_corporate_email'=> 'Bank Corporate Banking Email',
            'bank_correspondent'  => 'Other Bank Coordinates / Correspondent Banking Details',
        ] as $clef => $label) {
            $v = trim((string) $this->Model->get_setting($clef, ''));
            if ($v !== '') {
                $banque[$label] = $v;
            }
        }
        $data['banque'] = $banque;

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
            echo json_encode(['ok' => true, 'message' => 'Your message has been sent successfully! We will reply shortly.']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'An error occurred while sending. Please try again.']);
        }
        exit;
    }

    public function newsletter()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $email = trim((string) $this->input->post('email', TRUE));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
            echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
            exit;
        }

        $existant = $this->db->where('LOWER(email)', strtolower($email), false)
            ->where('deleted_at IS NULL', null, false)
            ->get('newsletter_abonnes')
            ->row();

        if ($existant) {
            echo json_encode(['ok' => true, 'message' => 'You are already subscribed. Thank you!']);
            exit;
        }

        $insert = $this->Model->create('newsletter_abonnes', [
            'email'      => $email,
            'source'     => 'footer',
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->server('HTTP_USER_AGENT'),
        ]);

        if ($insert) {
            echo json_encode(['ok' => true, 'message' => 'Thank you! You are now subscribed to the A.G.F newsletter.']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'An error occurred. Please try again.']);
        }
        exit;
    }
}
