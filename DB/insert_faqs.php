<?php
$pdo = new PDO('mysql:host=localhost;dbname=agf_db;charset=utf8mb4', 'root', '');

$faqs = [
    ['Général', 'Qu\'est-ce qu\'African Green Farmers (A.G.F) Limited ?', 'African Green Farmers (A.G.F) Limited est une plateforme agro-industrielle intégrée dédiée à la transformation alimentaire, aux intrants biologiques et à la bioéconomie circulaire en Zambie. Fondée le 12 mars 2025, elle opère sous le nom commercial A.G.F LIMITED.', 1],
    ['Général', 'Quel est le siège social d\'A.G.F ?', 'Le siège social d\'African Green Farmers (A.G.F) Limited est situé en Zambie. L\'entreprise détient un numéro d\'identification fiscale (TPIN) : 2003675243.', 1],
    ['Général', 'Quel est le numéro d\'enregistrement fiscal (TPIN) ?', 'Le TPIN (Tax Pay Incorporation Number) d\'A.G.F Limited est le 2003675243, délivré par les autorités fiscales zambiennes.', 1],
    ['Investissement', 'Quel est le montant total de l\'investissement ?', 'Le projet représente un investissement total de 63 209 692 USD, couvrant les dépenses pré-opérationnelles, le CAPEX et l\'OPEX.', 1],
    ['Investissement', 'Quel type de financement est recherché ?', 'A.G.F recherche un prêt à long terme concessionnel (concessional long-term project loan) pour financer le développement complet du projet.', 1],
    ['Investissement', 'Quel est le numéro de licence d\'investissement ?', 'La licence d\'investissement est ZDA/59004/10/2025, délivrée par la Zambia Development Agency (ZDA). Les incitations fiscales de 5 ans sont enregistrées sous la référence ZDA/DG/DUTY du 22 janvier 2026 (Ministère des Finances).', 1],
    ['Produits', 'Quels sont les produits principaux d\'A.G.F ?', 'Les produits principaux sont organisés en 3 catégories : Nutraceuticals & Bioactifs, Aliments Fortifiés & Nutrition Végétale, et Intrants Agricoles Biologiques. L\'entreprise produit des compléments alimentaires, des aliments enrichis et des engrais biologiques.', 1],
    ['Produits', 'Quel est le site de production pilote ?', 'Le site de production pilote s\'appelle AFOOPROC (Agrofood Processing And Organic Processing Centre). L\'installation de transformation conforme aux BPF s\'appelle ABIPPOF (Agrofood & Bio-Inputs Processing Facility) INDUSTRIES.', 1],
    ['Projet', 'Quelle est la durée totale du projet ?', 'Le projet s\'étend sur 60 mois (5 ans), de la clôture financière à l\'expansion et l\'optimisation. La production commerciale à grande échelle est prévue à partir du mois 15-24.', 1],
    ['Projet', 'Quelles sont les unités stratégiques d\'A.G.F ?', 'A.G.F compte 5 unités stratégiques : ABIPROF Industries (transformation), ABIPROF Agro-Estates (agriculture), ABIPROF Livestock Bioresource (élevage), CERIQA Laboratories (contrôle qualité), et Natural Health Food Supermarkets (distribution).', 1],
    ['Projet', 'Quel est le mécanisme SBLC ?', 'Le mécanisme SBLC (Standby Letter of Credit) est en préparation avec Absa Bank Zambia pour garantir les transactions financières du projet.', 1],
    ['Projet', 'Quelle est la superficie totale des terres ?', 'Le projet prévoit l\'acquisition et le titrage foncier d\'une superficie allant jusqu\'à 2 002 hectares pour les opérations agricoles et industrielles.', 1],
];

foreach ($faqs as $faq) {
    $stmt = $pdo->prepare('INSERT INTO faq (categorie, question, reponse, est_publiee) VALUES (?, ?, ?, ?)');
    $stmt->execute([$faq[0], $faq[1], $faq[2], $faq[3]]);
    echo "Inserted: {$faq[1]}\n";
}

echo "DONE - " . count($faqs) . " FAQs created";
