import re
import statistics
from datetime import date, datetime, time, timedelta
from decimal import Decimal

from django.db import connection

from core.helpers import get_setting


def _rows(sql, params=None):
    with connection.cursor() as cursor:
        cursor.execute(sql, params or [])
        columns = [col[0] for col in cursor.description]
        return [dict(zip(columns, row)) for row in cursor.fetchall()]


def _row(sql, params=None):
    rows = _rows(sql, params)
    return rows[0] if rows else None


def _scalar(sql, params=None, default=0):
    row = _row(sql, params)
    if not row:
        return default
    return next(iter(row.values()), default)


def _end_value(end):
    if isinstance(end, str):
        return end if " " in end else end + " 23:59:59"
    if isinstance(end, datetime):
        return end.strftime("%Y-%m-%d %H:%M:%S")
    if isinstance(end, date):
        return end.isoformat() + " 23:59:59"
    return str(end)


def _count_between(table, date_col, start, end):
    value = _scalar(
        f"SELECT COUNT(*) AS n FROM {table} WHERE {date_col} >= %s AND {date_col} <= %s",
        [start.isoformat() if isinstance(start, date) else str(start), _end_value(end)],
    )
    return int(value or 0)


def _delta(current, previous):
    previous = float(previous or 0)
    if previous == 0:
        return 100.0 if float(current or 0) > 0 else 0.0
    return round((float(current) - previous) / previous * 100, 1)


def _kpis(days, start, today, prev_start, prev_end):
    visits_now = _count_between("visitors_logs", "visit_date", start, today)
    visits_prev = _count_between("visitors_logs", "visit_date", prev_start, prev_end)
    visits_today = int(_scalar("SELECT COUNT(*) AS n FROM visitors_logs WHERE visit_date = %s", [today]) or 0)

    consult_now = _count_between("consultations", "created_at", start, today)
    consult_prev = _count_between("consultations", "created_at", prev_start, prev_end)
    consult_pending = int(
        _scalar("SELECT COUNT(*) AS n FROM consultations WHERE statut = %s", ["en_attente"]) or 0
    )
    consult_done = int(
        _scalar("SELECT COUNT(*) AS n FROM consultations WHERE statut = %s", ["terminee"]) or 0
    )

    inv_now = _count_between("investors", "created_at", start, today)
    inv_prev = _count_between("investors", "created_at", prev_start, prev_end)
    inv_total = int(_scalar("SELECT COUNT(*) AS n FROM investors") or 0)
    brokers_total = int(_scalar("SELECT COUNT(*) AS n FROM brokers") or 0)

    revenu = float(
        _scalar("SELECT SUM(prix_ht) FROM consultations WHERE paiement_statut = %s", ["paye"], 0) or 0
    )
    attente = float(
        _scalar("SELECT SUM(prix_ht) FROM consultations WHERE paiement_statut = %s", ["en_attente"], 0) or 0
    )

    msg_non_lus = int(_scalar("SELECT COUNT(*) AS n FROM contact_us WHERE is_readed = 0") or 0)
    msg_total = int(_scalar("SELECT COUNT(*) AS n FROM contact_us") or 0)

    users_actifs = int(
        _scalar(
            "SELECT COUNT(*) AS n FROM users WHERE actif = 1 AND is_active = 1 AND deleted_at IS NULL"
        )
        or 0
    )
    cutoff = (datetime.now() - timedelta(minutes=30)).strftime("%Y-%m-%d %H:%M:%S")
    sessions_en_ligne = int(
        _scalar(
            "SELECT COUNT(*) AS n FROM user_sessions WHERE is_active = 1 AND last_activity >= %s",
            [cutoff],
        )
        or 0
    )

    return {
        "visites": {
            "value": visits_now,
            "delta": _delta(visits_now, visits_prev),
            "today": visits_today,
        },
        "consultations": {
            "value": consult_now,
            "delta": _delta(consult_now, consult_prev),
            "pending": consult_pending,
            "done": consult_done,
        },
        "leads": {
            "value": inv_total + brokers_total,
            "delta": _delta(inv_now, inv_prev),
            "investors": inv_total,
            "brokers": brokers_total,
            "new_period": inv_now,
        },
        "revenus": {
            "paid": round(revenu, 2),
            "pending": round(attente, 2),
            "currency": "USD",
        },
        "messages": {"unread": msg_non_lus, "total": msg_total},
        "utilisateurs": {"active": users_actifs, "online": sessions_en_ligne},
    }


