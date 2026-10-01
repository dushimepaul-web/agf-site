-- ============================================================
-- AGF - Menus admin pour les nouveaux modules CRUD
-- Exécuter une seule fois
-- ============================================================

-- Récupérer l'id du parent "Administration"
SET @admin_id = (SELECT id_menu FROM menus WHERE code = 'ADMINISTRATION' LIMIT 1);

-- Si le parent Administration n'existe pas, le créer
INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'ADMINISTRATION', 'Administration', 'bi-shield-lock', '#', NULL, 90
WHERE @admin_id IS NULL;

SET @admin_id = COALESCE(@admin_id, (SELECT id_menu FROM menus WHERE code = 'ADMINISTRATION' LIMIT 1));

-- Insérer les sous-menus (ignorer si déjà présents)
INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'MENUS', 'Menus', 'bi-circle', 'Menus', @admin_id, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'MENUS' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'ROLES', 'Rôles', 'bi-circle', 'Roles', @admin_id, 2
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'ROLES' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'UTILISATEURS', 'Utilisateurs', 'bi-circle', 'Users', @admin_id, 3
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'UTILISATEURS' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'JOURNAUX', 'Journaux', 'bi-circle', 'Logs', @admin_id, 4
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'JOURNAUX' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'SESSIONS', 'Sessions', 'bi-circle', 'Sessions', @admin_id, 5
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'SESSIONS' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'VISITEURS', 'Visiteurs', 'bi-circle', 'Visitors', @admin_id, 6
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'VISITEURS' AND parent_id = @admin_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'TEMOIGNAGES', 'Témoignages', 'bi-circle', 'Temoignages', @admin_id, 7
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'TEMOIGNAGES' AND parent_id = @admin_id);

-- Menu parent "Contenu" pour Témoignages
SET @content_id = (SELECT id_menu FROM menus WHERE code = 'CONTENU' AND parent_id IS NULL LIMIT 1);
INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'CONTENU', 'Contenu', 'bi-file-earmark', '#', NULL, 50
WHERE @content_id IS NULL;

SET @content_id = COALESCE(@content_id, (SELECT id_menu FROM menus WHERE code = 'CONTENU' AND parent_id IS NULL LIMIT 1));

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'TEMOIGNAGES_MENU', 'Témoignages', 'bi-circle', 'Temoignages', @content_id, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'TEMOIGNAGES_MENU' AND parent_id = @content_id);

-- Menu parent "Consultation"
SET @consult_id = (SELECT id_menu FROM menus WHERE code = 'CONSULTATION_MENU' AND parent_id IS NULL LIMIT 1);
INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'CONSULTATION_MENU', 'Consultation', 'bi-heart-pulse', '#', NULL, 60
WHERE @consult_id IS NULL;

SET @consult_id = COALESCE(@consult_id, (SELECT id_menu FROM menus WHERE code = 'CONSULTATION_MENU' AND parent_id IS NULL LIMIT 1));

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'CONSULTATIONS_ADMIN', 'Consultations', 'bi-circle', 'Consultations', @consult_id, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'CONSULTATIONS_ADMIN' AND parent_id = @consult_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'EXPERTS_ADMIN', 'Experts', 'bi-circle', 'Consultations/medecins', @consult_id, 2
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'EXPERTS_ADMIN' AND parent_id = @consult_id);

INSERT IGNORE INTO menus (`uuid`, `code`, `libelle`, `icon`, `route`, `parent_id`, `ordre`)
SELECT UUID(), 'PATIENTS_ADMIN', 'Patients', 'bi-circle', 'Patients', @consult_id, 3
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE code = 'PATIENTS_ADMIN' AND parent_id = @consult_id);

SELECT 'DONE - Menus ajoutés' AS result;
