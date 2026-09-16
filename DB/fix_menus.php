<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');
$pdo->exec('DELETE FROM menus WHERE parent_id IS NOT NULL');

$parents = [
    'PRODUITS' => ['icon' => 'bi-box-seam', 'ordre' => 2],
    'FINANCE' => ['icon' => 'bi-currency-exchange', 'ordre' => 3],
    'PUBLIC' => ['icon' => 'bi-globe', 'ordre' => 4],
    'ADMINISTRATION' => ['icon' => 'bi-shield-lock', 'ordre' => 5],
];

foreach ($parents as $code => $info) {
    $pdo->exec("UPDATE menus SET icon='{$info['icon']}', ordre={$info['ordre']} WHERE code='$code'");
}

$getParent = $pdo->prepare('SELECT id_menu FROM menus WHERE code = ?');
$insert = $pdo->prepare('INSERT INTO menus (uuid, code, libelle, icon, route, parent_id, ordre) VALUES (UUID(), ?, ?, ?, ?, ?, ?)');

$children = [
    'PRODUITS' => [
        ['PRODUITS_LIST', 'Liste des produits', 'bi-circle', 'Produits', 1],
        ['PRODUITS_CATEGORIES', 'Catégories', 'bi-circle', 'Categories', 2],
        ['PRODUITS_UNITES', 'Unités d\'affaires', 'bi-circle', 'Unites', 3],
    ],
    'ADMINISTRATION' => [
        ['ADMIN_MENUS', 'Menus', 'bi-circle', 'Menus', 1],
        ['ADMIN_ROLES', 'Rôles', 'bi-circle', 'Roles', 2],
        ['ADMIN_LOGS', 'Journaux', 'bi-circle', 'Logs', 3],
    ],
    'PUBLIC' => [
        ['PUBLIC_CONTACT', 'Messages contact', 'bi-circle', 'ContactUs', 1],
        ['PUBLIC_FAQ', 'FAQ', 'bi-circle', 'Faq', 2],
        ['PUBLIC_GALERIE', 'Galerie médias', 'bi-circle', 'GalerieMedias', 3],
        ['PUBLIC_PARTENAIRES', 'Partenaires', 'bi-circle', 'Partenaires', 4],
        ['PUBLIC_SOCIAL', 'Réseaux sociaux', 'bi-circle', 'SocialLinks', 5],
    ],
    'FINANCE' => [
        ['FINANCE_BROKERS', 'Intermédiaires', 'bi-circle', 'Brokers', 1],
        ['FINANCE_INVESTORS', 'Investisseurs', 'bi-circle', 'Investors', 2],
    ],
];

foreach ($children as $parentCode => $items) {
    $getParent->execute([$parentCode]);
    $parent = $getParent->fetch(PDO::FETCH_ASSOC);
    if (!$parent) continue;
    $parentId = $parent['id_menu'];
    foreach ($items as $item) {
        $insert->execute([$item[0], $item[1], $item[2], $item[3], $parentId, $item[4]]);
    }
}

echo "DONE";