def _series(days, start, today):
    rows = _rows(
        "SELECT visit_date AS d, COUNT(*) AS n FROM visitors_logs "
        "WHERE visit_date >= %s GROUP BY visit_date",
        [start],
    )
    by_day = {str(r["d"]): int(r["n"] or 0) for r in rows}

    rows = _rows(
        "SELECT DATE(created_at) AS d, COUNT(*) AS n FROM consultations "
        "WHERE created_at >= %s GROUP BY DATE(created_at)",
        [start],
    )
    consult_by_day = {str(r["d"]): int(r["n"] or 0) for r in rows}

    rows = _rows(
        "SELECT DATE(created_at) AS d, COUNT(*) AS n FROM investors "
        "WHERE created_at >= %s GROUP BY DATE(created_at)",
        [start],
    )
    lead_by_day = {str(r["d"]): int(r["n"] or 0) for r in rows}

    labels, visits, consults, leads, ma7 = [], [], [], [], []
    buffer = []
    cursor = start
    while cursor <= today:
        key = cursor.isoformat()
        labels.append(cursor.strftime("%d/%m"))
        value = int(by_day.get(key, 0))
        visits.append(value)
        consults.append(int(consult_by_day.get(key, 0)))
        leads.append(int(lead_by_day.get(key, 0)))
        buffer.append(value)
        if len(buffer) > 7:
            buffer.pop(0)
        ma7.append(round(sum(buffer) / len(buffer), 1) if buffer else 0.0)
        cursor += timedelta(days=1)

    return {
        "labels": labels,
        "visits": visits,
        "visits_ma7": ma7,
        "consultations": consults,
        "leads": leads,
    }


def _devices(days, start):
    rows = _rows(
        "SELECT device, COUNT(*) AS n FROM visitors_logs "
        "WHERE visit_date >= %s GROUP BY device ORDER BY n DESC",
        [start],
    )
    return [
        {"label": r["device"] or "Inconnu", "n": int(r["n"] or 0)}
        for r in rows
    ]


def _top_pages(days, start):
    rows = _rows(
        "SELECT page, COUNT(*) AS n FROM visitors_logs "
        "WHERE visit_date >= %s AND page IS NOT NULL "
        "GROUP BY page ORDER BY n DESC LIMIT 8",
        [start],
    )
    out = []
    for row in rows:
        path = re.sub(r"^https?://[^/]+", "", str(row["page"] or ""), flags=re.IGNORECASE)
        if not path:
            path = "/"
        out.append({"label": path[:40], "n": int(row["n"] or 0)})
    return out


def _peak_hours(days, start):
    rows = _rows(
        "SELECT HOUR(visit_time) AS h, COUNT(*) AS n FROM visitors_logs "
        "WHERE visit_date >= %s GROUP BY HOUR(visit_time)",
        [start],
    )
    hours = [0] * 24
    for row in rows:
        hour = int(row["h"] or 0)
        if 0 <= hour < 24:
            hours[hour] = int(row["n"] or 0)
    return hours


def _consultations(days, start):
    by_statut = {
        r["statut"]: int(r["n"] or 0)
        for r in _rows("SELECT statut, COUNT(*) AS n FROM consultations GROUP BY statut")
    }
    by_paiement = {
        r["paiement_statut"]: int(r["n"] or 0)
        for r in _rows(
            "SELECT paiement_statut, COUNT(*) AS n FROM consultations GROUP BY paiement_statut"
        )
    }
    by_mode = {
        r["mode_paiement"]: int(r["n"] or 0)
        for r in _rows(
            "SELECT mode_paiement, COUNT(*) AS n FROM consultations "
            "WHERE mode_paiement IS NOT NULL GROUP BY mode_paiement"
        )
    }

    recent = _rows(
        "SELECT c.numero_consultation, c.patient_nom, c.patient_prenom, c.statut, "
        "c.paiement_statut, c.prix_ht, c.devise, c.mode_paiement, c.created_at, "
        "c.description_symptomes, m.specialite "
        "FROM consultations c "
        "LEFT JOIN medecins m ON m.id = c.medecin_id "
        "ORDER BY c.created_at DESC LIMIT 6"
    )
    for row in recent:
        symptoms = str(row.pop("description_symptomes", "") or "")
        row["resume"] = symptoms if len(symptoms) <= 70 else symptoms[:70] + "…"

    avg_row = _row(
        "SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) AS h "
        "FROM consultations "
        "WHERE statut IN ('terminee','en_cours') AND updated_at > created_at"
    )
    avg_hours = (
        round(float(avg_row["h"]), 1)
        if avg_row and avg_row.get("h") is not None
        else None
    )

    stale = _row(
        "SELECT COUNT(*) AS n, MAX(TIMESTAMPDIFF(HOUR, created_at, NOW())) AS max_h "
        "FROM consultations WHERE statut = 'en_attente'"
    )

    return {
        "by_statut": by_statut,
        "by_paiement": by_paiement,
        "by_mode": by_mode,
        "recent": recent,
        "avg_treatment_hours": avg_hours,
        "pending_count": int((stale or {}).get("n") or 0),
        "pending_max_hours": int((stale or {}).get("max_h") or 0),
    }


