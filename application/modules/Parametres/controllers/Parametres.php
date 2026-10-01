<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parametres extends MY_Controller {
    public function __construct() { parent::__construct(); $this->not_logged_in(); $this->require_admin(); }

    public function index() {
        $data['title'] = 'Paramètres';
        $this->load->view('index', $data);
    }

    public function api_list() {
        $this->db->where('deleted_at', null);
        $rows = $this->db->get('parametres')->result_array();
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['clef']] = $r['valeur'];
        }
        $this->json_success($settings);
    }

    public function api_update() {
        $data = $this->get_json_input();
        if (empty($data) || !is_array($data)) {
            $this->json_error('Données invalides'); return;
        }
        $rows = $this->db->select('clef')->where('deleted_at', null)->get('parametres')->result_array();
        $allowed = array_flip(array_column($rows, 'clef'));
        $echecs = [];
        foreach ($data as $key => $value) {
            if (!isset($allowed[$key]) || !is_scalar($value)) continue;
            if (!$this->Model->setValueStore($key, (string)$value)) {
                $echecs[] = $key;
            }
        }
        if (!empty($echecs)) {
            $this->json_error('Erreur lors de la mise à jour de : ' . implode(', ', $echecs));
            return;
        }
        $this->json_success(null, 'Paramètres mis à jour');
    }

    public function api_upload_logo() {
        $res = $this->_handle_upload('logo', ['jpg','jpeg','png','gif','svg','webp'], 5 * 1024 * 1024, 'logo_app');
        if ($res !== true) { $this->json_error($res); return; }
        $this->json_success(['path' => $this->_upload_result['path']], 'Logo mis à jour');
    }

    public function api_upload_favicon() {
        $res = $this->_handle_upload('favicon', ['png','ico','svg','jpg','jpeg','webp'], 5 * 1024 * 1024, 'favicon_ico');
        if ($res !== true) { $this->json_error($res); return; }
        $this->json_success(['path' => $this->_upload_result['path']], 'Favicon mis à jour');
    }

    public function api_upload_login_img() {
        $res = $this->_handle_upload('login_img', ['jpg','jpeg','png','gif','svg','webp'], 5 * 1024 * 1024, 'login_img');
        if ($res !== true) { $this->json_error($res); return; }
        $this->json_success(['path' => $this->_upload_result['path']], 'Image de connexion mise à jour');
    }

    private $_upload_result = null;

    private function _handle_upload($field, $allowed_ext, $max_size, $setting_key)
    {
        $this->_upload_result = null;
        $nom_fichier = isset($_FILES[$field]['name']) ? $_FILES[$field]['name'] : '(aucun)';
        $taille = isset($_FILES[$field]['size']) ? $_FILES[$field]['size'] : 0;
        $err = isset($_FILES[$field]['error']) ? $_FILES[$field]['error'] : -1;
        if (empty($_FILES[$field])) {
            log_message('error', 'Upload ' . $field . ' rejeté: $_FILES vide (post_max_size=' . ini_get('post_max_size') . ', upload_max_filesize=' . ini_get('upload_max_filesize') . ')');
            return 'Aucun fichier reçu';
        }
        if ($err !== UPLOAD_ERR_OK) {
            $errors = [0=>'OK',1=>'Taille dépassée',2=>'Taille HTML dépassée',3=>'Partiel',4=>'Aucun fichier',6=>'Dossier tmp manquant',7=>'Ã‰criture impossible',8=>'Extension bloquée'];
            log_message('error', 'Upload ' . $field . ' rejeté: code PHP ' . $err . ' (' . ($errors[$err] ?? 'Inconnue') . ') pour ' . $nom_fichier . ' (' . $taille . ' octets)');
            return 'Erreur PHP: ' . ($errors[$err] ?? 'Inconnue');
        }
        if ($taille > $max_size) {
            log_message('error', 'Upload ' . $field . ' rejeté: taille ' . $taille . ' > ' . $max_size . ' pour ' . $nom_fichier);
            return 'Fichier trop volumineux (max ' . round($max_size / 1024 / 1024) . ' MB)';
        }
        $ext = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            log_message('error', 'Upload ' . $field . ' rejeté: extension .' . $ext . ' non autorisée pour ' . $nom_fichier);
            return 'Format non autorisé';
        }
        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
        if ($finfo) {
            $mime = finfo_file($finfo, $_FILES[$field]['tmp_name']);
            finfo_close($finfo);
            $allowed_mimes = [
                'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif',
                'svg'=>'image/svg+xml','webp'=>'image/webp','ico'=>'image/x-icon'
            ];
            if (isset($allowed_mimes[$ext]) && $mime) {
                // Certains fichiers (ico en PNG, jpg pjpeg, svg variantes) ont
                // un MIME légèrement différent : on accepte les alias courants.
                $aliases = [
                    'image/jpeg'   => ['image/jpeg', 'image/pjpeg'],
                    'image/png'    => ['image/png', 'image/x-png'],
                    'image/gif'    => ['image/gif'],
                    'image/svg+xml'=> ['image/svg+xml', 'image/svg'],
                    'image/webp'   => ['image/webp'],
                    'image/x-icon' => ['image/x-icon', 'image/vnd.microsoft.icon', 'image/png']
                ];
                $mimes_ok = $aliases[$allowed_mimes[$ext]] ?? [$allowed_mimes[$ext]];
                if (!in_array($mime, $mimes_ok, true)) {
                    log_message('error', 'Upload rejeté (MIME ' . $mime . ' pour .' . $ext . ' : ' . $_FILES[$field]['name'] . ')');
                    return 'Le contenu du fichier ne correspond pas à son extension';
                }
            }
        }
        $upload_dir = FCPATH . 'assets/uploads/logo/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $new_name = md5(uniqid()) . '.' . $ext;
        $dest = $upload_dir . $new_name;
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
            $path = 'assets/uploads/logo/' . $new_name;
            $old = $this->Model->get_setting($setting_key);
            $this->Model->setValueStore($setting_key, $path);
            $this->_upload_result = ['path' => $path];
            if ($old && $old !== $path && strpos($old, 'assets/uploads/') === 0) {
                $old_file = FCPATH . $old;
                if (file_exists($old_file) && is_file($old_file)) {
                    @unlink($old_file);
                }
            }
            return true;
        }
        return 'Erreur lors de la sauvegarde du fichier';
    }
}

