<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Permissions par rôle (table roles_droits).
 * Le rôle ADMIN (id 1) est bypassé dans permission_helper::has_perm().
 */
class Role_permission_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param int    $role_id
     * @param string $module_code code module (ex: CONSULTATION, ADMINISTRATION)
     * @param string $permission  peut_lire | peut_editer_valeurs | peut_gerer_variables | can_view | ...
     * @return bool
     */
    public function check_permission($role_id, $module_code, $permission) {
        $role_id = (int)$role_id;
        $module_code = strtoupper(trim((string)$module_code));
        $permission = trim((string)$permission);
        if ($role_id <= 0 || $module_code === '' || $permission === '') return false;

        $row = $this->db->where('role_id', $role_id)
            ->where('module_code', $module_code)
            ->where('permission', $permission)
            ->get('roles_droits')
            ->row_array();
        return $row && (int)$row['allowed'] === 1;
    }

    public function grant($role_id, $module_code, $permission, $allowed = 1) {
        $data = [
            'role_id' => (int)$role_id,
            'module_code' => strtoupper(trim((string)$module_code)),
            'permission' => trim((string)$permission),
            'allowed' => $allowed ? 1 : 0,
        ];
        $existing = $this->db->where('role_id', $data['role_id'])
            ->where('module_code', $data['module_code'])
            ->where('permission', $data['permission'])
            ->get('roles_droits')->row_array();
        if ($existing) {
            return $this->db->where('id', $existing['id'])->update('roles_droits', ['allowed' => $data['allowed']]);
        }
        return $this->db->insert('roles_droits', $data);
    }

    public function permissions_for_role($role_id) {
        return $this->db->where('role_id', (int)$role_id)->get('roles_droits')->result_array();
    }
}