def _leads(days, start, today):
    inv_series = {
        str(r["d"]): int(r["n"] or 0)
        for r in _rows(
            "SELECT DATE(created_at) AS d, COUNT(*) AS n FROM investors "
            "WHERE created_at >= %s GROUP BY DATE(created_at)",
            [start],
        )
    }
    brokers_series = {
        str(r["d"]): int(r["n"] or 0)
        for r in _rows(
            "SELECT DATE(created_at) AS d, COUNT(*) AS n FROM brokers "
            "WHERE created_at >= %s GROUP BY DATE(created_at)",
            [start],
        )
    }
    recent_inv = _rows(
        "SELECT full_name, organization, email, commitment_range, timeline, created_at "
        "FROM investors ORDER BY created_at DESC LIMIT 5"
    )
    recent_brokers = _rows(
        "SELECT full_name, firm_name, email, engagement_model, created_at "
        "FROM brokers ORDER BY created_at DESC LIMIT 5"
    )
    return {
        "investors_by_day": inv_series,
        "brokers_by_day": brokers_series,
        "recent_investors": recent_inv,
        "recent_brokers": recent_brokers,
        "investors_total": int(_scalar("SELECT COUNT(*) AS n FROM investors") or 0),
        "brokers_total": int(_scalar("SELECT COUNT(*) AS n FROM brokers") or 0),
    }


def _catalogue():
    rows = _rows(
        "SELECT pc.nom AS categorie, COUNT(p.id) AS n "
        "FROM produits p LEFT JOIN produit_categories pc ON pc.id = p.categorie_id "
        "GROUP BY pc.nom"
    )
    return {
        "total": int(_scalar("SELECT COUNT(*) AS n FROM produits") or 0),
        "actifs": int(_scalar("SELECT COUNT(*) AS n FROM produits WHERE est_actif = 1") or 0),
        "certifies": int(
            _scalar("SELECT COUNT(*) AS n FROM produits WHERE est_certifie = 1") or 0
        ),
        "par_categorie": [
            {"label": r["categorie"] or "Sans catégorie", "n": int(r["n"] or 0)}
            for r in rows
        ],
    }


def _activity():
    contacts = _rows(
        "SELECT FullName, Email, Subject, is_readed, Date_creation "
        "FROM contact_us ORDER BY Date_creation DESC LIMIT 5"
    )
    users = _rows(
        "SELECT nom, prenom, email, derniere_connexion, created_at, actif "
        "FROM users ORDER BY created_at DESC LIMIT 5"
    )
    logs = _rows(
        "SELECT logs.action, logs.description, logs.niveau, logs.created_at, users.email "
        "FROM logs LEFT JOIN users ON users.id = logs.user_id "
        "ORDER BY logs.id DESC LIMIT 8"
    )
    return {
        "contacts": contacts,
        "users": users,
        "logs": logs,
        "newsletter": int(
            _scalar("SELECT COUNT(*) AS n FROM newsletter_abonnes WHERE deleted_at IS NULL") or 0
        ),
        "partenaires": int(
            _scalar("SELECT COUNT(*) AS n FROM partenaires WHERE est_actif = 1") or 0
        ),
        "faq": int(_scalar("SELECT COUNT(*) AS n FROM faq") or 0),
    }


