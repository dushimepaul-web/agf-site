<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');

// Rôle MEDECIN
$medecinRole = $pdo->query("SELECT id FROM roles WHERE code='MEDECIN'")->fetch(PDO::FETCH_ASSOC);
$roleId = $medecinRole['id'];

// Médecins à créer
$medecins = [
    ['Jean', 'Kabongo', 'Médecine générale', 'Médecin généraliste avec 10 ans d\'expérience.', '+243990000001'],
    ['Marie', 'Mukendi', 'Nutrition & Diététique', 'Spécialiste en nutrition humaine.', '+243990000002'],
    ['Pierre', 'Ilunga', 'Cardiologie', 'Cardiologue expérimenté.', '+243990000003'],
    ['Sarah', 'Kasongo', 'Pédiatrie', 'Pédiatre dédiée à la santé des enfants.', '+243990000004'],
];

$insertUser = $pdo->prepare('INSERT INTO users (uuid, username, email, password_hash, nom, prenom, role_id, actif) VALUES (UUID(), ?, ?, ?, ?, ?, ?, 1)');
$insertMed = $pdo->prepare('INSERT INTO medecins (user_id, specialite, bio) VALUES (?, ?, ?)');
$insertHoraire = $pdo->prepare('INSERT INTO horaires_medecins (medecin_id, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?)');

$jours = ['lundi','mardi','mercredi','jeudi','vendredi'];
$heures = [['08:00:00','12:00:00'], ['14:00:00','17:00:00']];

foreach ($medecins as $m) {
    $username = strtolower($m[0] . '.' . $m[1]);
    $email = $username . '@agf.bi';
    $hash = password_hash('password', PASSWORD_DEFAULT);
    
    $insertUser->execute([$username, $email, $hash, $m[1], $m[0], $roleId]);
    $userId = $pdo->lastInsertId();
    
    $insertMed->execute([$userId, $m[2], $m[3]]);
    $medecinId = $pdo->lastInsertId();
    
    foreach ($jours as $jour) {
        foreach ($heures as $h) {
            $insertHoraire->execute([$medecinId, $jour, $h[0], $h[1]]);
        }
    }
    echo "Created: Dr. {$m[0]} {$m[1]} (user_id=$userId, medecin_id=$medecinId)\n";
}

echo "DONE";
