<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Moteur d'analyse du tableau de bord AGF.
 * Toutes les métriques sont calculées depuis la base réelle :
 * visites du site, consultations, leads investisseurs/brokers,
 * messages, catalogue produits, audit et sessions.
 */
class Dashboard_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Payload complet du dashboard pour une fenêtre glissante de $days jours.
     */
    public function dashboard_payload($days = 30)
    {
        $days = max(7, min(90, (int)$days));
        $today = date('Y-m-d');
        $start = date('Y-m-d', strtotime("-" . ($days - 1) . " days"));
        $prev_start = date('Y-m-d', strtotime("-" . (2 * $days - 1) . " days"));
        $prev_end = date('Y-m-d', strtotime("-{$days} days"));

        return [
            'meta' => [
                'days' => $days,
                'start' => $start,
                'end' => $today,
                'generated_at' => date('Y-m-d H:i:s'),
                'app' => $this->get_setting('nom_app', 'African Green Farmers Limited'),
            ],
            'kpis' => $this->_kpis($days, $start, $today, $prev_start, $prev_end),
            'series' => $this->_series($days, $start, $today),
            'devices' => $this->_devices($days, $start),
            'pages' => $this->_top_pages($days, $start),
            'hours' => $this->_peak_hours($days, $start),
            'consultations' => $this->_consultations($days, $start),
            'leads' => $this->_leads($days, $start, $today),
            'catalogue' => $this->_catalogue(),
            'activity' => $this->_activity(),
            'insights' => $this->_insights($days, $start, $today, $prev_start, $prev_end),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* KPIs + tendances (période courante vs période précédente)           */
    /* ------------------------------------------------------------------ */

    private function _count_between($table, $date_col, $start, $end)
    {
        $end_full = (strpos((string)$end, ' ') !== false) ? (string)$end : $end . ' 23:59:59';
        return (int)$this->db->where("{$date_col} >=", $start)
            ->where("{$date_col} <=", $end_full)
            ->count_all_results($table);
    }

    /** Croissance en % : (courant - précédent) / précédent * 100, ou null. */
    private function _delta($current, $previous)
    {
        $previous = (float)$previous;
        if ($previous == 0) return ($current > 0) ? 100.0 : 0.0;
        return round(($current - $previous) / $previous * 100, 1);
    }

    private function _kpis($days, $start, $today, $prev_start, $prev_end)
    {
        $visits_now = $this->_count_between('visitors_logs', 'visit_date', $start, $today);
        $visits_prev = $this->_count_between('visitors_logs', 'visit_date', $prev_start, $prev_end);
        $visits_today = (int)$this->db->where('visit_date', $today)->count_all_results('visitors_logs');

        $consult_now = $this->_count_between('consultations', 'created_at', $start, $today);
        $consult_prev = $this->_count_between('consultations', 'created_at', $prev_start, $prev_end);
        $consult_pending = (int)$this->db->where('statut', 'en_attente')->count_all_results('consultations');
        $consult_done = (int)$this->db->where('statut', 'terminee')->count_all_results('consultations');

        $inv_now = $this->_count_between('investors', 'created_at', $start, $today);
        $inv_prev = $this->_count_between('investors', 'created_at', $prev_start, $prev_end);
        $inv_total = (int)$this->db->count_all('investors');
        $brokers_total = (int)$this->db->count_all('brokers');

        $q_paid = $this->db->select_sum('prix_ht')->where('paiement_statut', 'paye')->get('consultations')->row();
        $revenu = $q_paid ? (float)$q_paid->prix_ht : 0.0;
        $q_pend = $this->db->select_sum('prix_ht')->where('paiement_statut', 'en_attente')->get('consultations')->row();
        $attente = $q_pend ? (float)$q_pend->prix_ht : 0.0;

        $msg_non_lus = (int)$this->db->where('is_readed', 0)->count_all_results('contact_us');
        $msg_total = (int)$this->db->count_all('contact_us');

        $users_actifs = (int)$this->db->where('actif', 1)->where('is_active', 1)->where('deleted_at', null)->count_all_results('users');
        $sessions_en_ligne = (int)$this->db->where('is_active', 1)
            ->where('last_activity >=', date('Y-m-d H:i:s', strtotime('-30 minutes')))
            ->count_all_results('user_sessions');

        return [
            'visites' => ['value' => $visits_now, 'delta' => $this->_delta($visits_now, $visits_prev), 'today' => $visits_today],
            'consultations' => ['value' => $consult_now, 'delta' => $this->_delta($consult_now, $consult_prev), 'pending' => $consult_pending, 'done' => $consult_done],
            'leads' => ['value' => $inv_total + $brokers_total, 'delta' => $this->_delta($inv_now, $inv_prev), 'investors' => $inv_total, 'brokers' => $brokers_total, 'new_period' => $inv_now],
            'revenus' => ['paid' => round($revenu, 2), 'pending' => round($attente, 2), 'currency' => 'USD'],
            'messages' => ['unread' => $msg_non_lus, 'total' => $msg_total],
            'utilisateurs' => ['active' => $users_actifs, 'online' => $sessions_en_ligne],
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Séries temporelles                                                  */
    /* ------------------------------------------------------------------ */

    private function _series($days, $start, $today)
    {
        // Visites par jour (remplissage des jours vides à 0)
        $rows = $this->db->select('visit_date AS d, COUNT(*) AS n', false)
            ->where('visit_date >=', $start)
            ->group_by('visit_date')
            ->get('visitors_logs')->result_array();
        $by_day = [];
        foreach ($rows as $r) $by_day[$r['d']] = (int)$r['n'];

        $consult_rows = $this->db->select('DATE(created_at) AS d, COUNT(*) AS n', false)
            ->where('created_at >=', $start)
            ->group_by('DATE(created_at)')
            ->get('consultations')->result_array();
        $consult_by_day = [];
        foreach ($consult_rows as $r) $consult_by_day[$r['d']] = (int)$r['n'];

        $lead_rows = $this->db->select('DATE(created_at) AS d, COUNT(*) AS n', false)
            ->where('created_at >=', $start)
            ->group_by('DATE(created_at)')
            ->get('investors')->result_array();
        $lead_by_day = [];
        foreach ($lead_rows as $r) $lead_by_day[$r['d']] = (int)$r['n'];

        $dates = [];
        $visits = [];
        $consults = [];
        $leads = [];
        $ma7 = [];
        $buffer = [];
        $cursor = strtotime($start);
        $end_ts = strtotime($today);
        while ($cursor <= $end_ts) {
            $d = date('Y-m-d', $cursor);
            $dates[] = date('d/m', $cursor);
            $v = $by_day[$d] ?? 0;
            $visits[] = $v;
            $consults[] = $consult_by_day[$d] ?? 0;
            $leads[] = $lead_by_day[$d] ?? 0;
            // Moyenne mobile 7 jours (lissage — lecture "data science")
            $buffer[] = $v;
            if (count($buffer) > 7) array_shift($buffer);
            $ma7[] = round(array_sum($buffer) / count($buffer), 1);
            $cursor = strtotime('+1 day', $cursor);
        }

        return [
            'labels' => $dates,
            'visits' => $visits,
            'visits_ma7' => $ma7,
            'consultations' => $consults,
            'leads' => $leads,
        ];
    }

    private function _devices($days, $start)
    {
        $rows = $this->db->select('device, COUNT(*) AS n', false)
            ->where('visit_date >=', $start)
            ->group_by('device')
            ->order_by('n', 'DESC')
            ->get('visitors_logs')->result_array();
        $out = [];
        foreach ($rows as $r) $out[] = ['label' => $r['device'] ?: 'Inconnu', 'n' => (int)$r['n']];
        return $out;
    }

    private function _top_pages($days, $start)
    {
        $rows = $this->db->select('page, COUNT(*) AS n', false)
            ->where('visit_date >=', $start)
            ->where('page IS NOT NULL', null, false)
            ->group_by('page')
            ->order_by('n', 'DESC')
            ->limit(8)
            ->get('visitors_logs')->result_array();
        $out = [];
        foreach ($rows as $r) {
            // Chemin lisible : retire scheme+domaine
            $path = preg_replace('#^https?://[^/]+#i', '', (string)$r['page']);
            if ($path === '' || $path === false) $path = '/';
            $out[] = ['label' => mb_substr($path, 0, 40), 'n' => (int)$r['n']];
        }
        return $out;
    }

    private function _peak_hours($days, $start)
    {
        $rows = $this->db->select('HOUR(visit_time) AS h, COUNT(*) AS n', false)
            ->where('visit_date >=', $start)
            ->group_by('HOUR(visit_time)')
            ->get('visitors_logs')->result_array();
        $hours = array_fill(0, 24, 0);
        foreach ($rows as $r) {
            $h = (int)$r['h'];
            if ($h >= 0 && $h < 24) $hours[$h] = (int)$r['n'];
        }
        return $hours;
    }

    /* ------------------------------------------------------------------ */
    /* Consultations                                                       */
    /* ------------------------------------------------------------------ */

    private function _consultations($days, $start)
    {
        $by_statut = [];
        foreach ($this->db->select('statut, COUNT(*) AS n', false)->group_by('statut')->get('consultations')->result_array() as $r) {
            $by_statut[$r['statut']] = (int)$r['n'];
        }
        $by_paiement = [];
        foreach ($this->db->select('paiement_statut, COUNT(*) AS n', false)->group_by('paiement_statut')->get('consultations')->result_array() as $r) {
            $by_paiement[$r['paiement_statut']] = (int)$r['n'];
        }
        $by_mode = [];
        foreach ($this->db->select('mode_paiement, COUNT(*) AS n', false)->where('mode_paiement IS NOT NULL', null, false)->group_by('mode_paiement')->get('consultations')->result_array() as $r) {
            $by_mode[$r['mode_paiement']] = (int)$r['n'];
        }

        $recent = $this->db->select('c.numero_consultation, c.patient_nom, c.patient_prenom, c.statut, c.paiement_statut, c.prix_ht, c.devise, c.mode_paiement, c.created_at, c.description_symptomes, m.specialite', false)
            ->join('medecins m', 'm.id = c.medecin_id', 'left')
            ->order_by('c.created_at', 'DESC')
            ->limit(6)
            ->get('consultations c')->result_array();

        // Délai moyen de traitement pour les consultations soldées
        $avg = $this->db->query(
            "SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) AS h
             FROM consultations
             WHERE statut IN ('terminee','en_cours') AND updated_at > created_at"
        )->row_array();
        $avg_hours = ($avg && $avg['h'] !== null) ? round((float)$avg['h'], 1) : null;

        // Ancienneté des dossiers en attente (heures)
        $stale = $this->db->query(
            "SELECT COUNT(*) AS n, MAX(TIMESTAMPDIFF(HOUR, created_at, NOW())) AS max_h
             FROM consultations WHERE statut = 'en_attente'"
        )->row_array();

        return [
            'by_statut' => $by_statut,
            'by_paiement' => $by_paiement,
            'by_mode' => $by_mode,
            'recent' => array_map(function ($r) {
                $sym = (string)($r['description_symptomes'] ?? '');
                unset($r['description_symptomes']);
                $r['resume'] = mb_strlen($sym) > 70 ? mb_substr($sym, 0, 70) . '…' : $sym;
                return $r;
            }, $recent),
            'avg_treatment_hours' => $avg_hours,
            'pending_count' => (int)($stale['n'] ?? 0),
            'pending_max_hours' => (int)($stale['max_h'] ?? 0),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Leads investisseurs / brokers                                        */
    /* ------------------------------------------------------------------ */

    private function _leads($days, $start, $today)
    {
        $inv_series = [];
        $rows = $this->db->select('DATE(created_at) AS d, COUNT(*) AS n', false)
            ->where('created_at >=', $start)
            ->group_by('DATE(created_at)')
            ->get('investors')->result_array();
        foreach ($rows as $r) $inv_series[$r['d']] = (int)$r['n'];

        $brokers_series = [];
        $rows = $this->db->select('DATE(created_at) AS d, COUNT(*) AS n', false)
            ->where('created_at >=', $start)
            ->group_by('DATE(created_at)')
            ->get('brokers')->result_array();
        foreach ($rows as $r) $brokers_series[$r['d']] = (int)$r['n'];

        $recent_inv = $this->db->select('full_name, organization, email, commitment_range, timeline, created_at')
            ->order_by('created_at', 'DESC')->limit(5)->get('investors')->result_array();
        $recent_brokers = $this->db->select('full_name, firm_name, email, engagement_model, created_at')
            ->order_by('created_at', 'DESC')->limit(5)->get('brokers')->result_array();

        return [
            'investors_by_day' => $inv_series,
            'brokers_by_day' => $brokers_series,
            'recent_investors' => $recent_inv,
            'recent_brokers' => $recent_brokers,
            'investors_total' => (int)$this->db->count_all('investors'),
            'brokers_total' => (int)$this->db->count_all('brokers'),
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Catalogue                                                            */
    /* ------------------------------------------------------------------ */

    private function _catalogue()
    {
        $par_cat = $this->db->select('pc.nom AS categorie, COUNT(p.id) AS n', false)
            ->join('produit_categories pc', 'pc.id = p.categorie_id', 'left')
            ->group_by('pc.nom')
            ->get('produits p')->result_array();
        $out = [];
        foreach ($par_cat as $r) $out[] = ['label' => $r['categorie'] ?: 'Sans catégorie', 'n' => (int)$r['n']];
        return [
            'total' => (int)$this->db->count_all('produits'),
            'actifs' => (int)$this->db->where('est_actif', 1)->count_all_results('produits'),
            'certifies' => (int)$this->db->where('est_certifie', 1)->count_all_results('produits'),
            'par_categorie' => $out,
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Activité récente (messages, utilisateurs, audit)                     */
    /* ------------------------------------------------------------------ */

    private function _activity()
    {
        $contacts = $this->db->select('FullName, Email, Subject, is_readed, Date_creation')
            ->order_by('Date_creation', 'DESC')->limit(5)->get('contact_us')->result_array();

        $users = $this->db->select('nom, prenom, email, derniere_connexion, created_at, actif')
            ->order_by('created_at', 'DESC')->limit(5)->get('users')->result_array();

        $logs = $this->db->select('logs.action, logs.description, logs.niveau, logs.created_at, users.email', false)
            ->join('users', 'users.id = logs.user_id', 'left')
            ->order_by('logs.id', 'DESC')->limit(8)->get('logs')->result_array();

        $newsletter = (int)$this->db->where('deleted_at', null)->count_all_results('newsletter_abonnes');
        $partenaires = (int)$this->db->where('est_actif', 1)->count_all_results('partenaires');
        $faq = (int)$this->db->count_all('faq');

        return compact('contacts', 'users', 'logs') + [
            'newsletter' => $newsletter,
            'partenaires' => $partenaires,
            'faq' => $faq,
        ];
    }

    /* ------------------------------------------------------------------ */
    /* Insights automatiques (règles analytiques)                           */
    /* ------------------------------------------------------------------ */

    private function _insights($days, $start, $today, $prev_start, $prev_end)
    {
        $insights = [];
        $add = function ($type, $icon, $title, $text) use (&$insights) {
            $insights[] = ['type' => $type, 'icon' => $icon, 'title' => $title, 'text' => $text];
        };

        $visits_now = $this->_count_between('visitors_logs', 'visit_date', $start, $today);
        $visits_prev = $this->_count_between('visitors_logs', 'visit_date', $prev_start, $prev_end);
        $delta = $this->_delta($visits_now, $visits_prev);

        // 1. Tendance de trafic (test de variation period-over-period)
        if ($visits_prev > 0) {
            if ($delta >= 20) $add('success', 'bi-graph-up-arrow', 'Trafic en forte hausse', "Le trafic du site progresse de {$delta}% sur {$days} jours ({$visits_now} visites vs {$visits_prev} sur la période précédente).");
            elseif ($delta <= -20) $add('danger', 'bi-graph-down-arrow', 'Trafic en baisse', "Le trafic recule de {$delta}% sur {$days} jours ({$visits_now} visites contre {$visits_prev} précédemment). Une campagne de visibilité est à envisager.");
            else $add('info', 'bi-activity', 'Trafic stable', "Le trafic reste stable sur {$days} jours : {$visits_now} visites (variation de {$delta}%).");
        } elseif ($visits_now > 0) {
            $add('info', 'bi-rocket-takeoff', 'Reprise de trafic', "{$visits_now} visites enregistrées sur la période, aucune activité mesurée sur la période précédente.");
        }

        // 2. Détection d'anomalie : aujourd'hui vs distribution 30j (z-score)
        $hist = $this->db->select('visit_date, COUNT(*) AS n', false)
            ->where('visit_date >=', date('Y-m-d', strtotime('-30 days')))
            ->where('visit_date <', $today)
            ->group_by('visit_date')->get('visitors_logs')->result_array();
        if (count($hist) >= 5) {
            $vals = array_map(function ($r) { return (int)$r['n']; }, $hist);
            $mean = array_sum($vals) / count($vals);
            $variance = array_sum(array_map(function ($v) use ($mean) { return ($v - $mean) ** 2; }, $vals)) / count($vals);
            $std = sqrt($variance);
            $today_n = (int)$this->db->where('visit_date', $today)->count_all_results('visitors_logs');
            if ($std > 0) {
                $z = ($today_n - $mean) / $std;
                if ($z >= 2) $add('success', 'bi-stars', 'Pic de fréquentation anormal (à la hausse)', "Aujourd'hui : {$today_n} visites pour une moyenne de " . round($mean, 1) . " (écart-type " . round($std, 1) . ") — z-score = +" . round($z, 1) . ". Pic inhabituel détecté.");
                elseif ($z <= -2 && $today_n == 0 && (int)date('G') >= 10) $add('warning', 'bi-exclamation-triangle', 'Fréquentation atypiquement nulle', "Aucune visite aujourd'hui alors que la moyenne des 30 derniers jours est de " . round($mean, 1) . " visites/jour (z-score = " . round($z, 1) . "). Vérifiez le site.");
            }
        }

        // 3. Heure de pointe
        $hours = $this->_peak_hours($days, $start);
        $max_h = array_search(max($hours), $hours, true);
        if (max($hours) > 0) {
            $part = round(max($hours) / max(1, array_sum($hours)) * 100);
            $add('info', 'bi-clock-history', 'Heure de pointe', sprintf("Le pic de fréquentation se situe à %02dh avec %.0f%% du trafic de la période (%d visites).", $max_h, $part, $hours[$max_h]));
        }

        // 4. Part mobile
        $dev = $this->_devices($days, $start);
        $total_d = array_sum(array_column($dev, 'n'));
        foreach ($dev as $d) {
            if (stripos($d['label'], 'mobile') !== false && $total_d > 0) {
                $pct = round($d['n'] / $total_d * 100);
                if ($pct >= 60) $add('warning', 'bi-phone', 'Audience majoritairement mobile', "{$pct}% du trafic provient des mobiles — assurez-vous que les parcours clés (contact, investisseurs, consultations) sont parfaitement adaptés au mobile.");
                break;
            }
        }

        // 5. Consultations en attente (dossiers stale)
        $pending = (int)$this->db->where('statut', 'en_attente')->count_all_results('consultations');
        if ($pending > 0) {
            $max_h_wait = (int)$this->db->query("SELECT MAX(TIMESTAMPDIFF(HOUR, created_at, NOW())) AS h FROM consultations WHERE statut='en_attente'")->row()->h;
            $type = $max_h_wait > 48 ? 'danger' : 'warning';
            $add($type, 'bi-hourglass-split', 'Consultations en attente', "{$pending} dossier(s) en attente de traitement, le plus ancien depuis {$max_h_wait} heures. Un médecin devrait les prendre en charge.");
        }

        // 6. Paiements
        $attente = (int)$this->db->where('paiement_statut', 'en_attente')->count_all_results('consultations');
        $paye = (int)$this->db->where('paiement_statut', 'paye')->count_all_results('consultations');
        if ($attente > 0 && $paye == 0) {
            $add('warning', 'bi-cash-coin', 'Aucun paiement encaissé', "{$attente} consultation(s) avec paiement en attente (virement bancaire). Relance des dossiers recommandée.");
        }

        // 7. Messages non lus
        $unread = (int)$this->db->where('is_readed', 0)->count_all_results('contact_us');
        if ($unread > 0) {
            $add($unread >= 3 ? 'danger' : 'info', 'bi-envelope-exclamation', 'Messages en attente de réponse', "{$unread} message(s) du formulaire de contact non traité(s). La rapidité de réponse est un levier de conversion majeur.");
        }

        // 8. Pipeline investisseurs
        $inv_total = (int)$this->db->count_all('investors');
        $new_inv = $this->_count_between('investors', 'created_at', $start, $today . ' 23:59:59');
        if ($inv_total > 0) {
            $add('info', 'bi-funnel', 'Pipeline investisseurs', "{$inv_total} investisseurs enregistrés au total, dont {$new_inv} sur les {$days} derniers jours. " . (int)$this->db->count_all('brokers') . " courtier(s) également référencé(s).");
        }

        // 9. Utilisateurs / sécurité
        $orphan_sessions = (int)$this->db->where('is_active', 1)
            ->where('last_activity <', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->count_all_results('user_sessions');
        if ($orphan_sessions > 0) {
            $add('info', 'bi-shield-check', 'Sessions inactives', "{$orphan_sessions} session(s) suivie(s) inactives depuis plus de 2 heures mais encore marquées actives — nettoyage possible depuis le module Sessions.");
        }

        $no_login = (int)$this->db->where('derniere_connexion', null)->count_all_results('users');
        if ($no_login > 0) {
            $add('warning', 'bi-person-x', 'Comptes jamais connectés', "{$no_login} utilisateur(s) n'ont jamais ouvert de session — vérifiez qu'ils ont bien reçu leurs accès.");
        }

        // 10. Catalogue
        $produits = (int)$this->db->count_all('produits');
        $cert = (int)$this->db->where('est_certifie', 1)->count_all_results('produits');
        if ($produits > 0 && $cert < $produits) {
            $add('info', 'bi-patch-check', 'Certification produit', "{$cert} produit(s) certifié(s) sur {$produits} — " . ($produits - $cert) . " produit(s) sans certification affichée.");
        }

        return $insights;
    }
}