def _insights(days, start, today, prev_start, prev_end):
    insights = []

    def add(kind, icon, title, text):
        insights.append({"type": kind, "icon": icon, "title": title, "text": text})

    visits_now = _count_between("visitors_logs", "visit_date", start, today)
    visits_prev = _count_between("visitors_logs", "visit_date", prev_start, prev_end)
    delta = _delta(visits_now, visits_prev)

    if visits_prev > 0:
        if delta >= 20:
            add(
                "success",
                "bi-graph-up-arrow",
                "Trafic en forte hausse",
                f"Le trafic du site progresse de {delta}% sur {days} jours "
                f"({visits_now} visites vs {visits_prev} sur la période précédente).",
            )
        elif delta <= -20:
            add(
                "danger",
                "bi-graph-down-arrow",
                "Trafic en baisse",
                f"Le trafic recule de {delta}% sur {days} jours "
                f"({visits_now} visites contre {visits_prev} précédemment). "
                "Une campagne de visibilité est à envisager.",
            )
        else:
            add(
                "info",
                "bi-activity",
                "Trafic stable",
                f"Le trafic reste stable sur {days} jours : {visits_now} visites "
                f"(variation de {delta}%).",
            )
    elif visits_now > 0:
        add(
            "info",
            "bi-rocket-takeoff",
            "Reprise de trafic",
            f"{visits_now} visites enregistrées sur la période, aucune activité mesurée "
            "sur la période précédente.",
        )

    hist_start = today - timedelta(days=30)
    hist = _rows(
        "SELECT visit_date, COUNT(*) AS n FROM visitors_logs "
        "WHERE visit_date >= %s AND visit_date < %s GROUP BY visit_date",
        [hist_start, today],
    )
    if len(hist) >= 5:
        values = [int(r["n"] or 0) for r in hist]
        mean = sum(values) / len(values)
        std = statistics.pstdev(values)
        today_n = int(
            _scalar("SELECT COUNT(*) AS n FROM visitors_logs WHERE visit_date = %s", [today])
            or 0
        )
        if std > 0:
            z = (today_n - mean) / std
            if z >= 2:
                add(
                    "success",
                    "bi-stars",
                    "Pic de fréquentation anormal (à la hausse)",
                    f"Aujourd'hui : {today_n} visites pour une moyenne de {round(mean, 1)} "
                    f"(écart-type {round(std, 1)}) — z-score = +{round(z, 1)}. "
                    "Pic inhabituel détecté.",
                )
            elif z <= -2 and today_n == 0 and datetime.now().hour >= 10:
                add(
                    "warning",
                    "bi-exclamation-triangle",
                    "Fréquentation atypiquement nulle",
                    f"Aucune visite aujourd'hui alors que la moyenne des 30 derniers jours "
                    f"est de {round(mean, 1)} visites/jour (z-score = {round(z, 1)}). "
                    "Vérifiez le site.",
                )

    hours = _peak_hours(days, start)
    peak = max(hours) if hours else 0
    if peak > 0:
        max_h = hours.index(peak)
        part = round(peak / max(1, sum(hours)) * 100)
        add(
            "info",
            "bi-clock-history",
            "Heure de pointe",
            f"Le pic de fréquentation se situe à {max_h:02d}h avec {part}% du trafic "
            f"de la période ({hours[max_h]} visites).",
        )

    devices = _devices(days, start)
    total_devices = sum(d["n"] for d in devices)
    for device in devices:
        if "mobile" in str(device["label"]).lower() and total_devices > 0:
            pct = round(device["n"] / total_devices * 100)
            if pct >= 60:
                add(
                    "warning",
                    "bi-phone",
                    "Audience majoritairement mobile",
                    f"{pct}% du trafic provient des mobiles — assurez-vous que les parcours "
                    "clés (contact, investisseurs, consultations) sont parfaitement adaptés au mobile.",
                )
            break

    pending = int(_scalar("SELECT COUNT(*) AS n FROM consultations WHERE statut = 'en_attente'") or 0)
    if pending > 0:
        max_wait_row = _row(
            "SELECT MAX(TIMESTAMPDIFF(HOUR, created_at, NOW())) AS h "
            "FROM consultations WHERE statut = 'en_attente'"
        )
        max_h_wait = int((max_wait_row or {}).get("h") or 0)
        add(
            "danger" if max_h_wait > 48 else "warning",
            "bi-hourglass-split",
            "Consultations en attente",
            f"{pending} dossier(s) en attente de traitement, le plus ancien depuis "
            f"{max_h_wait} heures. Un médecin devrait les prendre en charge.",
        )

    attente_paiement = int(
        _scalar("SELECT COUNT(*) AS n FROM consultations WHERE paiement_statut = 'en_attente'") or 0
    )
    paye = int(
        _scalar("SELECT COUNT(*) AS n FROM consultations WHERE paiement_statut = 'paye'") or 0
    )
    if attente_paiement > 0 and paye == 0:
        add(
            "warning",
            "bi-cash-coin",
            "Aucun paiement encaissé",
            f"{attente_paiement} consultation(s) avec paiement en attente (virement bancaire). "
            "Relance des dossiers recommandée.",
        )

    unread = int(_scalar("SELECT COUNT(*) AS n FROM contact_us WHERE is_readed = 0") or 0)
    if unread > 0:
        add(
            "danger" if unread >= 3 else "info",
            "bi-envelope-exclamation",
            "Messages en attente de réponse",
            f"{unread} message(s) du formulaire de contact non traité(s). "
            "La rapidité de réponse est un levier de conversion majeur.",
        )

    inv_total = int(_scalar("SELECT COUNT(*) AS n FROM investors") or 0)
    new_inv = _count_between("investors", "created_at", start, today)
    if inv_total > 0:
        brokers_total = int(_scalar("SELECT COUNT(*) AS n FROM brokers") or 0)
        add(
            "info",
            "bi-funnel",
            "Pipeline investisseurs",
            f"{inv_total} investisseurs enregistrés au total, dont {new_inv} sur les "
            f"{days} derniers jours. {brokers_total} courtier(s) également référencé(s).",
        )

    orphan_cutoff = (datetime.now() - timedelta(hours=2)).strftime("%Y-%m-%d %H:%M:%S")
    orphan_sessions = int(
        _scalar(
            "SELECT COUNT(*) AS n FROM user_sessions "
            "WHERE is_active = 1 AND last_activity < %s",
            [orphan_cutoff],
        )
        or 0
    )
    if orphan_sessions > 0:
        add(
            "info",
            "bi-shield-check",
            "Sessions inactives",
            f"{orphan_sessions} session(s) suivie(s) inactives depuis plus de 2 heures "
            "mais encore marquées actives — nettoyage possible depuis le module Sessions.",
        )

    no_login = int(
        _scalar("SELECT COUNT(*) AS n FROM users WHERE derniere_connexion IS NULL") or 0
    )
    if no_login > 0:
        add(
            "warning",
            "bi-person-x",
            "Comptes jamais connectés",
            f"{no_login} utilisateur(s) n'ont jamais ouvert de session — vérifiez qu'ils "
            "ont bien reçu leurs accès.",
        )

    produits = int(_scalar("SELECT COUNT(*) AS n FROM produits") or 0)
    cert = int(_scalar("SELECT COUNT(*) AS n FROM produits WHERE est_certifie = 1") or 0)
    if produits > 0 and cert < produits:
        add(
            "info",
            "bi-patch-check",
            "Certification produit",
            f"{cert} produit(s) certifié(s) sur {produits} — {produits - cert} "
            "produit(s) sans certification affichée.",
        )

    return insights


