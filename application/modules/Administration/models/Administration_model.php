<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administration_model extends Model
{
    public function __construct() { parent::__construct(); }

    /**
     * Un nom de table ne peut contenir que lettres/chiffres/underscore et
     * doit exister : protège les requêtes DESCRIBE/GET/TRUNCATE interpolées.
     */
    private function _assert_safe_table($table)
    {
        $table = (string)$table;
        if (!preg_match('/^[A-Za-z0-9_]+$/', $table) || !$this->db->table_exists($table)) {
            throw new InvalidArgumentException('Table inconnue ou nom invalide');
        }
        return $table;
    }

    public function get_tables()
    {
        $tables = [];
        $result = $this->db->query("SHOW TABLES");
        foreach ($result->result_array() as $row) {
            $tables[] = array_values($row)[0];
        }
        return $tables;
    }

    public function get_table_structure($table)
    {
        $table = $this->_assert_safe_table($table);
        return $this->db->query("DESCRIBE `$table`")->result_array();
    }

    public function export_table($table)
    {
        $table = $this->_assert_safe_table($table);
        return $this->db->get($table)->result_array();
    }

    public function import_table($table, $data)
    {
        $table = $this->_assert_safe_table($table);
        if (!is_array($data)) return false;
        // TRUNCATE + insert_batch atomiques : jamais laisser la table vide
        // si insert_batch échoue.
        $this->db->trans_begin();
        $this->db->truncate($table);
        $ok = empty($data) ? true : $this->db->insert_batch($table, $data);
        if (!$ok || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

    public function create_backup($table)
    {
        $table = $this->_assert_safe_table($table);
        $data = $this->export_table($table);
        $dir = FCPATH . 'backups';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return ['success' => false, 'error' => 'Dossier backups/ inaccessible'];
        }
        $filename = $table . '_' . date('Ymd_His') . '.json';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        $written = file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
        if ($written === false) {
            return ['success' => false, 'error' => 'Écriture du fichier de sauvegarde impossible'];
        }
        return ['success' => true, 'file' => $filename, 'rows' => count($data)];
    }
}
