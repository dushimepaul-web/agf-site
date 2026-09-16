<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produits_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ===================== CATÉGORIES =====================

    public function get_categories($actives_only = false)
    {
        $where = $actives_only ? ['est_actif' => 1] : [];
        return $this->read('produit_categories', $where, 'ordre', 'ASC');
    }

    public function get_categorie($id)
    {
        return $this->read_one('produit_categories', ['id' => $id]);
    }

    public function get_categorie_by_slug($slug)
    {
        return $this->read_one('produit_categories', ['slug' => $slug]);
    }

    public function create_categorie($data)
    {
        return $this->create('produit_categories', $data, true);
    }

    public function update_categorie($id, $data)
    {
        return $this->update('produit_categories', ['id' => $id], $data);
    }

    public function delete_categorie($id)
    {
        $nb = $this->db->where('categorie_id', $id)->count_all_results('produits');
        if ($nb > 0) return false;
        return $this->delete('produit_categories', ['id' => $id]);
    }

    public function count_categories()
    {
        return $this->db->count_all('produit_categories');
    }

    // ===================== PRODUITS =====================

    public function get_produits($categorie_id = null)
    {
        $where = [];
        if ($categorie_id) $where['categorie_id'] = $categorie_id;
        return $this->read('produits', $where, 'ordre', 'ASC');
    }

    public function get_produits_avec_categorie($categorie_id = null)
    {
        $this->db->select('p.*, c.nom AS categorie_nom');
        $this->db->from('produits p');
        $this->db->join('produit_categories c', 'c.id = p.categorie_id', 'left');
        if ($categorie_id) $this->db->where('p.categorie_id', $categorie_id);
        $this->db->order_by('p.ordre', 'ASC');
        $q = $this->db->get();
        return ($q !== false) ? $q->result_array() : [];
    }

    public function get_produit($id)
    {
        $this->db->select('p.*, c.nom AS categorie_nom');
        $this->db->from('produits p');
        $this->db->join('produit_categories c', 'c.id = p.categorie_id', 'left');
        $this->db->where('p.id', $id);
        $q = $this->db->get();
        return ($q !== false) ? $q->row_array() : null;
    }

    public function create_produit($data)
    {
        return $this->create('produits', $data, true);
    }

    public function update_produit($id, $data)
    {
        return $this->update('produits', ['id' => $id], $data);
    }

    public function delete_produit($id)
    {
        return $this->delete('produits', ['id' => $id]);
    }

    public function count_produits($categorie_id = null)
    {
        if ($categorie_id) return $this->db->where('categorie_id', $categorie_id)->count_all_results('produits');
        return $this->db->count_all('produits');
    }

    // ===================== UNITÉS D'AFFAIRES =====================

    public function get_unites($actives_only = false)
    {
        $where = $actives_only ? ['est_actif' => 1] : [];
        return $this->read('unites_affaires', $where, 'ordre', 'ASC');
    }

    public function get_unite($id)
    {
        return $this->read_one('unites_affaires', ['id' => $id]);
    }

    public function get_unite_by_code($code)
    {
        return $this->read_one('unites_affaires', ['code' => $code]);
    }

    public function get_unite_by_slug($slug)
    {
        return $this->read_one('unites_affaires', ['slug' => $slug]);
    }

    public function create_unite($data)
    {
        return $this->create('unites_affaires', $data, true);
    }

    public function update_unite($id, $data)
    {
        return $this->update('unites_affaires', ['id' => $id], $data);
    }

    public function delete_unite($id)
    {
        return $this->delete('unites_affaires', ['id' => $id]);
    }

    public function count_unites()
    {
        return $this->db->count_all('unites_affaires');
    }
}
