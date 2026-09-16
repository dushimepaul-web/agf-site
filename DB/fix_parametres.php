<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');

$updates = [
    'nom_app' => 'African Green Farmers',
    'site_adresse' => 'Zambie',
    'contact_personne' => 'African Green Farmers (A.G.F) Limited',
];

foreach ($updates as $clef => $valeur) {
    $stmt = $pdo->prepare('UPDATE parametres SET valeur = ? WHERE clef = ?');
    $stmt->execute([$valeur, $clef]);
    echo "Updated: $clef = $valeur\n";
}

echo "DONE";
