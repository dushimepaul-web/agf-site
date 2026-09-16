# surveillance-maladie
PLAN DE DÉVELOPPEMENT
Dashboard Intégré de Surveillance Épidémiologique du Burundi
Mpox · Choléra · Rougeole · Paludisme  —  Ebola BDBV  —  AMR / React-AMR
1. Objectif du projet
Faire évoluer les tableaux de bord de surveillance d'un support statique vers des outils dynamiques, structurés en 3 étapes :
•  Étape 1 — Dashboard Mpox / Choléra / Rougeole / Paludisme : un dashboard unique avec un sélecteur de maladie qui recharge automatiquement les KPIs, graphiques, cartes et filtres correspondants.
•  Étape 2 — Dashboard Ebola BDBV : une application à menus multiples (Detection Delay, Contact Tracing, Case Ascertainment, Lab Confirmation, Isolation Delay, Spatial Transmission, Mortality, Economic Impact, Framework, Intelligence).
•  Étape 3 — Dashboard AMR / React-AMR : une application à menus multiples (Overview, Patients, Diagnostics, Therapy Advisor, Alerts, History, Guidelines).
Contrairement à l'Étape 1, les Étapes 2 et 3 ne reposent pas sur un simple sélecteur de maladie : ce sont des applications à part entière, où chaque menu est un module dont le comportement (seuils, formules, KPIs, alertes, recommandations) est piloté par des variables configurables plutôt que codé en dur. Modifier la valeur d'une variable modifie directement le comportement du dashboard, sans nouveau développement.
Durée totale : 14 jours par étape (42 jours au total). Chaque phase est portée collectivement : tout le monde est responsable dans chaque partie.
2. Équipe & rémunération
L'équipe est composée de 4 personnes. Jean de Dieu NTIRAMPEBA intervient à titre de coordination.
Membre de l'équipe	Rôle	Responsabilités principales	Rémunération
Jean de Dieu NTIRAMPEBA	Chef de projet / Coordination technique	Supervision globale, revue de code, arbitrage technique, validation des livrables.	 Membre du Lab
Dushime Paul	Développeur Backend principal	Modélisation base de données, migrations, modèles CI3/HMVC, endpoints API, import des données Excel.	500 $
Nturo Methoussela	Développeur Backend / Intégration	Vues d'agrégation SQL, endpoints géographiques et statistiques, gestion des rôles d'accès, appui à l'intégration frontend.	500 $
Mureranyambo Belyse	Développeuse Frontend	Interface du dashboard, sélecteur de maladie, graphiques Chart.js, carte Leaflet.js, filtres dynamiques, tests utilisateurs.	500 $
		TOTAL BUDGET DÉVELOPPEMENT	1 500 $

 
