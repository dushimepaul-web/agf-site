<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Gestion des menus';
        $data['menus'] = $this->Model->read('menus', [], 'ordre', 'ASC');
        $this->load->view('menus/index', $data);
    }

    public function api_list() {
        $this->db->order_by('ordre');
        $q = $this->db->get('menus');
        $this->json_success($q !== false ? $q->result_array() : []);
    }

    public function api_get($id) {
        $r = $this->Model->readOne('menus', ['uuid' => $id]);
        if (!$r) { $this->json_error('Menu non trouvé', 404); return; }
        $this->json_success($r);
    }

    public function api_create() {
        $data = $this->get_json_input();
        if (empty($data['code']) || empty($data['libelle'])) {
            $this->json_error('Code et libellé obligatoires'); return;
        }
        if ($this->Model->readOne('menus', ['code' => trim($data['code'])])) {
            $this->json_error('Ce code existe déjà'); return;
        }
        $insert = [
            'code' => trim($data['code']),
            'libelle' => trim($data['libelle']),
            'icon' => trim($data['icon'] ?? '') !== '' ? trim($data['icon']) : null,
            'route' => trim($data['route'] ?? '') !== '' ? trim($data['route']) : null,
            'parent_id' => !empty($data['parent_id']) && is_numeric($data['parent_id']) ? intval($data['parent_id']) : null,
            'ordre' => isset($data['ordre']) && is_numeric($data['ordre']) ? intval($data['ordre']) : 0
        ];
        if ($this->Model->createLastId('menus', $insert)) {
            $this->json_success(['id_menu' => $this->db->insert_id()], 'Menu créé');
        }
        $this->json_error('Erreur');
    }

    public function api_update($id) {
        $data = $this->get_json_input();
        $existing = $this->Model->readOne('menus', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Menu non trouvé', 404); return; }

        $update = [];
        if (isset($data['code'])) {
            $code = trim($data['code']);
            if ($code === '') { $this->json_error('Le code est obligatoire'); return; }
            $dup = $this->Model->readOne('menus', ['code' => $code, 'id_menu !=' => $existing['id_menu']]);
            if ($dup) { $this->json_error('Ce code existe déjà'); return; }
            $update['code'] = $code;
        }
        if (isset($data['libelle'])) {
            $libelle = trim($data['libelle']);
            if ($libelle === '') { $this->json_error('Le libellé est obligatoire'); return; }
            $update['libelle'] = $libelle;
        }
        if (array_key_exists('icon', $data)) $update['icon'] = trim($data['icon'] ?? '') !== '' ? trim($data['icon']) : null;
        if (array_key_exists('route', $data)) $update['route'] = trim($data['route'] ?? '') !== '' ? trim($data['route']) : null;
        if (array_key_exists('parent_id', $data)) {
            $update['parent_id'] = !empty($data['parent_id']) && is_numeric($data['parent_id']) ? intval($data['parent_id']) : null;
        }
        if (array_key_exists('ordre', $data)) $update['ordre'] = is_numeric($data['ordre']) ? intval($data['ordre']) : 0;

        if (empty($update)) { $this->json_error('Aucune donnée à modifier'); return; }
        if ($this->Model->update('menus', ['uuid' => $id], $update))
            $this->json_success(null, 'Menu mis à jour');
        else $this->json_error('Erreur');
    }

    public function api_delete($id) {
        $this->require_post();
        $existing = $this->Model->readOne('menus', ['uuid' => $id]);
        if (!$existing) { $this->json_error('Menu non trouvé', 404); return; }
        $children = $this->Model->read('menus', ['parent_id' => $existing['id_menu']]);
        if (!empty($children)) {
            $this->json_error('Supprimez d\'abord les sous-menus'); return;
        }
        if ($this->db->where('uuid', $id)->delete('menus'))
            $this->json_success(null, 'Menu supprimé');
        else $this->json_error('Erreur');
    }
}

