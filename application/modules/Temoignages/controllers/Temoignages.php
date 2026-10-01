<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Temoignages extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Gestion des témoignages';
        $this->load->view('temoignages/index', $data);
    }

    public function api_list() {
        $this->db->order_by('ordre', 'ASC')->order_by('created_at', 'DESC');
        $q = $this->db->get('temoignages');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_get($id) {
        $r = $this->Model->readOne('temoignages', ['id' => $id]);
        if (!$r) { $this->json_error('Témoignage non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create() {
        $data = $this->get_json_input();
        $titre = trim($data['titre'] ?? '');
        if ($titre === '') { $this->json_error('Le titre est obligatoire'); return; }

        $insert = [
            'titre' => $titre,
            'video_url' => trim($data['video_url'] ?? '') !== '' ? trim($data['video_url']) : null,
            'miniature' => trim($data['miniature'] ?? '') !== '' ? trim($data['miniature']) : null,
            'auteur' => trim($data['auteur'] ?? '') !== '' ? trim($data['auteur']) : null,
            'description' => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null,
            'est_actif' => isset($data['est_actif']) ? (int)$data['est_actif'] : 1,
            'ordre' => (int)($data['ordre'] ?? 0)
        ];
        if ($this->Model->create('temoignages', $insert)) {
            $this->json_success(['id' => $this->db->insert_id()], 'Témoignage créé');
        }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('temoignages', ['id' => $id]);
        if (!$existing) { $this->json_error('Témoignage non trouvé', 404); return; }

        $update = [];
        if (isset($data['titre'])) {
            $titre = trim($data['titre']);
            if ($titre === '') { $this->json_error('Le titre est obligatoire'); return; }
            $update['titre'] = $titre;
        }
        if (array_key_exists('video_url', $data)) { $update['video_url'] = trim($data['video_url'] ?? '') !== '' ? trim($data['video_url']) : null; }
        if (array_key_exists('miniature', $data)) { $update['miniature'] = trim($data['miniature'] ?? '') !== '' ? trim($data['miniature']) : null; }
        if (array_key_exists('auteur', $data)) { $update['auteur'] = trim($data['auteur'] ?? '') !== '' ? trim($data['auteur']) : null; }
        if (array_key_exists('description', $data)) { $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null; }
        if (array_key_exists('est_actif', $data)) { $update['est_actif'] = (int)$data['est_actif']; }
        if (array_key_exists('ordre', $data)) { $update['ordre'] = (int)$data['ordre']; }

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('temoignages', ['id' => $id], $update)) {
            $this->json_success(null, 'Témoignage mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('temoignages', ['id' => $id]);
        if (!$existing) { $this->json_error('Témoignage non trouvé', 404); return; }

        if ($this->db->where('id', $id)->delete('temoignages')) {
            $this->json_success(null, 'Témoignage supprimé');
        }
        $this->json_error('Erreur lors de la suppression');
    }

    public function api_upload() {
        $this->require_post();
        if (empty($_FILES['file'])) { $this->json_error('Aucun fichier envoyé'); return; }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) { $this->json_error('Erreur upload: ' . $file['error']); return; }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed)) { $this->json_error('Type non autorisé'); return; }
        if ($file['size'] > 5 * 1024 * 1024) { $this->json_error('Fichier trop volumineux (max 5Mo)'); return; }

        $dir = FCPATH . 'attachments/Temoignages/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = url_title(pathinfo($file['name'], PATHINFO_FILENAME), '-', true) . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            $this->json_error('Erreur lors de l\'enregistrement'); return;
        }
        $this->json_success(['path' => 'attachments/Temoignages/' . $filename], 'Fichier uploadé');
    }
}
