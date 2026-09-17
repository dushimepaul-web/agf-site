CREATE TABLE IF NOT EXISTS `temoignages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `miniature` varchar(500) DEFAULT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `description` text,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `ordre` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
