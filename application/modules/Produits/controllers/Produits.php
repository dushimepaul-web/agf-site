<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produits extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
    }

    public function index()
    {
        $data['title'] = 'Gestion des produits';
        $data['categories'] = $this->Produits_model->get_categories(true);
        $this->load->view('produits/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier le produit' : 'Ajouter un produit';
        $data['produit'] = $id ? $this->Produits_model->get_produit($id) : null;
        $data['categories'] = $this->Produits_model->get_categories(true);
        if ($id && !$data['produit']) show_404();
        $this->load->view('produits/add_edit', $data);
    }

    public function api_list()
    {
        $categorie_id = $this->input->get('categorie_id', true);
        $rows = $this->Produits_model->get_produits_avec_categorie($categorie_id ?: null);
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Produits_model->get_produit($id);
        if (!$r) { $this->json_error('Produit non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        $nom = trim($data['nom'] ?? '');
        $categorie_id = (int)($data['categorie_id'] ?? 0);
        if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
        if ($categorie_id <= 0) { $this->json_error('La catégorie est obligatoire'); return; }

        $cat = $this->Produits_model->get_categorie($categorie_id);
        if (!$cat) { $this->json_error('Catégorie invalide'); return; }

        $slug = url_title(trim($data['slug'] ?? $nom), '-', true);

        $insert = [
            'nom'            => $nom,
            'categorie_id'   => $categorie_id,
            'slug'           => $slug,
            'description'    => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null,
            'image'          => trim($data['image'] ?? '') !== '' ? trim($data['image']) : null,
            'conditionnement'=> trim($data['conditionnement'] ?? '') !== '' ? trim($data['conditionnement']) : null,
            'est_certifie'   => isset($data['est_certifie']) ? (int)$data['est_certifie'] : 0,
            'est_actif'      => isset($data['est_actif']) ? (int)$data['est_actif'] : 1,
            'ordre'          => (int)($data['ordre'] ?? 0)
        ];
        $id = $this->Produits_model->create_produit($insert);
        if ($id) { $this->json_success(['id' => $id], 'Produit créé'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Produits_model->get_produit($id);
        if (!$existing) { $this->json_error('Produit non trouvé', 404); return; }

        $update = [];
        if (isset($data['nom'])) {
            $nom = trim($data['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (isset($data['categorie_id'])) {
            $cat = $this->Produits_model->get_categorie((int)$data['categorie_id']);
            if (!$cat) { $this->json_error('Catégorie invalide'); return; }
            $update['categorie_id'] = (int)$data['categorie_id'];
        }
        if (isset($data['slug'])) {
            $slug = url_title(trim($data['slug']), '-', true);
            $update['slug'] = $slug;
        }
        if (array_key_exists('description', $data)) {
            $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null;
        }
        if (array_key_exists('image', $data)) {
            $update['image'] = trim($data['image'] ?? '') !== '' ? trim($data['image']) : null;
        }
        if (array_key_exists('conditionnement', $data)) {
            $update['conditionnement'] = trim($data['conditionnement'] ?? '') !== '' ? trim($data['conditionnement']) : null;
        }
        if (array_key_exists('est_certifie', $data)) {
            $update['est_certifie'] = (int)$data['est_certifie'];
        }
        if (array_key_exists('est_actif', $data)) {
            $update['est_actif'] = (int)$data['est_actif'];
        }
        if (array_key_exists('ordre', $data)) {
            $update['ordre'] = (int)$data['ordre'];
        }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Produits_model->update_produit($id, $update)) {
            $this->json_success(null, 'Produit mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $existing = $this->Produits_model->get_produit($id);
        if (!$existing) { $this->json_error('Produit non trouvé', 404); return; }

        if ($this->Produits_model->delete_produit($id)) {
            $this->json_success(null, 'Produit supprimé');
        }
        $this->json_error('Erreur lors de la suppression');
    }

    public function api_upload()
    {
        $this->require_post();

        if (empty($_FILES['file'])) {
            $this->json_error('Aucun fichier envoyé');
            return;
        }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->json_error('Erreur upload: ' . $file['error']);
            return;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed)) {
            $this->json_error('Type de fichier non autorisé (jpg, png, gif, webp uniquement)');
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $this->json_error('Fichier trop volumineux (max 5Mo)');
            return;
        }

        $dir = FCPATH . 'attachments/Produits/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = url_title(pathinfo($file['name'], PATHINFO_FILENAME), '-', true) . '_' . time() . '.' . $ext;
        $dest = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->json_error('Erreur lors de l\'enregistrement du fichier');
            return;
        }

        $path = 'attachments/Produits/' . $filename;
        $this->json_success(['path' => $path, 'filename' => $filename], 'Fichier uploadé');
    }
}
