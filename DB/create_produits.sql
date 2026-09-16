-- ============================================================
-- AGF - Tables produits + paramètres site
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
-- 1. produit_categories (3 catégories)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produit_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text,
  `slug` varchar(100) NOT NULL,
  `ordre` int NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cat_uuid` (`uuid`),
  UNIQUE KEY `uk_cat_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. produits (11 produits flagship)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `categorie_id` int UNSIGNED NOT NULL,
  `nom` varchar(200) NOT NULL,
  `description` text,
  `prix` varchar(100) DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `conditionnement` varchar(100) DEFAULT NULL,
  `est_certifie` tinyint(1) NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `ordre` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_prod_uuid` (`uuid`),
  UNIQUE KEY `uk_prod_slug` (`slug`),
  KEY `idx_prod_categorie` (`categorie_id`),
  CONSTRAINT `fk_prod_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `produit_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DONNÉES - Catégories
-- ============================================================
INSERT INTO `produit_categories` (`uuid`, `nom`, `description`, `slug`, `ordre`, `est_actif`) VALUES
(UUID(), 'Nutraceuticals & Bioactive', 'Compléments alimentaires bioactifs et extraits fonctionnels pour la santé', 'nutraceuticals', 1, 1),
(UUID(), 'Aliments Fortifiés & Nutrition Végétale', 'Aliments et boissons végétales enrichis et fortifiés', 'aliments-fortifies', 2, 1),
(UUID(), 'Intrants Agricoles Biologiques', 'Bio-contrôle et engrais organiques pour agriculture durable', 'intrants-biologiques', 3, 1);

-- ============================================================
-- DONNÉES - Produits (11 produits flagship)
-- ============================================================

-- Catégorie 1: Nutraceuticals (id = 1)
INSERT INTO `produits` (`uuid`, `categorie_id`, `nom`, `description`, `prix`, `slug`, `image`, `conditionnement`, `est_certifie`, `est_actif`, `ordre`) VALUES
(UUID(), 1, 'Advanced Multi-Functional Bioactive Complex',
 'Complexe bioactif multifonctionnel avancé pour la santé globale',
 '45$ = 112,500 BIF',
 'advanced-multifunctional-bioactive-complex',
 'attachments/Produits/advanced-multifunctional-bioactive-complex.png',
 'Bouteille 250g', 1, 1, 1),

(UUID(), 1, 'Immune Boosting Extract',
 'Extrait renforçant le système immunitaire',
 '38$ = 95,000 BIF',
 'immune-boosting-extract',
 'attachments/Produits/immune-boosting-extract.png',
 'Bouteille 250g', 1, 1, 2),

(UUID(), 1, 'Advanced Antioxidant Complex',
 'Complexe antioxydant avancé pour la protection cellulaire',
 '42$ = 105,000 BIF',
 'advanced-antioxidant-complex',
 'attachments/Produits/advanced-antioxidant-complex-can.jpeg',
 'Bouteille 250g', 1, 1, 3),

(UUID(), 1, 'Regenerator Plus',
 'Complément régénérant pour la récupération et la vitalité',
 '35$ = 87,500 BIF',
 'regenerator-plus',
 'attachments/Produits/regenerator-plus.png',
 'Bouteille 250g', 1, 1, 4);

-- Catégorie 2: Aliments Fortifiés (id = 2)
INSERT INTO `produits` (`uuid`, `categorie_id`, `nom`, `description`, `prix`, `slug`, `image`, `conditionnement`, `est_certifie`, `est_actif`, `ordre`) VALUES
(UUID(), 2, 'Fortified Soymilk Powder',
 'Poudre de lait de soja enrichi en vitamines et minéraux',
 '28$ = 70,000 BIF',
 'fortified-soymilk-powder',
 'attachments/Produits/fortified-soymilk-powder.png',
 'Can 500g', 1, 1, 5),

(UUID(), 2, 'Enriched Plant Milk Blend',
 'Mélange de laits végétaux enrichis pour une nutrition optimale',
 '25$ = 62,500 BIF',
 'enriched-plant-milk-blend',
 'attachments/Produits/enriched-plant-milk-blend.jpeg',
 NULL, 0, 1, 6),

(UUID(), 2, 'Fortified Vacuum Dried Tofu',
 'Tofu séché sous vide enrichi, riche en protéines végétales',
 '32$ = 80,000 BIF',
 'fortified-vacuum-dried-tofu',
 'attachments/Produits/fortified-vacuum-dried-tofu.jpeg',
 NULL, 0, 1, 7),

(UUID(), 2, 'Clean-Label Mixed Juice Blend',
 'Mélange de jus naturels sans additifs artificiels',
 '30$ = 75,000 BIF',
 'clean-label-mixed-juice-blend',
 'attachments/Produits/clean-label-mixed-juice-blend.jpeg',
 NULL, 0, 1, 8);

-- Catégorie 3: Intrants Biologiques (id = 3)
INSERT INTO `produits` (`uuid`, `categorie_id`, `nom`, `description`, `prix`, `slug`, `image`, `conditionnement`, `est_certifie`, `est_actif`, `ordre`) VALUES
(UUID(), 3, 'Multi-Pathogen Bio-control Complex',
 'Complexe biologique de bio-contrôle contre les multi-pathogènes',
 '55$ = 137,500 BIF',
 'multi-pathogen-bio-control-complex',
 'attachments/Produits/multi-pathogen-bio-control-complex.jpeg',
 NULL, 0, 1, 9),

(UUID(), 3, 'Ultra-Fine Bio-fertilizer Complex',
 'Engrais biologique ultra-fin pour une nutrition plantes optimale',
 '48$ = 120,000 BIF',
 'ultra-fine-bio-fertilizer-complex',
 'attachments/Produits/ultra-fine-bio-fertilizer-complex.jpeg',
 NULL, 0, 1, 10),

(UUID(), 3, 'Bioshield Fertilizer',
 'Engrais biologique protecteur pour cultures sensibles',
 '52$ = 130,000 BIF',
 'bioshield-fertilizer',
 'attachments/Produits/bioshield-fertilizer.jpeg',
 NULL, 0, 1, 11);

-- ============================================================
-- PARAMÈTRES SITE (table parametres existante)
-- ============================================================
INSERT INTO `parametres` (`clef`, `valeur`, `uuid`) VALUES
('whatsapp_number', '', UUID()),
('site Telephone', '', UUID()),
('site_email', '', UUID()),
('site_website', '', UUID()),
('site_adresse', '', UUID()),
('nom_entreprise', 'African Green Farmers (A.G.F) Limited', UUID()),
('slogan', 'Integrated Agro-Industrial Food Processing and Organic Fertilizer Manufacturing System', UUID()),
('tpin', '2003675243', UUID()),
('licence_investissement', 'ZDA/59004/10/2025', UUID()),
('contact_personne', '', UUID()),
('facebook_url', '', UUID()),
('linkedin_url', '', UUID()),
('youtube_url', '', UUID())
ON DUPLICATE KEY UPDATE valeur = VALUES(valeur);

-- ============================================================
-- UNITÉS D'AFFAIRES (5 SBUs)
-- ============================================================
CREATE TABLE IF NOT EXISTS `unites_affaires` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `code` varchar(50) NOT NULL,
  `nom` varchar(200) NOT NULL,
  `description` text,
  `slogan` text,
  `logo` varchar(255) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `ordre` int NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_ua_uuid` (`uuid`),
  UNIQUE KEY `uk_ua_code` (`code`),
  UNIQUE KEY `uk_ua_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `unites_affaires` (`uuid`, `code`, `nom`, `description`, `slogan`, `logo`, `slug`, `ordre`, `est_actif`) VALUES
(UUID(), 'ABI_IND', 'ABIPROF Industries', 'Division industrielle de fabrication agro-alimentaire et nutraceutique.', 'Fabrication industrielle avancée', 'attachments/Unites/abiprof-industries-logo-alt.png', 'abiprof-industries', 1, 1),
(UUID(), 'ABI_EST', 'ABIPROF Agro-Estates', 'Systèmes de production agricole intégrés sur plus de 2 000 hectares.', 'Agriculture régénérative intégrée', 'attachments/Unites/abiprof-agro-estates-logo-alt.png', 'abiprof-agro-estates', 2, 1),
(UUID(), 'ABI_LST', 'ABIPROF Livestock Bioresource', 'Systèmes d élevage et valorisation des ressources biologiques.', 'Bioélevage et valorisation biologique', 'attachments/Unites/abiprof-livestock-bioresources-logo.png', 'abiprof-livestock-bioresource', 3, 1),
(UUID(), 'CER_LAB', 'CERIQA Laboratories', 'Laboratoires scientifiques de recherche, développement et contrôle qualité.', 'Recherche scientifique et innovation', 'attachments/Unites/ceriqa-laboratories-logo.png', 'ceriqa-laboratories', 4, 1),
(UUID(), 'NHS_SUP', 'Natural Health Food Supermarkets', 'Réseau de supermarchés de santé naturelle.', 'Commerce au détail de santé naturelle', 'attachments/Unites/natural-health-supermarkets-logo.jpeg', 'natural-health-supermarkets', 5, 1);
