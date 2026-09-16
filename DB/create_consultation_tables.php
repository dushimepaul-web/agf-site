<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');

// Table des médecins
$pdo->exec("CREATE TABLE IF NOT EXISTS medecins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(150) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    telephone VARCHAR(30) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    actif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table des horaires
$pdo->exec("CREATE TABLE IF NOT EXISTS horaires_medecins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medecin_id INT UNSIGNED NOT NULL,
    jour ENUM('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    disponible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table des consultations
$pdo->exec("CREATE TABLE IF NOT EXISTS consultations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL,
    medecin_id INT UNSIGNED NOT NULL,
    nom VARCHAR(150) NOT NULL,
    prenom VARCHAR(150) NOT NULL,
    poids VARCHAR(20) DEFAULT NULL,
    taille VARCHAR(20) DEFAULT NULL,
    adresse TEXT DEFAULT NULL,
    description_symptomes TEXT NOT NULL,
    duree_symptomes VARCHAR(100) DEFAULT NULL,
    preuve_paiement VARCHAR(255) DEFAULT NULL,
    whatsapp_envoye TINYINT(1) DEFAULT 0,
    statut ENUM('en_attente','en_cours','terminee','annulee') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (uuid),
    FOREIGN KEY (medecin_id) REFERENCES medecins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Table des médias de consultation (images médicales + preuve paiement)
$pdo->exec("CREATE TABLE IF NOT EXISTS consultation_medias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT UNSIGNED NOT NULL,
    fichier_url VARCHAR(255) NOT NULL,
    type ENUM('medical','paiement') DEFAULT 'medical',
    ordre INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "DONE - Tables created\n";
