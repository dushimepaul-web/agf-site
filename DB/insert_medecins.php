<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');

// Médecins
$medecins = [
    ['Dr. Jean', 'Kabongo', 'Médecine générale', 'Médecin généraliste avec 10 ans d\'expérience en zone rurale.', '+243 99 000 0001', 'kabongo@agf.bi'],
    ['Dr. Marie', 'Mukendi', 'Nutrition & Diététique', 'Spécialiste en nutrition humaine et diététique clinique.', '+243 99 000 0002', 'mukendi@agf.bi'],
    ['Dr. Pierre', 'Ilunga', 'Cardiologie', 'Cardiologue expérimenté, spécialisé dans les maladies cardiovasculaires.', '+243 99 000 0003', 'ilunga@agf.bi'],
    ['Dr. Sarah', 'Kasongo', 'Pédiatrie', 'Pédiatre dédiée à la santé des enfants et adolescents.', '+243 99 000 0004', 'kasongo@agf.bi'],
];

$insertMed = $pdo->prepare('INSERT INTO medecins (uuid, nom, prenom, specialite, bio, telephone, email) VALUES (UUID(), ?, ?, ?, ?, ?, ?)');
$insertHoraire = $pdo->prepare('INSERT INTO horaires_medecins (medecin_id, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?)');

$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
$heures = [
    ['08:00:00', '12:00:00'],
    ['14:00:00', '17:00:00'],
];

foreach ($medecins as $m) {
    $insertMed->execute($m);
    $medecinId = $pdo->lastInsertId();
    echo "Inserted: {$m[0]} {$m[1]}\n";
    
    foreach ($jours as $jour) {
        foreach ($heures as $h) {
            $insertHoraire->execute([$medecinId, $jour, $h[0], $h[1]]);
        }
    }
    echo "  -> 10 horaires créés\n";
}

echo "DONE";
