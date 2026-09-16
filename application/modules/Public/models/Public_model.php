<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Public_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // CONTACT US
    public function get_contacts($limit = 50, $offset = 0)
    {
        $this->db->order_by('Date_creation', 'DESC');
        $this->db->limit($limit, $offset);
        $q = $this->db->get('contact_us');
        return ($q !== false) ? $q->result_array() : [];
    }

    public function count_contacts()
    {
        return $this->db->count_all('contact_us');
    }

    public function get_contact($id)
    {
        return $this->read_one('contact_us', ['IdContact' => $id]);
    }

    public function mark_contact_read($id)
    {
        return $this->update('contact_us', ['IdContact' => $id], ['is_readed' => 1]);
    }

    public function delete_contact($id)
    {
        $this->db->where('IdContact', $id);
        return $this->db->delete('contact_us');
    }

    // FAQ
    public function get_faqs($published_only = false)
    {
        $where = $published_only ? ['est_publiee' => 1] : [];
        return $this->read('faq', $where, 'ordre', 'ASC');
    }

    public function get_faq($id)
    {
        return $this->read_one('faq', ['id_faq' => $id]);
    }

    public function create_faq($data)
    {
        return $this->create('faq', $data, true);
    }

    public function update_faq($id, $data)
    {
        return $this->update('faq', ['id_faq' => $id], $data);
    }

    public function delete_faq($id)
    {
        $this->db->where('id_faq', $id);
        return $this->db->delete('faq');
    }

    // PARTENAIRES
    public function get_partenaires($actifs_only = false)
    {
        $where = $actifs_only ? ['est_actif' => 1] : [];
        return $this->read('partenaires', $where, 'created_at', 'DESC');
    }

    public function get_partenaire($id)
    {
        return $this->read_one('partenaires', ['id_partenaire' => $id]);
    }

    public function create_partenaire($data)
    {
        return $this->create('partenaires', $data, true);
    }

    public function update_partenaire($id, $data)
    {
        return $this->update('partenaires', ['id_partenaire' => $id], $data);
    }

    public function delete_partenaire($id)
    {
        return $this->delete('partenaires', ['id_partenaire' => $id]);
    }

    // SOCIAL LINKS
    public function get_social_links($actifs_only = false)
    {
        $where = $actifs_only ? ['is_active' => 1] : [];
        return $this->read('social_links', $where, 'display_order', 'ASC');
    }

    public function get_social_link($id)
    {
        return $this->read_one('social_links', ['id' => $id]);
    }

    public function create_social_link($data)
    {
        return $this->create('social_links', $data, true);
    }

    public function update_social_link($id, $data)
    {
        return $this->update('social_links', ['id' => $id], $data);
    }

    public function delete_social_link($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('social_links');
    }
}
