<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partenaires extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
        $this->require_admin();
        $this->load->model('Public/Public_model');
    }

    public function index()
    {
        $data['title'] = 'Partenaires';
        $this->load->view('partenaires/list', $data);
    }

    public function add_edit($id = null)
    {
        $data['title'] = $id ? 'Modifier le partenaire' : 'Ajouter un partenaire';
        $data['partenaire'] = $id ? $this->Public_model->get_partenaire($id) : null;
        if ($id && !$data['partenaire']) show_404();
        $this->load->view('partenaires/add_edit', $data);
    }

    public function api_list()
    {
        $rows = $this->Public_model->get_partenaires();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Public_model->get_partenaire($id);
        if (!$r) { $this->json_error('Partenaire non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        if (empty($data['nom'])) { $this->json_error('Le nom est obligatoire'); return; }
        if (empty($data['type_partenaire'])) { $this->json_error('Le type est obligatoire'); return; }
        $insert = [
            'type_partenaire' => $data['type_partenaire'],
            'nom' => trim($data['nom']),
            'logo_url' => trim($data['logo_url'] ?? '') !== '' ? trim($data['logo_url']) : null,
            'description' => trim($data['description'] ?? '') !== '' ? trim($data['description']) : null,
            'pays' => trim($data['pays'] ?? '') !== '' ? trim($data['pays']) : null,
            'site_web' => trim($data['site_web'] ?? '') !== '' ? trim($data['site_web']) : null,
            'niveau_partenariat' => $data['niveau_partenariat'] ?? 'commercial',
            'date_debut' => !empty($data['date_debut']) ? $data['date_debut'] : null,
            'est_actif' => isset($data['est_actif']) ? (int)$data['est_actif'] : 1
        ];
        $id = $this->Public_model->create_partenaire($insert);
        if ($id) { $this->json_success(['id' => $id], 'Partenaire créé'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Public_model->get_partenaire($id);
        if (!$existing) { $this->json_error('Partenaire non trouvé', 404); return; }
        $update = [];
        if (isset($data['nom'])) $update['nom'] = trim($data['nom']);
        if (isset($data['type_partenaire'])) $update['type_partenaire'] = $data['type_partenaire'];
        if (array_key_exists('logo_url', $data)) $update['logo_url'] = trim($data['logo_url'] ?? '') !== '' ? trim($data['logo_url']) : null;
        if (array_key_exists('description', $data)) $update['description'] = trim($data['description'] ?? '') !== '' ? trim($data['description']) : null;
        if (array_key_exists('pays', $data)) $update['pays'] = trim($data['pays'] ?? '') !== '' ? trim($data['pays']) : null;
        if (array_key_exists('site_web', $data)) $update['site_web'] = trim($data['site_web'] ?? '') !== '' ? trim($data['site_web']) : null;
        if (isset($data['niveau_partenariat'])) $update['niveau_partenariat'] = $data['niveau_partenariat'];
        if (array_key_exists('date_debut', $data)) $update['date_debut'] = !empty($data['date_debut']) ? $data['date_debut'] : null;
        if (array_key_exists('est_actif', $data)) $update['est_actif'] = (int)$data['est_actif'];
        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Public_model->update_partenaire($id, $update)) {
            $this->json_success(null, 'Partenaire mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Public_model->delete_partenaire($id);
        $this->json_success(null, 'Partenaire supprimé');
    }
}
