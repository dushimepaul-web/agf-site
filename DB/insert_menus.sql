SET NAMES utf8mb4;

SET @produits_id = (SELECT id_menu FROM menus WHERE code = 'PRODUITS');
SET @admin_id = (SELECT id_menu FROM menus WHERE code = 'ADMINISTRATION');

INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES
(UUID(), 'PRODUITS_LIST', 'Liste des produits', 'bi-circle', 'Produits', @produits_id, 1),
(UUID(), 'PRODUITS_CATEGORIES', 'Categories', 'bi-circle', 'Categories', @produits_id, 2),
(UUID(), 'PRODUITS_UNITES', 'Unites d''affaires', 'bi-circle', 'Unites', @produits_id, 3),
(UUID(), 'ADMIN_MENUS', 'Menus', 'bi-circle', 'Menus', @admin_id, 1),
(UUID(), 'ADMIN_ROLES', 'Roles', 'bi-circle', 'Roles', @admin_id, 2);
