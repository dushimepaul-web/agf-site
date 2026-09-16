# Surveillance des maladies — Documentation utilisateur & administrateur

Application de surveillance épidémiologique (Burundi) construite sur CodeIgniter 3 (HMVC).
Trois espaces de tableaux de bord pilotés par des **variables configurantes** :
- **Dashboard** — Mpox, Choléra, Rougeole, Paludisme (vue d'ensemble standard)
- **Ebola BDBV** — 10 modules (moteur de calcul + variables)
- **AMR / React-AMR** — 7 modules (moteur de calcul + variables)

---

## 1. Connexion

| Rôle | Email | Mot de passe |
|---|---|---|
| Administrateur | `admin@surveillance.bi` | _défini par l'administrateur système_ |

URL : `http://localhost/maladie/` (WAMP) · première visite → page Permissions conseillée (voir §4).

---

## 2. Espace Ebola BDBV

Menu **Dashboard → Ebola BDBV**. La barre latérale gauche liste les 10 modules ;
chaque module affiche des cartes KPI calculées en direct depuis la base (`cas`, `contacts`,
`isolements`, `tests_labo`, `depenses_ripostes`) et comparées aux variables configurantes
(panneau **Variables** en bas de page).

| Module | Indicateurs | Variables utilisées |
|---|---|---|
| Délai de détection | Délai moyen (j), % sous seuil, % datés | `SEUIL_DELAI_DETECTION_HEURES` (converti en jours ; repli `SEUIL_DELAI_DETECTION_JOURS`), `OBJECTIF_DETECTION_PCT` |
| Suivi des contacts | Contacts/cas, % suivis, devenus cas, durée | `OBJECTIF_CONTACTS_PAR_CAS`, `OBJECTIF_PCT_CONTACTS_SUIVIS`, `DUREE_SUIVI_CONTACTS_JOURS` |
| Classification des cas | Suspect/Probable/Confirmé/Écarté, % investigués, délai d'investigation | `OBJECTIF_PCT_PROBABLES_INVESTIGUES`, `DELAI_INVESTIGATION_JOURS` |
| Confirmation laboratoire | % cas testés, délai de résultat | `OBJECTIF_PCT_CAS_TESTES`, `SEUIL_DELAI_RESULTAT_JOURS` |
| Délai d'isolement | % isolés, délai moyen | `OBJECTIF_PCT_ISOLE`, `SEUIL_DELAI_ISOLEMENT_JOURS` |
| Transmission spatiale | Cas par province, % locaux, épicentres | `SEUIL_TRANSMISSION_COMMUNAUTAIRE`, `NB_MIN_EPICENTRES` |
| Mortalité | Décès, CFR | `SEUIL_ALERTE_CFR` |
| Impact économique | Coût de riposte, % budget consommé | `BUDGET_ANNUEL_RIPOSTE` |
| Cadre opérationnel | Districts/structures actifs | `NBR_DISTRICTS_OPERATIONNELS`, `NBR_STRUCTURES_OPERATIONNELLES` |
| Renseignement | Tendances 7j/7j, variation | `SEUIL_ALERTE_TREND` |

Une carte passe en **bordure rouge** quand l'alerte calculée est déclenchée
(délai moyen > seuil, objectif non atteint, CFR > seuil, budget dépassé…).

### Données de démonstration Ebola
Script réexécutable (purge + insertion) : `DB/donnees_demo_ebola.sql`.
Contenu : 10 patients, 14 cas `CAS-EBOLA-001…014`, 27 contacts, 6 isolements,
34 tests labo, 10 dépenses (164,5 M BIF), 6 vaccinations, budget de riposte 200 M BIF.
Exécution : `php tests/runseed_demo.php` (ou via l'outil de sauvegarde/import SQL).

---

## 3. Espace AMR / React-AMR

Menu **Dashboard → AMR / React-AMR**. 7 modules (Vue d'ensemble, Patients, Diagnostics,
Thérapie conseillée, Alertes, Historique, Recommandations) avec les mêmes mécanismes
de variables (`SEUIL_ALERTE_RESISTANCE_AMR`, `OBJECTIF_PCT_ATBG_RENDUS`, …).

---

## 4. Droits par rôle (page Permissions)

Menu **Administration → Permissions** : sélectionnez un rôle, cochez pour chaque module :
- **Lire** (`peut_lire`) — accès aux KPI, variables et historique ;
- **Éditer les valeurs** (`peut_editer_valeurs`) — permet de modifier la valeur courante
  d'une variable depuis le panneau Variables des dashboards (l'ancienne valeur est historisée) ;
- **Gérer les variables** (`peut_gerer_variables`) — accès au CRUD complet de la page
  **Administration → Variables** (créer/supprimer une variable, changer bornes, unité…).

Le rôle **Administrateur** (id 1) est exempté de toute vérification.
Comptes : seuls les comptes de rôle 1 existent par défaut ; la gestion des comptes/rôles
se fait via **Administration → Rôles**.

---

## 5. Administration des variables

Menu **Administration → Variables** (réservé aux rôles disposant de `peut_gerer_variables`) :

- **Liste** : recherche + filtre par application (Ebola BDBV / AMR), pagination ;
- **Créer** : module cible (obligatoire), code (normalisé en majuscules, caractères
  accentués transformés en `_`), libellé, type (`SEUIL`, `NUMERIQUE`, `TEXTE`,
  `FORMULE`, `LISTE`, `BOOLEEN`), bornes min/max (validation à l'enregistrement),
  unité, options JSON pour les types LISTE ;
- **Modifier** : mêmes champs ; le code reste unique au sein d'un module ;
- **Supprimer** : purge aussi l'historique (`valeurs_variables`).

**Historisation** : toute modification de valeur depuis un dashboard crée une nouvelle
ligne `valeurs_variables` (date d'effet = maintenant) et clôt l'ancienne
(`date_fin_effet`), avec l'auteur (`modifie_par`) et un commentaire.

---

## 6. API (JSON, session requise)

| Méthode | URL | Description |
|---|---|---|
| GET | `api/ebola/module?module=DETECTION_DELAY` | KPI d'un module Ebola (+ courbe épidémique) |
| GET | `api/ebola/variables?module=…` | Variables du module + `peut_editer`/`peut_gerer` |
| POST | `api/ebola/variables/update` | Modifier la valeur (historise ; 403 si droit absent) |
| GET | `api/ebola/variables/historique?variable_id=…` | Historique des valeurs |
| GET | `api/amr/module`, `api/amr/variables`, `api/amr/variables/update`, `api/amr/variables/historique` | Idem pour AMR |
| GET | `api/variables` | Liste CRUD admin (toutes variables + module) |
| POST | `api/variables/create`, `api/variables/{id}/update`, `GET api/variables/{id}/delete` | CRUD admin (403 si droit absent) |

---

## 7. Tests smoke

Script PowerShell : `tests/smoke_ebola_amr.ps1` (exécutable sous Windows, utilise `curl.exe`).
Vérifie : login, les 10 modules Ebola, les modules AMR, variables + droits, mise à jour
avec historique, CRUD complet d'une variable (création → lecture → mise à jour →
vérification → suppression), et le rendu des pages.

```powershell
powershell -ExecutionPolicy Bypass -File tests\smoke_ebola_amr.ps1
```

Résultat attendu : `RESULTAT: 32 OK, 0 FAIL`.

---

## 8. Base de données

- Dump complet : `DB/surveillance_burundi.sql` (régénéré via `mysqldump --routines --triggers`) ;
- Scripts d'évolution : `DB/etape1_update.sql`, `DB/etape1_uuids.sql`,
  `DB/etape1_uuids2.sql` (UUID maladies/symptomes, exécution sûre), `DB/etape2_3_conformite.sql` ;
- Tables clés du moteur : `modules` (application `EBOLA_BDBV`/`AMR`),
  `variables` (code, type, bornes, unité), `valeurs_variables` (historique daté + auteur),
  `roles_droits` (peut_lire / peut_editer_valeurs / peut_gerer_variables).

Restaurer le dump : `mysql -u root surveillance_burundi < DB\surveillance_burundi.sql`
puis rejouer `DB/donnees_demo_ebola.sql` pour les données démo Ebola.