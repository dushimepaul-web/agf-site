<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parametres_model extends CI_Model
{
    public function __construct() { parent::__construct(); }

    public function get_all()
    {
        $rows = $this->db->where('deleted_at', null)->get('parametres')->result_array();
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['clef']] = $r['valeur'];
        }
        return $settings;
    }

    public function set($key, $value)
    {
        $exist = $this->db->where('clef', $key)->where('deleted_at', null)->get('parametres')->row_array();
        if ($exist) {
            return $this->db->where('clef', $key)->update('parametres', ['valeur' => $value]);
        }
        $data = ['clef' => $key, 'valeur' => $value, 'uuid' => generate_uuid()];
        return $this->db->insert('parametres', $data);
    }
}
