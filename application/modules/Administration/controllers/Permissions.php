<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        redirect(base_url('Roles'));
    }
}
