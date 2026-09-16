<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // BROKERS
    public function get_brokers()
    {
        return $this->read('brokers', [], 'created_at', 'DESC');
    }

    public function get_broker($id)
    {
        return $this->read_one('brokers', ['id' => $id]);
    }

    public function create_broker($data)
    {
        return $this->create('brokers', $data, true);
    }

    public function update_broker($id, $data)
    {
        return $this->update('brokers', ['id' => $id], $data);
    }

    public function delete_broker($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('brokers');
    }

    public function count_brokers()
    {
        return $this->db->count_all('brokers');
    }

    // INVESTORS
    public function get_investors()
    {
        return $this->read('investors', [], 'created_at', 'DESC');
    }

    public function get_investor($id)
    {
        return $this->read_one('investors', ['id' => $id]);
    }

    public function create_investor($data)
    {
        return $this->create('investors', $data, true);
    }

    public function update_investor($id, $data)
    {
        return $this->update('investors', ['id' => $id], $data);
    }

    public function delete_investor($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('investors');
    }

    public function count_investors()
    {
        return $this->db->count_all('investors');
    }
}
