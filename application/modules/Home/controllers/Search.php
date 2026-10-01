<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model');
    }

    public function ajax_search()
    {
        header('Content-Type: application/json; charset=utf-8');

        $q = trim($this->input->get('q', TRUE));
        if (strlen($q) < 1) {
            echo json_encode(['produits' => [], 'actualites' => [], 'pages' => []]);
            return;
        }

        $results = ['produits' => [], 'actualites' => [], 'pages' => []];

        // === PRODUITS ===
        $this->db->group_start();
        $this->db->like('nom', $q, 'both');
        $this->db->or_like('description', $q, 'both');
        $this->db->group_end();
        $this->db->where('est_actif', 1);
        $this->db->limit(6);
        $produits = $this->db->get('produits')->result_array();
        foreach ($produits as &$p) {
            $p['titre'] = $p['nom'];
            $p['slug']  = $p['slug'] ?: $p['id'];
            $p['extrait'] = mb_strimwidth(strip_tags($p['description'] ?? ''), 0, 80, '...');
        }
        $results['produits'] = $produits;

        // === TOUTES LES PAGES / MENUS / SOUS-MENUS ===
        $all_pages = [
            // Home
            ['title' => 'Accueil', 'url' => '/', 'icon' => 'bi-house-door', 'group' => 'Menu'],

            // About
            ['title' => 'Profil Société', 'url' => '/profil-societe', 'icon' => 'bi-building', 'group' => 'About'],
            ['title' => 'Stratégie & Investissement', 'url' => '/strategie-investissement', 'icon' => 'bi-graph-up', 'group' => 'About'],
            ['title' => 'Impact Stratégique', 'url' => '/impact-strategique', 'icon' => 'bi-lightning', 'group' => 'About'],
            ['title' => 'Vision & Mission', 'url' => '/vision-mission', 'icon' => 'bi-eye', 'group' => 'About'],
            ['title' => 'Produits & Innovation', 'url' => '/produits-innovation', 'icon' => 'bi-leaf', 'group' => 'About'],
            ['title' => 'Commercialisation & Financement', 'url' => '/commercialisation-financement', 'icon' => 'bi-cash-stack', 'group' => 'About'],
            ['title' => 'Projections Financières', 'url' => '/projections-financieres', 'icon' => 'bi-bar-chart-line', 'group' => 'About'],
            ['title' => 'Risques & Viabilité', 'url' => '/risques-viabilite', 'icon' => 'bi-shield-check', 'group' => 'About'],
            ['title' => 'Mise en Œuvre', 'url' => '/mise-en-oeuvre', 'icon' => 'bi-gear', 'group' => 'About'],
            ['title' => 'Gouvernance', 'url' => '/corporate-structure-governance', 'icon' => 'bi-bank', 'group' => 'About'],
            ['title' => 'ESG & Développement Durable', 'url' => '/esg_Sustainability', 'icon' => 'bi-recycle', 'group' => 'About'],

            // Shop
            ['title' => 'Boutique (Shop)', 'url' => '/shop', 'icon' => 'bi-box-seam', 'group' => 'Shop'],

            // Teleconsultation
['title' => 'Advisory Service', 'url' => '/doctor', 'icon' => 'bi-camera-video', 'group' => 'Advisory'],
        ['title' => 'Choose an Expert', 'url' => '/consultation', 'icon' => 'bi-person-badge', 'group' => 'Advisory'],

            // Investment
            ['title' => 'Projections d\'Investissement', 'url' => '/investment-projection', 'icon' => 'bi-bar-chart', 'group' => 'Investment'],
            ['title' => 'Engagement Investisseur', 'url' => '/investor-commitment', 'icon' => 'bi-handshake', 'group' => 'Investment'],
            ['title' => 'Partenariats Stratégiques', 'url' => '/strategic-partnerships', 'icon' => 'bi-people', 'group' => 'Investment'],
            ['title' => 'Transparence & Financement', 'url' => '/relations', 'icon' => 'bi-bank', 'group' => 'Investment'],
            ['title' => 'Commission Courtier', 'url' => '/broker-commission', 'icon' => 'bi-cash-coin', 'group' => 'Investment'],
            ['title' => 'Devenir Courtier', 'url' => '/broker', 'icon' => 'bi-person-plus', 'group' => 'Investment'],
            ['title' => 'Devenir Investisseur', 'url' => '/investor', 'icon' => 'bi-person-check', 'group' => 'Investment'],

            // Media
            ['title' => 'Média', 'url' => '/media', 'icon' => 'bi-collection-play', 'group' => 'Media'],
            ['title' => 'Tendances', 'url' => '/media/trending', 'icon' => 'bi-fire', 'group' => 'Media'],
            ['title' => 'Actualités', 'url' => '/media/news', 'icon' => 'bi-newspaper', 'group' => 'Media'],
            ['title' => 'Témoignages', 'url' => '/media/temoignages', 'icon' => 'bi-chat-quote', 'group' => 'Media'],

            // Contact
            ['title' => 'Contact', 'url' => '/contact', 'icon' => 'bi-envelope', 'group' => 'Contact'],
        ];

        $q_lower = strtolower($q);
        foreach ($all_pages as $p) {
            $title_lower = strtolower($p['title']);
            if (strpos($title_lower, $q_lower) !== false || levenshtein($q_lower, $title_lower) <= 3) {
                $results['pages'][] = [
                    'titre'      => $p['title'],
                    'url'        => $p['url'],
                    'description' => $p['group'],
                    'icon'       => $p['icon'],
                ];
            }
        }

        echo json_encode($results);
    }
}
