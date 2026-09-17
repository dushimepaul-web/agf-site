<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model {

    protected $default_table = '';
    protected $primary_key = 'id';
    protected $soft_delete_key = 'deleted_at';
    protected $created_at_key = 'created_at';
    protected $updated_at_key = 'updated_at';

    public function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('url');
        $this->load->driver('cache', array('adapter' => 'file'));
        date_default_timezone_set('Africa/Bujumbura');
    }

    // CRUD

    public function create($table, $data, $return_id = false) {
        if ($this->db->field_exists($this->created_at_key, $table) && !isset($data[$this->created_at_key])) {
            $data[$this->created_at_key] = date('Y-m-d H:i:s');
        }
        $query = $this->db->insert($table, $data);
        if ($query) {
            return $return_id ? $this->db->insert_id() : true;
        }
        log_message('error', "Create failed on table {$table}: " . $this->db->error()['message']);
        return false;
    }

    public function create_last_id($table, $data) {
        return $this->create($table, $data, true);
    }

    public function create_batch($table, $data) {
        if (empty($data)) return false;
        if ($this->db->field_exists($this->created_at_key, $table)) {
            foreach ($data as &$row) {
                if (!isset($row[$this->created_at_key])) {
                    $row[$this->created_at_key] = date('Y-m-d H:i:s');
                }
            }
        }
        $query = $this->db->insert_batch($table, $data);
        if (!$query) log_message('error', "Batch insert failed on table {$table}: " . $this->db->error()['message']);
        return (bool) $query;
    }

    function readOne($table, $criteres) {
        $this->db->where($criteres);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    public function read($table, $where = [], $order_by = null, $order = 'DESC', $limit = null, $offset = 0, $include_deleted = false) {
        if (!$include_deleted && $this->db->field_exists($this->soft_delete_key, $table)) {
            $this->db->where($this->soft_delete_key, null);
        }
        if (!empty($where)) {
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        $this->db->where_in($key, $value);
                    } else {
                        $this->db->where($key, $value);
                    }
                }
            } else {
                $this->db->where($where);
            }
        }
        if ($order_by !== null) $this->db->order_by($order_by, $order);
        if ($limit !== null) $this->db->limit($limit, $offset);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function read_one($table, $where, $include_deleted = false) {
        if (!is_array($where)) $where = [$this->primary_key => $where];
        if (!$include_deleted && $this->db->field_exists($this->soft_delete_key, $table)) {
            $this->db->where($this->soft_delete_key, null);
        }
        $query = $this->db->get_where($table, $where);
        return $query->row_array();
    }

    public function read_where_in($table, $ids = [], $id_field = 'id') {
        if (empty($ids)) return [];
        if ($this->db->field_exists($this->soft_delete_key, $table)) {
            $this->db->where($this->soft_delete_key, null);
        }
        $this->db->where_in($id_field, $ids);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function read_limit($table, $limit, $order_by = null, $order = 'DESC') {
        return $this->read($table, [], $order_by, $order, $limit);
    }

    public function update($table, $where, $data, $return_affected = false) {
        if ($this->db->field_exists($this->updated_at_key, $table) && !isset($data[$this->updated_at_key])) {
            $data[$this->updated_at_key] = date('Y-m-d H:i:s');
        }
        $this->db->where($where);
        $query = $this->db->update($table, $data);
        if ($query) return $return_affected ? $this->db->affected_rows() : true;
        log_message('error', "Update failed on table {$table}: " . $this->db->error()['message']);
        return false;
    }

    public function update_return_affected($table, $where, $data) {
        if ($this->update($table, $where, $data)) return $this->read_one($table, $where);
        return null;
    }

    public function update_where_in($table, $ids = [], $data = [], $id_field = 'id') {
        if (empty($ids) || empty($data)) return false;
        if ($this->db->field_exists($this->updated_at_key, $table) && !isset($data[$this->updated_at_key])) {
            $data[$this->updated_at_key] = date('Y-m-d H:i:s');
        }
        $this->db->where_in($id_field, $ids);
        return $this->db->update($table, $data);
    }

    public function update_batch($table, $data, $key = 'id') {
        if (empty($data)) return false;
        $query = $this->db->update_batch($table, $data, $key);
        if (!$query) log_message('error', "Batch update failed on table {$table}: " . $this->db->error()['message']);
        return (bool) $query;
    }

    public function delete($table, $where, $soft = true) {
        if ($soft && $this->db->field_exists($this->soft_delete_key, $table)) {
            return $this->update($table, $where, [$this->soft_delete_key => date('Y-m-d H:i:s')]);
        }
        $this->db->where($where);
        $query = $this->db->delete($table);
        if (!$query) log_message('error', "Delete failed on table {$table}: " . $this->db->error()['message']);
        return (bool) $query;
    }

    public function restore($table, $where) {
        if ($this->db->field_exists($this->soft_delete_key, $table)) {
            return $this->update($table, $where, [$this->soft_delete_key => null]);
        }
        return false;
    }

    // REQUETES

    public function query($sql, $bindings = null) {
        if (!is_null($bindings) && !empty($bindings)) {
            $query = $this->db->query($sql, $bindings);
        } else {
            $query = $this->db->query($sql);
        }
        if ($query && $query->num_rows() > 0) return $query->result_array();
        return [];
    }

    public function readQuery($query, $bindings = null) {
        if (!is_null($bindings) && !empty($bindings)) {
            $query = $this->db->query($query, $bindings);
        } else {
            $query = $this->db->query($query);
        }
        if ($query) return $query->result_array();
    }

    public function query_one($sql, $bindings = null) {
        if (!is_null($bindings) && !empty($bindings)) {
            $query = $this->db->query($sql, $bindings);
        } else {
            $query = $this->db->query($sql);
        }
        if ($query && $query->num_rows() > 0) return $query->row_array();
        return null;
    }

    public function execute_query($sql) {
        $query = $this->db->query($sql);
        if ($query && $query->num_rows() > 0) return $query->result();
        return [];
    }

    // UTILITAIRES

    public function count($table, $where = [], $include_deleted = false) {
        if (!empty($where)) $this->db->where($where);
        if (!$include_deleted && $this->db->field_exists($this->soft_delete_key, $table)) {
            $this->db->where($this->soft_delete_key, null);
        }
        return $this->db->count_all_results($table);
    }

    public function exists($table, $where) {
        return $this->count($table, $where) > 0;
    }

    public function email_exists($email, $table = 'users') {
        return $this->exists($table, ['email' => $email]);
    }

    // PARAMETRES

    public function get_setting($key, $default = null) {
        static $cache = [];
        if (isset($cache[$key])) return $cache[$key];
        $this->db->select('valeur');
        $this->db->from('parametres');
        $this->db->where('clef', $key);
        $this->db->where('deleted_at', null);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $value = $query->row()->valeur;
            $result = $value !== null ? $value : $default;
            $cache[$key] = $result;
            return $result;
        }
        $cache[$key] = $default;
        return $default;
    }

    public function set_setting($key, $value) {
        $exists = $this->exists('parametres', ['clef' => $key, 'deleted_at' => null]);
        if ($exists) {
            return $this->update('parametres', ['clef' => $key], ['valeur' => $value]);
        } else {
            return $this->create('parametres', [
                'clef' => $key,
                'valeur' => $value,
                'uuid' => bin2hex(random_bytes(16)),
                'deleted_at' => null
            ]);
        }
    }

    public function get_all_settings() {
        $settings = $this->read('parametres', ['deleted_at' => null]);
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['clef']] = $setting['valeur'];
        }
        return $result;
    }

    public function get_all_configs() {
        return $this->get_all_settings();
    }

    // LOGS

    public function log_history($user_id, $action, $description = '') {
        return $this->create('logs', [
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'url' => current_url(),
            'method' => $this->input->method(),
            'niveau' => 'info',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function log_login_attempt($email, $success = false, $user_id = null) {
        if ($success && $user_id) {
            $this->update('users', ['id' => $user_id], [
                'last_login_at' => date('Y-m-d H:i:s'),
                'last_login_ip' => $this->input->ip_address()
            ]);
        }
        return $this->create('logs', [
            'user_id' => $user_id,
            'action' => $success ? 'login_success' : 'login_failed',
            'description' => "Tentative de connexion avec l'email: {$email}",
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'url' => current_url(),
            'method' => $this->input->method(),
            'niveau' => $success ? 'info' : 'warning'
        ]);
    }

    // VISITEURS

    public function log_visit() {
        $ip = $this->input->ip_address();
        if (in_array($ip, ['::1', '127.0.0.1'])) $ip = '197.255.128.0';
        $device = 'Desktop';
        if ($this->agent->is_mobile()) $device = 'Mobile';
        $today = date('Y-m-d');
        $current_page = current_url();
        $existing = $this->read_one('visitors_logs', [
            'ip_address' => $ip, 'visit_date' => $today, 'page' => $current_page
        ]);
        if ($existing) return true;
        $geo = $this->get_geolocation($ip);
        return $this->create('visitors_logs', [
            'page' => $current_page, 'ip_address' => $ip,
            'user_agent' => $this->input->user_agent(),
            'referer' => $this->agent->referrer(),
            'device' => $device, 'visit_date' => $today,
            'visit_time' => date('H:i:s')
        ]);
    }

    private function get_geolocation($ip) {
        $cache_key = 'geo_' . str_replace('.', '_', $ip);
        $cached = $this->cache->get($cache_key);
        if ($cached !== false) return $cached;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "http://ip-api.com/json/{$ip}",
            CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_FOLLOWLOCATION => true
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $geo = [];
        if ($http_code == 200 && $response) {
            $data = json_decode($response, true);
            $geo = [
                'country' => $data['country'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'lat' => $data['lat'] ?? null, 'lon' => $data['lon'] ?? null
            ];
            $this->cache->save($cache_key, $geo, 86400);
        }
        return $geo;
    }

    // UTILITAIRES

    public function get_youtube_id($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null;
    }

    public function get_table() { return $this->default_table; }

    public function set_table($table) { $this->default_table = $table; return $this; }

    // AUTH

    public function login($username, $password) {
        $user = $this->read_one('users', ['email' => $username]);
        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            return $user;
        }
        return false;
    }

    public function check_email($email) {
        if (!$email) return false;
        $sql = 'SELECT * FROM users WHERE email = ?';
        $query = $this->db->query($sql, array($email));
        return ($query->num_rows() == 1) ? true : false;
    }

    public function get_user_by_id($id) {
        return $this->db->where('id', $id)->get('users')->row_array();
    }

    public function get_user_group($user_id) {
        $sql = "SELECT r.* FROM users u INNER JOIN roles r ON r.id = u.role_id WHERE u.id = ?";
        return $this->query_one($sql, [$user_id]);
    }

    // TRANSACTIONS

    public function begin_transaction() { $this->db->trans_begin(); }

    public function commit_transaction() {
        if ($this->db->trans_status() === false) { $this->db->trans_rollback(); return false; }
        $this->db->trans_commit();
        return true;
    }

    public function rollback_transaction() { $this->db->trans_rollback(); }

    public function transaction(callable $callback) {
        $this->begin_transaction();
        try {
            $result = $callback($this);
            if ($this->commit_transaction()) return $result;
            throw new Exception('Transaction failed');
        } catch (Exception $e) {
            $this->rollback_transaction();
            log_message('error', 'Transaction error: ' . $e->getMessage());
            throw $e;
        }
    }

    // SESSIONS

    public function create_user_session($user_id, $session_id = null) {
        $this->load->library('user_agent');
        if ($session_id === null) $session_id = session_id();
        if ($this->agent->is_mobile()) $device_type = 'mobile';
        elseif ($this->agent->is_robot()) $device_type = 'bot';
        elseif ($this->agent->is_tablet()) $device_type = 'tablet';
        else $device_type = 'desktop';
        $data = [
            'user_id' => $user_id, 'session_id' => $session_id,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'device_type' => $device_type,
            'platform' => $this->agent->platform(),
            'browser' => $this->agent->browser(),
            'login_time' => date('Y-m-d H:i:s'),
            'last_activity' => date('Y-m-d H:i:s'),
            'expiry_time' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'is_active' => 1
        ];
        $this->db->where('user_id', $user_id)->where('is_active', 1)
                 ->update('user_sessions', [
                     'is_active' => 0, 'logout_time' => date('Y-m-d H:i:s'), 'logout_reason' => 'force'
                 ]);
        $this->db->insert('user_sessions', $data);
        return $this->db->insert_id();
    }

    public function get_user_login_history($user_id, $limit = 10) {
        return $this->read('user_sessions', ['user_id' => $user_id], 'login_time', 'DESC', $limit);
    }

    // 2FA

    public function toggle_two_factor($user_id, $enable = true, $secret = null) {
        $data = ['two_factor_enabled' => $enable ? 1 : 0];
        if ($enable && $secret) $data['two_factor_secret'] = $secret;
        elseif (!$enable) $data['two_factor_secret'] = null;
        return $this->update('users', ['id' => $user_id], $data);
    }

    public function has_two_factor_enabled($user_id) {
        $user = $this->read_one('users', ['id' => $user_id]);
        return $user && $user['two_factor_enabled'] == 1;
    }

    public function get_two_factor_secret($user_id) {
        $user = $this->read_one('users', ['id' => $user_id]);
        return $user ? $user['two_factor_secret'] : null;
    }

    // EMAIL VERIFICATION

    public function generate_email_verification_token($user_id) {
        $token = bin2hex(random_bytes(32));
        $updated = $this->update('users', ['id' => $user_id], [
            'email_verification_token' => $token, 'email_verified_at' => null
        ]);
        return $updated ? $token : false;
    }

    public function verify_email_token($token) {
        $user = $this->read_one('users', ['email_verification_token' => $token]);
        if ($user) {
            $this->update('users', ['id' => $user['id']], [
                'email_verified_at' => date('Y-m-d H:i:s'), 'email_verification_token' => null
            ]);
            return $user;
        }
        return false;
    }

    public function is_email_verified($user_id) {
        $user = $this->read_one('users', ['id' => $user_id]);
        return $user && $user['email_verified_at'] !== null;
    }

    // USER STATUS

    public function toggle_user_status($user_id, $activate = true) {
        return $this->update('users', ['id' => $user_id], ['is_active' => $activate ? 1 : 0]);
    }

    public function is_user_active($user_id) {
        $user = $this->read_one('users', ['id' => $user_id, 'is_active' => 1]);
        return !empty($user);
    }

    public function soft_delete_user($user_id) {
        return $this->update('users', ['id' => $user_id], [
            'deleted_at' => date('Y-m-d H:i:s'), 'is_active' => 0
        ]);
    }

    public function restore_user($user_id) {
        return $this->update('users', ['id' => $user_id], ['deleted_at' => null, 'is_active' => 1]);
    }

    // SESSION STATS

    public function get_session_stats() {
        $stats = [];
        $stats['total_active'] = $this->db->where('is_active', 1)->count_all_results('user_sessions');
        $devices = $this->db->select('device_type, COUNT(*) as count')
            ->where('is_active', 1)->group_by('device_type')->get('user_sessions')->result_array();
        $stats['by_device'] = [];
        foreach ($devices as $device) $stats['by_device'][$device['device_type']] = $device['count'];
        $browsers = $this->db->select('browser, COUNT(*) as count')
            ->where('is_active', 1)->where('browser IS NOT NULL')
            ->group_by('browser')->get('user_sessions')->result_array();
        $stats['by_browser'] = [];
        foreach ($browsers as $browser) $stats['by_browser'][$browser['browser']] = $browser['count'];
        return $stats;
    }

    public function get_online_users($minutes = 15) {
        $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        $this->db->select('user_id')->distinct()->from('user_sessions')
                 ->where('is_active', 1)->where('last_activity >=', $since);
        return $this->db->get()->result_array();
    }

    // MEDECINS

    public function getDoctorByUUID($uuid) {
        $this->db->select('
            medecins.id,
            medecins.uuid,
            medecins.user_id,
            medecins.specialite,
            medecins.numero_licence,
            medecins.annees_experience,
            medecins.honoraires_consultation,
            medecins.currency,
            medecins.USD_EUR_Equivalent_en_BIF,
            medecins.prix_pour_residant_burundi,
            medecins.est_disponible,
            medecins.note_moyenne,
            medecins.nombre_avis,
            medecins.bio,
            medecins.diplomes,
            medecins.langues_parlees,
            medecins.actif,
            medecins.created_at,
            medecins.updated_at,
            users.nom,
            users.prenom,
            users.email,
            users.telephone,
            users.photo,
            users.is_active,
            users.est_verifie
        ');
        $this->db->from('medecins');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('medecins.uuid', $uuid);
        $this->db->where('medecins.actif', 1);
        $this->db->where('users.is_active', 1);
        $query = $this->db->get();
        return $query->row_array() ?: null;
    }

    public function get_medecins_disponibles($limit = null) {
        $this->db->select('medecins.*, users.nom, users.prenom, users.email');
        $this->db->from('medecins');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('medecins.actif', 1);
        $this->db->where('users.is_active', 1);
        if ($limit) $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    // PAYS

    public function getPaysByName($name) {
        $this->db->where('pays', $name);
        $query = $this->db->get('pays');
        return $query->row_array() ?: null;
    }

    // PAIEMENT

    public function getActivePaymentMethods() {
        $this->db->where('actif', 1);
        $query = $this->db->get('mode_paiements');
        return $query->result_array();
    }

    // STATISTIQUES

    public function get_statistics() {
        $stats = [];
        $stats['total_utilisateurs'] = $this->db->count_all('users');
        $stats['total_roles'] = $this->db->count_all('roles');
        $stats['total_menus'] = $this->db->count_all('menus');
        $stats['total_logs'] = $this->db->count_all('logs');
        $stats['users_actifs'] = $this->db->where('actif', 1)->count_all_results('users');
        $stats['sessions_actives'] = $this->db->where('is_active', 1)->count_all_results('user_sessions');
        return $stats;
    }
}