3. Planning détaillé
Responsabilité partagée : chaque membre de l'équipe intervient sur chaque partie du projet, du premier au dernier jour.
3.1 Étape 1 — Dashboard Mpox / Choléra / Rougeole / Paludisme (14 jours)
Approche : dashboard unique avec sélecteur de maladie.
ÉTAPE 1 — DASHBOARD MPOX / CHOLÉRA / ROUGEOLE / PALUDISME (14 jours)
Jour	Volet	Tâches	Responsable(s)	Livrable du jour
J1 (Jour 1)	Conception BDD (1/3)	Analyse des besoins des 4 maladies, modélisation conceptuelle (MCD) : entités Provinces, Communes, Zones, Collines, Structures de santé, Patients, Cas, Symptômes, Tests labo, Vaccination.	Toute l'équipe	Modèle conceptuel de données validé
J2 (Jour 2)	Conception BDD (2/3)	Modélisation logique (MLD) : normalisation des tables, définition des clés primaires/étrangères, contraintes, index. Choix des types de données et des énumérations (statuts, résultats, etc.).	Toute l'équipe	Schéma logique complet (diagramme + dictionnaire de données)
J3 (Jour 3)	Conception BDD (3/3)	Implémentation physique : création des scripts SQL (migrations CI3), mise en place de la base MySQL, vues d'agrégation pour les futurs besoins du dashboard, jeux de données de test.	Toute l'équipe	Base de données solide déployée et testée
J4 (Jour 4)	Upload Excel	Développement de la fonctionnalité d'upload du fichier Excel de données de base (lecture, validation des champs, correspondance avec le schéma, insertion/mise à jour en base, gestion des erreurs de saisie).	Toute l'équipe	Module d'upload Excel fonctionnel et testé
J5 (Jour 5)	Frontend CRUD (1/2)	Interfaces de gestion (Create/Read/Update/Delete) pour les données de référence : Provinces/Communes/Zones/Collines, Structures de santé, Patients.	Toute l'équipe	CRUD Géographie/Structures/Patients opérationnel
J6 (Jour 6)	Frontend CRUD (2/2)	Interfaces CRUD pour les Cas (statuts, symptômes, tests labo), la Vaccination et les Campagnes. Validation des formulaires et retours utilisateur (messages de succès/erreur).	Toute l'équipe	CRUD Cas/Vaccination/Campagnes opérationnel
J7 (Jour 7)	Frontend Dashboard (1/4)	Construction de la structure du dashboard unique : sélecteur de maladie (select), zone des KPIs (cas, décès, CFR, taux d'attaque), intégration Chart.js pour la courbe épidémique.	Toute l'équipe	Squelette du dashboard + KPIs et courbe épidémique dynamiques
J8 (Jour 8)	Frontend Dashboard (2/4)	Intégration Leaflet.js pour la carte des hotspots (répartition géographique des cas par colline, code couleur par niveau de risque).	Toute l'équipe	Carte interactive fonctionnelle
J9 (Jour 9)	Frontend Dashboard (3/4)	Filtres géographiques en cascade (Province → Commune → Zone → Colline) et filtres démographiques (genre, tranche d'âge, statut du cas, grossesse) connectés à l'API.	Toute l'équipe	Filtres géographiques et démographiques opérationnels
J10 (Jour 10)	Frontend Dashboard (4/4)	Intégration complète : un seul appel déclenché par le sélecteur de maladie recharge KPIs + courbe + carte + filtres + qualité de surveillance + vaccination. Finalisation de l'ergonomie (UI/UX) et des rôles d'accès à l'écran.	Toute l'équipe	Dashboard dynamique multi-maladies complet
J11 (Jour 11)	Tests	Tests de l'ensemble du code : tests unitaires (backend), tests d'intégration (API/BDD), tests de bout en bout (parcours utilisateur complet sur le dashboard et les CRUD), recensement des anomalies.	Toute l'équipe	Rapport de tests avec liste des anomalies identifiées
J12 (Jour 12)	Corrections (1/2)	Correction des anomalies critiques et bloquantes identifiées lors des tests (backend et frontend).	Toute l'équipe	Anomalies critiques corrigées
J13 (Jour 13)	Corrections (2/2)	Correction des anomalies mineures, ajustements d'ergonomie, re-tests de non-régression sur l'ensemble du dashboard et des CRUD.	Toute l'équipe	Produit stabilisé, prêt pour la mise en production
J14 (Jour 14)	Mise en production	Déploiement final sur l'environnement de production, vérification post-déploiement, formation rapide des utilisateurs, remise de la documentation et mise en consommation officielle du produit.	Toute l'équipe	Dashboard livré et mis en consommation
3.2 Étape 2 — Dashboard Ebola BDBV (14 jours)
Approche révisée : application à variables configurables. Chaque menu (Detection Delay, Contact Tracing, Case Ascertainment, Lab Confirmation, Isolation Delay, Spatial Transmission, Mortality, Economic Impact, Framework, Intelligence) est un module dont les variables et leurs valeurs déterminent le comportement — plutôt qu'un jeu de données figé extrait d'un document Excel ou PDF.
ÉTAPE 2 — DASHBOARD EBOLA BDBV — APPLICATION À VARIABLES (14 jours)
Jour	Volet	Tâches	Responsable(s)	Livrable du jour
J1	Cartographie des modules	Recensement collectif des 10 menus de l'application (Detection Delay, Contact Tracing, Case Ascertainment, Lab Confirmation, Isolation Delay, Spatial Transmission, Mortality, Economic Impact, Framework, Intelligence) et identification, pour chaque menu, des variables de comportement nécessaires (seuils, formules, libellés, unités, niveaux d'alerte).	Toute l'équipe	Cartographie des 10 modules et liste des variables validée
J2	Modélisation du moteur de variables	Conception du modèle logique : table Modules (menus), table Variables (code, type : numérique / seuil / texte / formule / liste, unité, description, module associé), table ValeursVariables (valeur courante, historique, date d'effet), table Rôles_Droits (qui peut éditer quoi).	Toute l'équipe	Schéma logique du moteur de variables (diagramme + dictionnaire de données)
J3	Implémentation physique	Scripts SQL (migrations CI3), déploiement de la base MySQL, jeux de données de test avec valeurs par défaut réalistes pour les 10 modules.	Toute l'équipe	Base de données du moteur de variables déployée et testée
J4	Backend — Admin des modules/variables	Développement de l'interface d'administration permettant de créer/modifier un module (menu) et de définir ses variables (nom, type, unité, formule, description), sans recourir à un import Excel figé.	Toute l'équipe	Interface d'administration des modules et variables fonctionnelle
J5	Backend — Gestion des valeurs	CRUD des valeurs de variables : saisie, modification, historisation des changements, validation selon le type de variable, gestion des droits d'édition par rôle.	Toute l'équipe	CRUD des valeurs de variables opérationnel
J6	Backend — Moteur de calcul	Développement des endpoints qui appliquent les valeurs des variables aux calculs et à l'affichage de chaque module (ex. : seuil d'alerte du délai de détection, formule du taux de transmission, seuil de mortalité).	Toute l'équipe	Moteur de calcul piloté par variables opérationnel
J7	Frontend Dashboard (1/4)	Construction du menu latéral dynamique (10 items) et chargement automatique des variables actives associées au module sélectionné (aucun menu n'est codé en dur).	Toute l'équipe	Menu dynamique + chargement des variables par module
J8	Frontend Dashboard (2/4)	Rendu dynamique des KPIs et graphiques Chart.js (Detection Delay, Mortality, Economic Impact...) dont le contenu, les seuils et les couleurs d'alerte sont pilotés par les valeurs des variables.	Toute l'équipe	KPIs et graphiques pilotés par variables fonctionnels
J9	Frontend Dashboard (3/4)	Intégration de la carte Leaflet.js (Spatial Transmission) et des filtres (Contact Tracing, Case Ascertainment, Lab Confirmation, Isolation Delay), également paramétrables via variables.	Toute l'équipe	Carte et filtres pilotés par variables opérationnels
J10	Frontend Dashboard (4/4)	Intégration complète : chaque menu (Detection Delay, Contact Tracing, Case Ascertainment, Lab Confirmation, Isolation Delay, Spatial Transmission, Mortality, Economic Impact, Framework, Intelligence) se comporte selon la configuration de ses variables, sans modification de code. Finalisation UI/UX et rôles d'accès (dont l'accès à l'administration des variables).	Toute l'équipe	Application à variables configurables complète (10 modules)
J11	Tests	Vérification module par module qu'une modification de variable change effectivement le comportement du dashboard associé (tests unitaires, d'intégration API/BDD, tests de bout en bout).	Toute l'équipe	Rapport de tests avec liste des anomalies identifiées
J12	Corrections (1/2)	Correction des anomalies critiques et bloquantes identifiées lors des tests.	Toute l'équipe	Anomalies critiques corrigées
J13	Corrections (2/2)	Correction des anomalies mineures, ajustements d'ergonomie, re-tests de non-régression sur les 10 modules et le moteur de variables.	Toute l'équipe	Produit stabilisé, prêt pour la mise en production
J14	Mise en production	Déploiement final, vérification post-déploiement, formation des utilisateurs à l'administration des modules et des variables, remise de la documentation.	Toute l'équipe	Application livrée et mise en consommation
3.3 Étape 3 — Dashboard AMR / React-AMR (14 jours)
Approche révisée : application à variables configurables. Chaque menu (Overview, Patients, Diagnostics, Therapy Advisor, Alerts, History, Guidelines) est un module dont les variables et leurs valeurs déterminent le comportement (seuils de résistance, règles de recommandation thérapeutique, déclenchement des alertes).
ÉTAPE 3 — DASHBOARD AMR / REACT-AMR — APPLICATION À VARIABLES (14 jours)
Jour	Volet	Tâches	Responsable(s)	Livrable du jour
J1	Cartographie des modules	Recensement collectif des 7 menus de l'application (Overview, Patients, Diagnostics, Therapy Advisor, Alerts, History, Guidelines) et identification, pour chaque menu, des variables de comportement nécessaires (seuils de résistance, règles de recommandation, libellés, unités).	Toute l'équipe	Cartographie des 7 modules et liste des variables validée
J2	Modélisation du moteur de variables	Conception du modèle logique : table Modules (menus), table Variables (code, type : numérique / seuil / texte / formule / liste, unité, description, module associé), table ValeursVariables (valeur courante, historique, date d'effet), table Rôles_Droits.	Toute l'équipe	Schéma logique du moteur de variables (diagramme + dictionnaire de données)
J3	Implémentation physique	Scripts SQL (migrations CI3), déploiement de la base MySQL, jeux de données de test avec valeurs par défaut réalistes pour les 7 modules.	Toute l'équipe	Base de données du moteur de variables déployée et testée
J4	Backend — Admin des modules/variables	Développement de l'interface d'administration permettant de créer/modifier un module (menu) et de définir ses variables (ex. : seuils de résistance pour les Alertes, règles du Therapy Advisor), sans recourir à un import Excel figé.	Toute l'équipe	Interface d'administration des modules et variables fonctionnelle
J5	Backend — Gestion des valeurs	CRUD des valeurs de variables : saisie, modification, historisation des changements, validation selon le type de variable, gestion des droits d'édition par rôle (Clinicien, Laboratoire, Hôpital, Santé publique).	Toute l'équipe	CRUD des valeurs de variables opérationnel
J6	Backend — Moteur de calcul	Développement des endpoints qui appliquent les valeurs des variables aux calculs et recommandations de chaque module (ex. : seuils de déclenchement des Alertes, règles de recommandation du Therapy Advisor).	Toute l'équipe	Moteur de calcul piloté par variables opérationnel
J7	Frontend Dashboard (1/4)	Construction du menu latéral dynamique (7 items) et chargement automatique des variables actives associées au module sélectionné.	Toute l'équipe	Menu dynamique + chargement des variables par module
J8	Frontend Dashboard (2/4)	Rendu dynamique des KPIs et graphiques Chart.js (Overview, Diagnostics, History) dont le contenu et les seuils sont pilotés par les valeurs des variables.	Toute l'équipe	KPIs et graphiques pilotés par variables fonctionnels
J9	Frontend Dashboard (3/4)	Intégration des filtres Patients/Diagnostics et de la logique de recommandation du Therapy Advisor, également paramétrables via variables, connectés à l'API.	Toute l'équipe	Filtres et recommandations pilotés par variables opérationnels
J10	Frontend Dashboard (4/4)	Intégration complète : chaque menu (Overview, Patients, Diagnostics, Therapy Advisor, Alerts, History, Guidelines) se comporte selon la configuration de ses variables, sans modification de code. Finalisation UI/UX et rôles d'accès (dont l'accès à l'administration des variables).	Toute l'équipe	Application à variables configurables complète (7 modules)
J11	Tests	Vérification module par module qu'une modification de variable change effectivement le comportement du dashboard associé (tests unitaires, d'intégration API/BDD, tests de bout en bout).	Toute l'équipe	Rapport de tests avec liste des anomalies identifiées
J12	Corrections (1/2)	Correction des anomalies critiques et bloquantes identifiées lors des tests.	Toute l'équipe	Anomalies critiques corrigées
J13	Corrections (2/2)	Correction des anomalies mineures, ajustements d'ergonomie, re-tests de non-régression sur les 7 modules et le moteur de variables.	Toute l'équipe	Produit stabilisé, prêt pour la mise en production
J14	Mise en production	Déploiement final, vérification post-déploiement, formation des utilisateurs à l'administration des modules et des variables, remise de la documentation.	Toute l'équipe	Application livrée et mise en consommation
4. Architecture du moteur de variables (Étapes 2 et 3)
Pour les Étapes 2 et 3, chaque dashboard devient une application configurable reposant sur trois entités principales :
•  Modules — les menus de l'application (10 pour Ebola BDBV, 7 pour AMR).
•  Variables — les paramètres de comportement de chaque module : code, libellé, type (numérique, seuil, texte, formule, liste), unité, description.
•  Valeurs de variables — la valeur courante de chaque variable, avec historisation des changements et date d'effet.
Une interface d'administration permet de créer/modifier les modules et leurs variables, et de saisir ou ajuster les valeurs selon les droits de chaque rôle. Le frontend de chaque menu lit ses variables au chargement et adapte en conséquence ses KPIs, seuils d'alerte, graphiques, cartes, filtres et recommandations — sans qu'un nouveau développement soit nécessaire pour changer un seuil ou une règle métier.
5. Livrables finaux
•  Base de données de surveillance solide et normalisée pour l'Étape 1 (Provinces → Collines, Structures, Patients, Cas, Labo, Vaccination).
•  Dashboard frontend unique (Étape 1) avec sélecteur de maladie, courbe épidémique, carte des hotspots, filtres démographiques et géographiques.
•  Moteur de configuration par variables (Modules, Variables, Valeurs de variables) avec interface d'administration, pour les dashboards Ebola BDBV (Étape 2) et AMR (Étape 3).
•  Applications Ebola BDBV et AMR dont chaque menu se comporte selon les variables configurées, modifiables sans nouveau développement.
•  Interfaces CRUD complètes pour la gestion des données (géographie, structures, patients, cas, vaccination, campagnes, modules, variables).
•  Rapport de tests et corrections associées pour chacune des 3 étapes.
•  Documentation utilisateur courte + guide d'administration des variables.
•  Produits déployés et mis en consommation.