def _normalize(value):
    if isinstance(value, Decimal):
        return float(value)
    if isinstance(value, datetime):
        return value.strftime("%Y-%m-%d %H:%M:%S")
    if isinstance(value, date):
        return value.isoformat()
    if isinstance(value, time):
        return value.strftime("%H:%M:%S")
    if isinstance(value, dict):
        return {key: _normalize(item) for key, item in value.items()}
    if isinstance(value, (list, tuple)):
        return [_normalize(item) for item in value]
    return value


def dashboard_payload(days=30):
    days = max(7, min(90, int(days or 30)))
    today = date.today()
    start = today - timedelta(days=days - 1)
    prev_start = today - timedelta(days=2 * days - 1)
    prev_end = today - timedelta(days=days)

    payload = {
        "meta": {
            "days": days,
            "start": start.isoformat(),
            "end": today.isoformat(),
            "generated_at": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            "app": get_setting("nom_app", "African Green Farmers Limited"),
        },
        "kpis": _kpis(days, start, today, prev_start, prev_end),
        "series": _series(days, start, today),
        "devices": _devices(days, start),
        "pages": _top_pages(days, start),
        "hours": _peak_hours(days, start),
        "consultations": _consultations(days, start),
        "leads": _leads(days, start, today),
        "catalogue": _catalogue(),
        "activity": _activity(),
        "insights": _insights(days, start, today, prev_start, prev_end),
    }
    return _normalize(payload)
