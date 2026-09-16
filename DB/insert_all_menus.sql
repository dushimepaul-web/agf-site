SET NAMES utf8mb4;

-- Supprimer les anciens enfants
DELETE FROM menus WHERE parent_id IS NOT NULL;

-- Mettre à jour les parents existants
UPDATE menus SET ordre = 1 WHERE code = 'DASHBOARD';
UPDATE menus SET ordre = 2, icon = 'bi-box-seam' WHERE code = 'PRODUITS';
UPDATE menus SET ordre = 5, icon = 'bi-shield-lock' WHERE code = 'ADMINISTRATION';

-- Ajouter le parent Public
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'PUBLIC', 'Public', 'bi-globe', NULL, NULL, 4);

-- Ajouter le parent Finance
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'FINANCE', 'Finance', 'bi-currency-exchange', NULL, NULL, 3);

-- Enfants Produits
SET @produits_id = (SELECT id_menu FROM menus WHERE code = 'PRODUITS');
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'PRODUITS_LIST', 'Liste des produits', 'bi-circle', 'Produits', @produits_id, 1),
(UUID(), 'PRODUITS_CATEGORIES', 'Catégories', 'bi-circle', 'Categories', @produits_id, 2),
(UUID(), 'PRODUITS_UNITES', 'Unités d''affaires', 'bi-circle', 'Unites', @produits_id, 3);

-- Enfants Administration
SET @admin_id = (SELECT id_menu FROM menus WHERE code = 'ADMINISTRATION');
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'ADMIN_MENUS', 'Menus', 'bi-circle', 'Menus', @admin_id, 1),
(UUID(), 'ADMIN_ROLES', 'Rôles', 'bi-circle', 'Roles', @admin_id, 2),
(UUID(), 'ADMIN_LOGS', 'Journaux', 'bi-circle', 'Logs', @admin_id, 3);

-- Enfants Public
SET @public_id = (SELECT id_menu FROM menus WHERE code = 'PUBLIC');
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'PUBLIC_CONTACT', 'Messages contact', 'bi-circle', 'ContactUs', @public_id, 1),
(UUID(), 'PUBLIC_FAQ', 'FAQ', 'bi-circle', 'Faq', @public_id, 2),
(UUID(), 'PUBLIC_GALERIE', 'Galerie médias', 'bi-circle', 'GalerieMedias', @public_id, 3),
(UUID(), 'PUBLIC_PARTENAIRES', 'Partenaires', 'bi-circle', 'Partenaires', @public_id, 4),
(UUID(), 'PUBLIC_SOCIAL', 'Réseaux sociaux', 'bi-circle', 'SocialLinks', @public_id, 5);

-- Enfants Finance
SET @finance_id = (SELECT id_menu FROM menus WHERE code = 'FINANCE');
INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'FINANCE_BROKERS', 'Intermédiaires', 'bi-circle', 'Brokers', @finance_id, 1),
(UUID(), 'FINANCE_INVESTORS', 'Investisseurs', 'bi-circle', 'Investors', @finance_id, 2);

SELECT 'DONE';
