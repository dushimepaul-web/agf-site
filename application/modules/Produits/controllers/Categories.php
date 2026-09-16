<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
        $this->load->model('Produits_model');
    }

    public function index()
    {
        $data['title'] = 'Catégories de produits';
        $this->load->view('categories/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier la catégorie' : 'Ajouter une catégorie';
        $data['categorie'] = $id ? $this->Produits_model->get_categorie($id) : null;
        if ($id && !$data['categorie']) show_404();
        $this->load->view('categories/add_edit', $data);
    }

    public function api_list()
    {
        $rows = $this->Produits_model->get_categories();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Produits_model->get_categorie($id);
        if (!$r) { $this->json_error('Catégorie non trouvée', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        $nom = trim($data['nom'] ?? '');
        if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }

        $slug = url_title(trim($data['slug'] ?? $nom), '-', true);
        if ($this->Produits_model->get_categorie_by_slug($slug)) {
            $this->json_error('Ce slug existe déjà'); return;
        }

        $insert = [
            'nom'        => $nom,
            'slug'       => $slug,
            'description'=> trim($data['description'] ?? '') !== '' ? trim($data['description']) : null,
            'ordre'      => (int)($data['ordre'] ?? 0),
            'est_actif'  => isset($data['est_actif']) ? (int)$data['est_actif'] : 1
        ];
        $id = $this->Produits_model->create_categorie($insert);
        if ($id) { $this->json_success(['id' => $id], 'Catégorie créée'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Produits_model->get_categorie($id);
        if (!$existing) { $this->json_error('Catégorie non trouvée', 404); return; }

        $update = [];
        if (isset($data['nom'])) {
            $nom = trim($data['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (isset($data['slug'])) {
            $slug = url_title(trim($data['slug']), '-', true);
            $dup = $this->Produits_model->get_categorie_by_slug($slug);
            if ($dup && $dup['id'] != $id) { $this->json_error('Ce slug existe déjà'); return; }
            $update['slug'] = $slug;
        }
        if (array_key_exists('description', $data)) {
            $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null;
        }
        if (array_key_exists('ordre', $data)) {
            $update['ordre'] = (int)$data['ordre'];
        }
        if (array_key_exists('est_actif', $data)) {
            $update['est_actif'] = (int)$data['est_actif'];
        }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Produits_model->update_categorie($id, $update)) {
            $this->json_success(null, 'Catégorie mise à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $existing = $this->Produits_model->get_categorie($id);
        if (!$existing) { $this->json_error('Catégorie non trouvée', 404); return; }

        if (!$this->Produits_model->delete_categorie($id)) {
            $this->json_error('Impossible : des produits sont rattachés à cette catégorie');
            return;
        }
        $this->json_success(null, 'Catégorie supprimée');
    }
}
