<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model');
    }

    public function index()
    {
        $categories = $this->db->query("
            SELECT pc.*, 
                   (SELECT COUNT(*) FROM produits p WHERE p.categorie_id = pc.id AND p.est_actif = 1) as total_produits
            FROM produit_categories pc
            WHERE pc.est_actif = 1
            ORDER BY pc.ordre ASC
        ")->result_array();

        $produits = $this->db->query("
            SELECT p.*, pc.nom as categorie_nom, pc.slug as categorie_slug
            FROM produits p
            LEFT JOIN produit_categories pc ON p.categorie_id = pc.id
            WHERE p.est_actif = 1
            ORDER BY p.ordre ASC
        ")->result_array();

        $data = [
            'categories' => $categories,
            'produits' => $produits,
            'current_category' => null,
        ];

        $this->load->view('Shop_view', $data);
    }

    public function category($slug)
    {
        $categorie = $this->db->query("
            SELECT * FROM produit_categories WHERE slug = ? AND est_actif = 1
        ", [$slug])->row_array();

        if (!$categorie) {
            show_404();
            return;
        }

        $categories = $this->db->query("
            SELECT pc.*, 
                   (SELECT COUNT(*) FROM produits p WHERE p.categorie_id = pc.id AND p.est_actif = 1) as total_produits
            FROM produit_categories pc
            WHERE pc.est_actif = 1
            ORDER BY pc.ordre ASC
        ")->result_array();

        $produits = $this->db->query("
            SELECT p.*, pc.nom as categorie_nom, pc.slug as categorie_slug
            FROM produits p
            LEFT JOIN produit_categories pc ON p.categorie_id = pc.id
            WHERE p.categorie_id = ? AND p.est_actif = 1
            ORDER BY p.ordre ASC
        ", [$categorie['id']])->result_array();

        $data = [
            'categories' => $categories,
            'produits' => $produits,
            'current_category' => $categorie,
        ];

        $this->load->view('Shop_view', $data);
    }

    public function detail($slug)
    {
        $produit = $this->db->query("
            SELECT p.*, pc.nom as categorie_nom, pc.slug as categorie_slug, pc.description as categorie_description
            FROM produits p
            LEFT JOIN produit_categories pc ON p.categorie_id = pc.id
            WHERE p.slug = ? AND p.est_actif = 1
        ", [$slug])->row_array();

        if (!$produit) {
            show_404();
            return;
        }

        $related = $this->db->query("
            SELECT p.*, pc.nom as categorie_nom
            FROM produits p
            LEFT JOIN produit_categories pc ON p.categorie_id = pc.id
            WHERE p.categorie_id = ? AND p.est_actif = 1 AND p.id != ?
            ORDER BY RAND()
            LIMIT 4
        ", [$produit['categorie_id'], $produit['id']])->result_array();

        $categories = $this->db->query("
            SELECT pc.*, 
                   (SELECT COUNT(*) FROM produits p WHERE p.categorie_id = pc.id AND p.est_actif = 1) as total_produits
            FROM produit_categories pc
            WHERE pc.est_actif = 1
            ORDER BY pc.ordre ASC
        ")->result_array();

        $data = [
            'produit' => $produit,
            'related' => $related,
            'categories' => $categories,
        ];

        $this->load->view('Shop_product_detail', $data);
    }

    public function apiProducts()
    {
        $category = $this->input->get('category');
        $search = $this->input->get('q');

        $where = 'p.est_actif = 1';
        $params = [];

        if (!empty($category) && $category !== 'all') {
            $where .= ' AND pc.slug = ?';
            $params[] = $category;
        }

        if (!empty($search) && strlen($search) >= 2) {
            $like = '%' . $this->db->escape_like_str($search) . '%';
            $where .= ' AND (p.nom LIKE ? OR p.description LIKE ?)';
            $params[] = $like;
            $params[] = $like;
        }

        $produits = $this->db->query("
            SELECT p.*, pc.nom as categorie_nom, pc.slug as categorie_slug
            FROM produits p
            LEFT JOIN produit_categories pc ON p.categorie_id = pc.id
            WHERE $where
            ORDER BY p.ordre ASC
        ", $params)->result_array();

        $html = '';
        foreach ($produits as $p) {
            $img = !empty($p['image']) ? base_url($p['image']) : base_url('assets/backend/images/default-avatar.jpg');
            $html .= '
            <div class="agf-shop-card" onclick="window.location.href=\'' . base_url('shop/detail/' . $p['slug']) . '\'">
                <div class="agf-shop-card-img" style="background-image:url(\'' . $img . '\')">
                    ' . ($p['est_certifie'] ? '<span class="agf-shop-badge"><i class="bi bi-patch-check-fill"></i> Certified</span>' : '') . '
                </div>
                <div class="agf-shop-card-body">
                    <span class="agf-shop-card-cat">' . htmlspecialchars($p['categorie_nom'] ?? '') . '</span>
                    <h3 class="agf-shop-card-title">' . htmlspecialchars($p['nom']) . '</h3>
                    <p class="agf-shop-card-desc">' . htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 80, '...')) . '</p>
                    <div class="agf-shop-card-footer">
                        <span class="agf-shop-card-price">' . htmlspecialchars($p['prix'] ?? 'On request') . '</span>
                        <span class="agf-shop-card-action"><i class="bi bi-arrow-right"></i></span>
                    </div>
                </div>
            </div>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'html' => $html, 'count' => count($produits)]);
        exit;
    }
}
