<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_logs($limit = 100, $offset = 0, $filters = [])
    {
        if (!empty($filters['user_id'])) $this->db->where('user_id', $filters['user_id']);
        if (!empty($filters['action'])) $this->db->like('action', $filters['action']);
        if (!empty($filters['niveau'])) $this->db->where('niveau', $filters['niveau']);
        if (!empty($filters['date_from'])) $this->db->where('created_at >=', $filters['date_from']);
        if (!empty($filters['date_to'])) $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $q = $this->db->get('logs');
        return ($q !== false) ? $q->result_array() : [];
    }

    public function count_logs($filters = [])
    {
        if (!empty($filters['user_id'])) $this->db->where('user_id', $filters['user_id']);
        if (!empty($filters['action'])) $this->db->like('action', $filters['action']);
        if (!empty($filters['niveau'])) $this->db->where('niveau', $filters['niveau']);
        if (!empty($filters['date_from'])) $this->db->where('created_at >=', $filters['date_from']);
        if (!empty($filters['date_to'])) $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        return $this->db->count_all_results('logs');
    }

    public function get_log($id)
    {
        return $this->read_one('logs', ['id' => $id]);
    }

    public function clear_logs()
    {
        $this->db->truncate('logs');
        return $this->db->affected_rows();
    }
}
