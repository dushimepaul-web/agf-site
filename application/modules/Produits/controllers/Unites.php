<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unites extends MY_Controller
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
        $data['title'] = 'Unités d\'affaires';
        $this->load->view('unites/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier l\'unité' : 'Ajouter une unité';
        $data['unite'] = $id ? $this->Produits_model->get_unite($id) : null;
        if ($id && !$data['unite']) show_404();
        $this->load->view('unites/add_edit', $data);
    }

    public function api_list()
    {
        $rows = $this->Produits_model->get_unites();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Produits_model->get_unite($id);
        if (!$r) { $this->json_error('Unité non trouvée', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        $code = strtoupper(trim($data['code'] ?? ''));
        $nom = trim($data['nom'] ?? '');
        if ($code === '' || $nom === '') { $this->json_error('Code et nom obligatoires'); return; }

        if ($this->Produits_model->get_unite_by_code($code)) {
            $this->json_error('Ce code existe déjà'); return;
        }

        $slug = url_title(trim($data['slug'] ?? $nom), '-', true);

        $insert = [
            'code'        => $code,
            'nom'         => $nom,
            'slug'        => $slug,
            'description' => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null,
            'slogan'      => trim($data['slogan'] ?? '') !== '' ? trim($data['slogan']) : null,
            'logo'        => trim($data['logo'] ?? '') !== '' ? trim($data['logo']) : null,
            'ordre'       => (int)($data['ordre'] ?? 0),
            'est_actif'   => isset($data['est_actif']) ? (int)$data['est_actif'] : 1
        ];
        $id = $this->Produits_model->create_unite($insert);
        if ($id) { $this->json_success(['id' => $id], 'Unité créée'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Produits_model->get_unite($id);
        if (!$existing) { $this->json_error('Unité non trouvée', 404); return; }

        $update = [];
        if (isset($data['code'])) {
            $code = strtoupper(trim($data['code']));
            if ($code === '') { $this->json_error('Le code est obligatoire'); return; }
            $dup = $this->Produits_model->get_unite_by_code($code);
            if ($dup && $dup['id'] != $id) { $this->json_error('Ce code existe déjà'); return; }
            $update['code'] = $code;
        }
        if (isset($data['nom'])) {
            $nom = trim($data['nom']);
            if ($nom === '') { $this->json_error('Le nom est obligatoire'); return; }
            $update['nom'] = $nom;
        }
        if (isset($data['slug'])) {
            $update['slug'] = url_title(trim($data['slug']), '-', true);
        }
        if (array_key_exists('description', $data)) {
            $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null;
        }
        if (array_key_exists('slogan', $data)) {
            $update['slogan'] = trim($data['slogan'] ?? '') !== '' ? trim($data['slogan']) : null;
        }
        if (array_key_exists('logo', $data)) {
            $update['logo'] = trim($data['logo'] ?? '') !== '' ? trim($data['logo']) : null;
        }
        if (array_key_exists('ordre', $data)) {
            $update['ordre'] = (int)$data['ordre'];
        }
        if (array_key_exists('est_actif', $data)) {
            $update['est_actif'] = (int)$data['est_actif'];
        }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Produits_model->update_unite($id, $update)) {
            $this->json_success(null, 'Unité mise à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $existing = $this->Produits_model->get_unite($id);
        if (!$existing) { $this->json_error('Unité non trouvée', 404); return; }

        if ($this->Produits_model->delete_unite($id)) {
            $this->json_success(null, 'Unité supprimée');
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

        $dir = FCPATH . 'attachments/Unites/';
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

        $path = 'attachments/Unites/' . $filename;
        $this->json_success(['path' => $path, 'filename' => $filename], 'Fichier uploadé');
    }
}
