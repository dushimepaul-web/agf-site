<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Migration Étape 1 : table campagnes + population de référence. */
class Migration_Etape1_campagnes extends CI_Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `campagnes` (
          `id_campagne` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
          `uuid` char(36) NOT NULL,
          `code` varchar(30) NOT NULL,
          `maladie_id` int UNSIGNED NOT NULL,
          `nom` varchar(150) NOT NULL,
          `description` text,
          `structure_id` bigint UNSIGNED DEFAULT NULL,
          `province_id` bigint UNSIGNED DEFAULT NULL,
          `date_debut` date DEFAULT NULL,
          `date_fin` date DEFAULT NULL,
          `population_cible` int UNSIGNED DEFAULT NULL,
          `statut` enum('planifiee','en_cours','terminee','annulee') NOT NULL DEFAULT 'planifiee',
          `est_actif` tinyint(1) NOT NULL DEFAULT '1',
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id_campagne`),
          UNIQUE KEY `uk_campagne_code` (`code`),
          KEY `idx_camp_maladie` (`maladie_id`),
          KEY `idx_camp_structure` (`structure_id`),
          KEY `idx_camp_province` (`province_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci");

        $this->db->query("INSERT INTO parametres (clef, valeur, uuid, deleted_at, cree_le, modifie_le)
            SELECT 'population_reference_2026', '13300000', UUID(), NULL, NOW(), NOW()
            WHERE NOT EXISTS (SELECT 1 FROM parametres WHERE clef = 'population_reference_2026' AND deleted_at IS NULL)");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `campagnes`');
        $this->db->where('clef', 'population_reference_2026')->delete('parametres');
    }
}