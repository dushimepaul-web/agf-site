<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SocialLinks extends MY_Controller
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
        $data['title'] = 'Réseaux sociaux';
        $this->load->view('social_links/list', $data);
    }

    public function api_list()
    {
        $rows = $this->Public_model->get_social_links();
        $this->json_success($rows);
    }

    public function api_get($id)
    {
        $r = $this->Public_model->get_social_link($id);
        if (!$r) { $this->json_error('Lien non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create()
    {
        $data = $this->get_json_input();
        if (empty($data['platform']) || empty($data['url'])) {
            $this->json_error('Plateforme et URL obligatoires'); return;
        }
        $insert = [
            'platform' => trim($data['platform']),
            'label' => trim($data['label'] ?? $data['platform']),
            'url' => trim($data['url']),
            'icon_class' => trim($data['icon_class'] ?? 'bi'),
            'icon_name' => trim($data['icon_name'] ?? ''),
            'display_order' => (int)($data['display_order'] ?? 0),
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
            'target_blank' => isset($data['target_blank']) ? (int)$data['target_blank'] : 1
        ];
        $id = $this->Public_model->create_social_link($insert);
        if ($id) { $this->json_success(['id' => $id], 'Lien créé'); return; }
        $this->json_error('Erreur lors de la création');
    }

    public function api_update($id)
    {
        $data = $this->get_json_input();
        $existing = $this->Public_model->get_social_link($id);
        if (!$existing) { $this->json_error('Lien non trouvé', 404); return; }
        $update = [];
        if (isset($data['platform'])) $update['platform'] = trim($data['platform']);
        if (isset($data['label'])) $update['label'] = trim($data['label']);
        if (isset($data['url'])) $update['url'] = trim($data['url']);
        if (array_key_exists('icon_class', $data)) $update['icon_class'] = trim($data['icon_class']);
        if (array_key_exists('icon_name', $data)) $update['icon_name'] = trim($data['icon_name']);
        if (array_key_exists('display_order', $data)) $update['display_order'] = (int)$data['display_order'];
        if (array_key_exists('is_active', $data)) $update['is_active'] = (int)$data['is_active'];
        if (array_key_exists('target_blank', $data)) $update['target_blank'] = (int)$data['target_blank'];
        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Public_model->update_social_link($id, $update)) {
            $this->json_success(null, 'Lien mis à jour');
        }
        $this->json_error('Erreur lors de la mise à jour');
    }

    public function api_delete($id)
    {
        $this->require_post();
        $this->Public_model->delete_social_link($id);
        $this->json_success(null, 'Lien supprimé');
    }
}
