<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Variables_Controller extends MY_Controller
{
    abstract protected function app_code();
    abstract protected function app_label();
    abstract protected function app_route();
    abstract protected function app_icone();
    abstract protected function app_badge();
    abstract protected function app_js();
    abstract protected function app_js_var();
    abstract protected function kpi_for($code, $province);

    public function __construct()
    {
        parent::__construct();
        $this->not_logged_in();
    }

    public function index($module = null)
    {
        $modules = $this->Dashboard_model->modules($this->app_code());
        if (empty($modules)) { show_404(); return; }

        $code = $module;
        if ($code === null || $code === '' || $code === 'index') {
            $code = $modules[0]['code'];
        } else {
            $code = strtoupper($module);
        }
        $courant = null;
        foreach ($modules as $m) { if (strtoupper($m['code']) === $code) { $courant = $m; break; } }
        if (!$courant) { show_404(); return; }

        $data['title'] = $this->app_label() . ' — ' . $courant['nom'];
        $data['modules'] = $modules;
        $data['module_courant'] = $courant;
        $data['module_code'] = $courant['code'];
        $data['app_meta'] = array(
            'label' => $this->app_label(),
            'icone' => $this->app_icone(),
            'badge' => $this->app_badge(),
            'route' => $this->app_route(),
            'js' => $this->app_js(),
            'js_var' => $this->app_js_var(),
            'application' => $this->app_code(),
        );
        $this->render_view('Dashboard/App_View', $data);
    }

    public function api_module()
    {
        $this->not_logged_in();
        $code = strtoupper((string)$this->input->get('module'));
        $prov = $this->input->get('province');
        if (!$code) { $this->json_error('Module requis', 400); return; }
        if (!$this->Dashboard_model->module_by_code($code)) {
            $this->json_error('Module inconnu', 404);
            return;
        }
        $data = $this->kpi_for($code, $prov);
        if ($data === null) {
            $this->json_error('Module sans moteur', 400);
            return;
        }
        $this->json_success($data);
    }

    public function api_variables()
    {
        $this->not_logged_in();
        $code = strtoupper((string)$this->input->get('module'));
        $m = $this->Dashboard_model->module_by_code($code);
        if (!$m) { $this->json_error('Module inconnu', 404); return; }
        if (!has_perm($code, 'peut_lire')) {
            $this->json_error('Accès refusé : lecture non autorisée pour ce module', 403);
            return;
        }
        $vars = $this->Dashboard_model->variables_module((int)$m['id_module']);
        foreach ($vars as &$v) {
            if (!empty($v['options_liste'])) {
                $v['options_liste'] = json_decode($v['options_liste'], true);
            }
        }
        $this->json_success(array(
            'variables' => $vars,
            'peut_editer' => has_perm($code, 'peut_editer_valeurs'),
            'peut_gerer' => has_perm($code, 'peut_gerer_variables')
        ));
    }

    public function api_update_variable()
    {
        $this->not_logged_in();
        $body = $this->get_json_input();
        $id = (int)($body['variable_id'] ?? 0);
        $valeur = trim((string)($body['valeur'] ?? ''));
        $commentaire = trim((string)($body['commentaire'] ?? ''));
        if (!$id || $valeur === '') { $this->json_error('Données invalides', 400); return; }

        $var = $this->Model->readOne('variables', array('id_variable' => $id));
        if (!$var) { $this->json_error('Variable inconnue', 404); return; }
        $module = $this->Model->readOne('modules', array('id_module' => $var['module_id']));
        if (!$module) { $this->json_error('Module inconnu', 404); return; }
        if (!has_perm($module['code'], 'peut_editer_valeurs')) {
            $this->json_error('Accès refusé : modification des valeurs non autorisée', 403);
            return;
        }

        if (in_array($var['type_variable'], array('NUMERIQUE', 'SEUIL'), true)) {
            $v = (float)$valeur;
            if ($var['valeur_min'] !== null && $v < (float)$var['valeur_min']) { $this->json_error('Valeur inférieure au minimum (' . $var['valeur_min'] . ')'); return; }
            if ($var['valeur_max'] !== null && $v > (float)$var['valeur_max']) { $this->json_error('Valeur supérieure au maximum (' . $var['valeur_max'] . ')'); return; }
        }
        if ($var['type_variable'] === 'BOOLEEN') {
            $valeur = in_array($valeur, array('1', '0', 'true', 'false')) ? ($valeur === '1' || $valeur === 'true' ? '1' : '0') : null;
            if ($valeur === null) { $this->json_error('Valeur booléenne invalide'); return; }
        }

        if ($this->Dashboard_model->update_variable_value($id, $valeur, $commentaire)) {
            $this->json_success(array('variable_id' => $id, 'valeur' => $valeur), 'Valeur mise à jour');
        }
        $this->json_error('Échec de la mise à jour', 500);
    }

    public function api_historique()
    {
        $this->not_logged_in();
        $id = (int)$this->input->get('variable_id');
        if (!$id) { $this->json_error('Variable requise', 400); return; }
        $var = $this->Model->readOne('variables', array('id_variable' => $id));
        if (!$var) { $this->json_error('Variable inconnue', 404); return; }
        $module = $this->Model->readOne('modules', array('id_module' => $var['module_id']));
        if (!$module || !has_perm($module['code'], 'peut_lire')) {
            $this->json_error('Accès refusé : lecture non autorisée pour ce module', 403);
            return;
        }
        $rows = $this->Dashboard_model->historique_variable($id);
        foreach ($rows as &$r) {
            $r['auteur'] = trim((string)($r['prenom'] ?? '') . ' ' . (string)($r['nom'] ?? ''));
            if (empty(trim($r['auteur']))) $r['auteur'] = 'Système';
        }
        $this->json_success($rows);
    }
}