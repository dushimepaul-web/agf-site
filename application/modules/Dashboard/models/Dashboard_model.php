<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_stats()
    {
        return $this->get_statistics();
    }
}
