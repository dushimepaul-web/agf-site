import json
import re
import uuid as uuidlib
from datetime import timedelta
from pathlib import Path
from urllib.parse import quote

from django.conf import settings
from django.db import transaction
from django.db.models import Count, Max, Min
from django.utils import timezone

from core.helpers import generate_uuid, get_setting, media_url
from core.models import (
    Consultation,
    ConsultationMedia,
    HoraireMedecin,
    Medecin,
    ModePaiement,
    Pays,
    User,
)


class ConsultationError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


JOUR_ORDER = {
    "lundi": 1, "mardi": 2, "mercredi": 3, "jeudi": 4,
    "vendredi": 5, "samedi": 6, "dimanche": 7,
}
VALID_STATUS = {"en_attente", "en_cours", "terminee", "annulee"}


def _clean(value):
    return str(value or "").strip()


def _int(value, default=0):
    try:
        return int(value)
    except (TypeError, ValueError):
        return default


def _float(value, default=0.0):
    try:
        return float(value)
    except (TypeError, ValueError):
        return default


def _dict(row):
    data = {}
    for field in row._meta.fields:
        value = getattr(row, field.attname)
        if hasattr(value, "isoformat"):
            value = value.isoformat()
        data[field.attname] = value
    return data


def _medecin_payload(row):
    user = User.objects.filter(id=row.user_id).first()
    photo = (user.photo if user else "") or ""
    return {
        "id": row.id,
        "uuid": row.uuid,
        "user_id": row.user_id,
        "specialite": row.specialite,
        "numero_licence": row.numero_licence,
        "annees_experience": row.annees_experience,
        "honoraires_consultation": row.honoraires_consultation,
        "currency": row.currency,
        "USD_EUR_Equivalent_en_BIF": row.USD_EUR_Equivalent_en_BIF,
        "prix_pour_residant_burundi": row.prix_pour_residant_burundi,
        "note_moyenne": row.note_moyenne,
        "nombre_avis": row.nombre_avis,
        "bio": row.bio,
        "diplomes": row.diplomes,
        "langues_parlees": row.langues_parlees,
        "disponibilite": row.disponibilite,
        "est_disponible": row.est_disponible,
        "actif": row.actif,
        "prenom": user.prenom if user else "",
        "nom": user.nom if user else "",
        "email": user.email if user else "",
        "telephone": user.telephone if user else "",
        "photo": photo,
        "photo_url": media_url(photo, "assets/frontend/img/default-doctor.jpg") if photo else "",
        "user_nom": (f"{user.prenom} {user.nom}".strip() if user else ""),
        "user_email": user.email if user else "",
    }


def my_medecin_id(session):
    if str(session.get("role_code") or "").upper() != "MEDECIN":
        return None
    row = Medecin.objects.filter(user_id=session.get("id_utilisateur")).first()
    return row.id if row else None


def medecins(only_active=True):
    qs = Medecin.objects.all()
    if only_active:
        qs = qs.filter(actif=1)
    rows = [_medecin_payload(r) for r in qs]
    rows.sort(key=lambda x: (x["nom"] or "").lower())
    return rows


def get_medecin(medecin_id):
    row = Medecin.objects.filter(id=medecin_id).first()
    if not row:
        raise ConsultationError("Expert non trouvé", 404)
    return _medecin_payload(row)


def get_medecin_by_uuid(medecin_uuid):
    row = Medecin.objects.filter(uuid=medecin_uuid).first()
    if not row:
        raise ConsultationError("Expert non trouvé", 404)
    return _medecin_payload(row)


def get_horaires(medecin_id):
    rows = HoraireMedecin.objects.filter(medecin_id=medecin_id, est_actif=1)
    data = [_dict(r) for r in rows]
    data.sort(key=lambda r: (JOUR_ORDER.get(r.get("jour_semaine"), 99), str(r.get("heure_debut") or "")))
    return data


def create_medecin(data):
    data = data or {}
    user_id = _int(data.get("user_id"))
    specialite = _clean(data.get("specialite"))
    if user_id <= 0 or not specialite:
        raise ConsultationError("Utilisateur et spécialité obligatoires")
    if not User.objects.filter(id=user_id).exists():
        raise ConsultationError("Utilisateur invalide")
    if Medecin.objects.filter(user_id=user_id).exists():
        raise ConsultationError("Cet utilisateur est déjà enregistré comme expert")
    return Medecin.objects.create(
        uuid=generate_uuid(),
        user_id=user_id,
        specialite=specialite,
        numero_licence=_clean(data.get("numero_licence")) or None,
        annees_experience=_int(data.get("annees_experience")),
        honoraires_consultation=_float(data.get("honoraires_consultation")),
        bio=_clean(data.get("bio")) or None,
        diplomes=_clean(data.get("diplomes")) or None,
        langues_parlees=_clean(data.get("langues_parlees")) or None,
        actif=_int(data.get("actif", 1), 1),
        created_at=timezone.now(),
        updated_at=timezone.now(),
    )


def update_medecin(medecin_uuid, data):
    data = data or {}
    row = Medecin.objects.filter(uuid=medecin_uuid).first()
    if not row:
        raise ConsultationError("Expert non trouvé", 404)
    update = {}
    if "specialite" in data:
        specialite = _clean(data.get("specialite"))
        if not specialite:
            raise ConsultationError("La spécialité est obligatoire")
        update["specialite"] = specialite
    for key in ("numero_licence", "bio", "diplomes", "langues_parlees"):
        if key in data:
            update[key] = _clean(data.get(key)) or None
    if "annees_experience" in data:
        update["annees_experience"] = _int(data.get("annees_experience"))
    if "honoraires_consultation" in data:
        update["honoraires_consultation"] = _float(data.get("honoraires_consultation"))
    if "actif" in data:
        update["actif"] = _int(data.get("actif"))
    if not update:
        raise ConsultationError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    Medecin.objects.filter(id=row.id).update(**update)
    return row, list(update.keys())


def delete_medecin(medecin_uuid):
    row = Medecin.objects.filter(uuid=medecin_uuid).first()
    if not row:
        raise ConsultationError("Expert non trouvé", 404)
    if Consultation.objects.filter(medecin_id=row.id).exists():
        raise ConsultationError("Impossible : des consultations sont rattachées à cet expert")
    with transaction.atomic():
        HoraireMedecin.objects.filter(medecin_id=row.id).delete()
        row.delete()
    return row


def _consultation_payload(row, medecin_map):
    data = _dict(row)
    med = medecin_map.get(row.medecin_id)
    data["medecin_nom"] = f"{med['prenom']} {med['nom']}".strip() if med else ""
    data["specialite"] = med["specialite"] if med else ""
    return data


def consultations(session):
    medecin_map = {m["id"]: m for m in medecins(False)}
    qs = Consultation.objects.all()
    role = str(session.get("role_code") or "").upper()
    if role == "MEDECIN":
        doctor_id = my_medecin_id(session)
        if doctor_id:
            qs = qs.filter(medecin_id=doctor_id)
    return [_consultation_payload(r, medecin_map) for r in qs.order_by("-created_at")]


def get_consultation(consultation_uuid, session=None):
    row = Consultation.objects.filter(uuid=consultation_uuid).first()
    if not row:
        raise ConsultationError("Consultation non trouvée", 404)
    if session:
        role = str(session.get("role_code") or "").upper()
        if role == "MEDECIN":
            doctor_id = my_medecin_id(session)
            if doctor_id and row.medecin_id != doctor_id:
                raise ConsultationError("Accès refusé", 403)
    med = Medecin.objects.filter(id=row.medecin_id).first()
    data = _dict(row)
    data["medecin_nom"] = ""
    data["specialite"] = ""
    if med:
        payload = _medecin_payload(med)
        data["medecin_nom"] = f"{payload['prenom']} {payload['nom']}".strip()
        data["specialite"] = payload["specialite"]
    data["medias"] = [_dict(m) for m in ConsultationMedia.objects.filter(consultation_id=row.id).order_by("ordre")]
    return data


def update_consultation(consultation_uuid, data, session=None):
    data = data or {}
    row = Consultation.objects.filter(uuid=consultation_uuid).first()
    if not row:
        raise ConsultationError("Consultation non trouvée", 404)
    if session:
        role = str(session.get("role_code") or "").upper()
        if role == "MEDECIN":
            doctor_id = my_medecin_id(session)
            if doctor_id and row.medecin_id != doctor_id:
                raise ConsultationError("Accès refusé", 403)
    update = {}
    if "statut" in data:
        statut = _clean(data.get("statut"))
        if statut not in VALID_STATUS:
            raise ConsultationError("Statut invalide")
        update["statut"] = statut
    if "whatsapp_envoye" in data:
        update["whatsapp_envoye"] = _int(data.get("whatsapp_envoye"))
    for key in ("diagnostic", "traitement", "ordonnances", "notes_medecin", "examens_demandes"):
        if key in data:
            update[key] = _clean(data.get(key)) or None
    if not update:
        raise ConsultationError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    Consultation.objects.filter(id=row.id).update(**update)
    return row, list(update.keys())


def delete_consultation(consultation_uuid):
    row = Consultation.objects.filter(uuid=consultation_uuid).first()
    if not row:
        raise ConsultationError("Consultation non trouvée", 404)
    with transaction.atomic():
        ConsultationMedia.objects.filter(consultation_id=row.id).delete()
        row.delete()
    return row


def patients(session):
    qs = Consultation.objects.all()
    role = str(session.get("role_code") or "").upper()
    if role == "MEDECIN":
        doctor_id = my_medecin_id(session)
        if doctor_id:
            qs = qs.filter(medecin_id=doctor_id)
    return list(
        qs.values("patient_nom", "patient_prenom", "patient_poids", "patient_taille", "patient_adresse", "whatsapppatient", "country_id", "age")
        .annotate(nb_consultations=Count("id"), premiere_consultation=Min("created_at"), derniere_consultation=Max("created_at"))
        .order_by("-derniere_consultation")
    )


def patient_detail(nom, prenom, whatsapp=None, session=None):
    if not nom or not prenom:
        raise ConsultationError("Paramètres manquants")
    qs = Consultation.objects.filter(patient_nom=nom, patient_prenom=prenom)
    if whatsapp:
        qs = qs.filter(whatsapppatient=whatsapp)
    role = str(session.get("role_code") or "").upper() if session else ""
    if role == "MEDECIN":
        doctor_id = my_medecin_id(session)
        if doctor_id:
            qs = qs.filter(medecin_id=doctor_id)
    medecin_map = {m["id"]: m for m in medecins(False)}
    return [_consultation_payload(r, medecin_map) for r in qs.order_by("-created_at")]


DOCUMENT_EXTENSIONS = {".gif", ".jpg", ".jpeg", ".png", ".webp", ".pdf", ".doc", ".docx"}
PAYMENT_EXTENSIONS = {".jpg", ".jpeg", ".png", ".pdf"}
DEFAULT_WHATSAPP = "+260 777 844 844"


def _truthy(value):
    return str(value or "").lower() in {"1", "true", "on", "yes", "oui"}


def _upload_consultation_file(upload, folder_name, allowed_extensions, label="Fichier"):
    if not upload:
        raise ConsultationError(f"{label} invalide")
    extension = Path(upload.name or "").suffix.lower()
    if extension not in allowed_extensions:
        raise ConsultationError(f"{label} : format non autorisé")
    content = upload.read()
    if len(content) > 5 * 1024 * 1024:
        raise ConsultationError(f"{label} : fichier trop volumineux (max 5 Mo)")
    folder = Path(settings.PROJECT_ROOT) / "attachments" / folder_name
    folder.mkdir(parents=True, exist_ok=True)
    filename = f"{uuidlib.uuid4().hex}{extension}"
    (folder / filename).write_bytes(content)
    return f"attachments/{folder_name}/{filename}", filename


def _uploads(files, field):
    if not files:
        return []
    if hasattr(files, "getlist"):
        return [item for item in files.getlist(field) if item]
    value = files.get(field)
    if isinstance(value, (list, tuple)):
        return [item for item in value if item]
    return [value] if value else []


def payment_methods():
    rows = list(ModePaiement.objects.filter(actif=1).order_by("nom"))
    if not rows:
        return [{"id": 0, "nom": "Paiement à la consultation", "description": "", "numero_compte": "", "nom_compte": ""}]
    return [
        {
            "id": row.id,
            "nom": row.nom,
            "description": row.description or "",
            "numero_compte": row.numero_compte or "",
            "nom_compte": row.nom_compte or "",
        }
        for row in rows
    ]


def countries_for_form(query):
    query = _clean(query)
    if len(query) < 2:
        return []
    rows = Pays.objects.filter(pays__icontains=query).order_by("pays")[:10]
    return [
        {
            "id": row.id,
            "pays": row.pays,
            "code_iso2": row.iso2 or "",
            "phone_code": row.telephone_code or "",
            "currency_code": row.currency_code or "",
        }
        for row in rows
    ]


def _resolve_country(data):
    country_id = _int(data.get("country_id") or data.get("id_pays"))
    if country_id and Pays.objects.filter(id=country_id).exists():
        return country_id
    country = _clean(data.get("country") or data.get("pays"))
    if country:
        row = Pays.objects.filter(pays__iexact=country).first()
        if row:
            return row.id
    raise ConsultationError("Pays de résidence invalide")


def _resolve_doctor(data):
    medecin_uuid = _clean(data.get("doctor_uuid"))
    if medecin_uuid:
        row = Medecin.objects.filter(uuid=medecin_uuid, actif=1).first()
        if not row:
            raise ConsultationError("L'expert sélectionné n'est plus disponible")
        return row
    medecin_id = _int(data.get("medecin_id") or data.get("doctor_id"))
    if medecin_id:
        row = Medecin.objects.filter(id=medecin_id, actif=1).first()
        if not row:
            raise ConsultationError("L'expert sélectionné n'est plus disponible")
        return row
    row = Medecin.objects.filter(actif=1).order_by("id").first()
    if not row:
        raise ConsultationError("Aucun expert disponible")
    return row


def _patient_name(data):
    full_name = _clean(data.get("full_name"))
    if full_name:
        parts = re.split(r"\s+", full_name, maxsplit=1)
        return full_name, parts[0], parts[1] if len(parts) > 1 else ""
    prenom = _clean(data.get("prenom"))
    nom = _clean(data.get("nom"))
    return f"{prenom} {nom}".strip(), prenom, nom


def _patient_payload(data, strict=False):
    full_name, patient_prenom, patient_nom = _patient_name(data)
    if len(full_name) < 3 or len(full_name) > 100:
        raise ConsultationError("Le nom complet doit contenir entre 3 et 100 caractères")
    age = _int(data.get("age"))
    if age <= 0 or age > 120:
        raise ConsultationError("L'âge doit être compris entre 1 et 120 ans")
    weight = _float(data.get("weight") or data.get("poids"))
    if weight <= 0 or weight >= 300:
        raise ConsultationError("Le poids doit être compris entre 0 et 300 kg")
    height = _float(data.get("height") or data.get("taille"))
    if height <= 50 or height >= 251:
        raise ConsultationError("La taille doit être comprise entre 51 et 250 cm")
    whatsapp = _clean(data.get("whatsapppatient") or data.get("whatsapp"))
    if not whatsapp or len(whatsapp) > 20:
        raise ConsultationError("Le numéro WhatsApp est obligatoire (20 caractères maximum)")
    symptoms = _clean(data.get("symptoms") or data.get("description_symptomes"))
    if len(symptoms) < 20 or len(symptoms) > 5000:
        raise ConsultationError("La condition doit contenir entre 20 et 5000 caractères")
    duration = _clean(data.get("symptoms_duration") or data.get("duree_symptomes"))
    previous = _clean(data.get("previous_consultation") or data.get("consultation_precedente")).lower()
    if previous not in {"", "yes", "no"}:
        raise ConsultationError("Première consultation invalide")
    payment_method = _clean(data.get("payment_method") or data.get("mode_paiement"))
    if not payment_method:
        raise ConsultationError("Le mode de paiement est obligatoire")
    if len(payment_method) > 100:
        raise ConsultationError("Mode de paiement invalide")
    if strict and not _truthy(data.get("terms")):
        raise ConsultationError("Vous devez accepter les conditions générales")
    return {
        "full_name": full_name,
        "patient_prenom": patient_prenom,
        "patient_nom": patient_nom,
        "age": age,
        "weight": weight,
        "height": height,
        "whatsapp": whatsapp,
        "symptoms": symptoms,
        "duration": duration,
        "previous": previous,
        "payment_method": payment_method,
        "country_id": _resolve_country(data),
        "medecin": _resolve_doctor(data),
        "prix": _float(data.get("consultation_prix"), 50.0) or 50.0,
        "devise": _clean(data.get("consultation_devise") or data.get("devise")) or "USD",
    }


def public_submit(data, files=None, request=None, strict=False):
    data = data or {}
    payload = _patient_payload(data, strict=strict)
    medecin = payload["medecin"]
    saved_paths = []
    try:
        medical_paths = []
        medical_names = []
        for upload in _uploads(files, "medical_docs") + _uploads(files, "medical_docs[]"):
            path, name = _upload_consultation_file(upload, "Consultations", DOCUMENT_EXTENSIONS, "Document médical")
            saved_paths.append(path)
            medical_paths.append(path)
            medical_names.append(name)
        prescription_paths = []
        prescription_names = []
        for upload in _uploads(files, "prescriptions") + _uploads(files, "prescriptions[]"):
            path, name = _upload_consultation_file(upload, "Consultations", DOCUMENT_EXTENSIONS, "Ordonnance")
            saved_paths.append(path)
            prescription_paths.append(path)
            prescription_names.append(name)
        payment_proof = ""
        for upload in _uploads(files, "payment_proof"):
            payment_proof, _ = _upload_consultation_file(upload, "Payments", PAYMENT_EXTENSIONS, "Preuve de paiement")
            saved_paths.append(payment_proof)

        now = timezone.now()
        numero = f"AGF-{now.strftime('%Y%m%d')}-{now.strftime('%H%M%S')}"
        patient_id = None
        if request is not None:
            patient_id = request.session.get("id_utilisateur")
        with transaction.atomic():
            row = Consultation.objects.create(
                uuid=generate_uuid(),
                numero_consultation=numero,
                patient_id=patient_id,
                medecin_id=medecin.id,
                type="whatsapp",
                patient_nom=payload["patient_nom"],
                patient_prenom=payload["patient_prenom"],
                patient_poids=str(payload["weight"]),
                patient_taille=str(payload["height"]),
                poids=str(payload["weight"]),
                taille=str(payload["height"]),
                country_id=payload["country_id"],
                age=payload["age"],
                whatsapppatient=payload["whatsapp"],
                description_symptomes=payload["symptoms"],
                duree_symptomes=payload["duration"] or None,
                consultation_precedente=payload["previous"] or None,
                examens_demandes=json.dumps(medical_names) if medical_names else None,
                ordonnances=json.dumps(prescription_names) if prescription_names else None,
                date_souhaitee=now + timedelta(days=7),
                statut="en_attente",
                honoraires_consultation=0,
                prix_ht=payload["prix"],
                devise=payload["devise"],
                tva=0,
                paiement_statut="paye" if payment_proof else "en_attente",
                mode_paiement=payload["payment_method"],
                preuve_paiement=payment_proof or None,
                ip_creation=request.META.get("REMOTE_ADDR") if request is not None else None,
                created_at=now,
                updated_at=now,
            )
            for path in medical_paths:
                ConsultationMedia.objects.create(
                    consultation_id=row.id,
                    fichier_url=path,
                    type="medical",
                    ordre=0,
                    created_at=now,
                )
            for path in prescription_paths:
                ConsultationMedia.objects.create(
                    consultation_id=row.id,
                    fichier_url=path,
                    type="prescription",
                    ordre=0,
                    created_at=now,
                )
        return row
    except Exception:
        for item in saved_paths:
            try:
                (Path(settings.PROJECT_ROOT) / item).unlink(missing_ok=True)
            except OSError:
                pass
        raise


def patient_whatsapp_url(row):
    medecin = Medecin.objects.filter(id=row.medecin_id).first()
    payload = _medecin_payload(medecin) if medecin else {}
    number = "".join(ch for ch in str(get_setting("whatsapp_number", DEFAULT_WHATSAPP)) if ch.isdigit())
    if not number:
        return ""
    country = Pays.objects.filter(id=row.country_id).first() if row.country_id else None
    message = "*DEMANDE DE CONSULTATION - A.G.F*\n\n"
    message += "*INFORMATIONS PATIENT*\n"
    message += f"Nom : {row.patient_prenom or ''} {row.patient_nom or ''}\n"
    message += f"WhatsApp : {row.whatsapppatient or ''}\n"
    created_at = row.created_at or timezone.now()
    if timezone.is_aware(created_at):
        created_at = timezone.localtime(created_at)
    message += f"Date : {created_at.strftime('%d/%m/%Y %H:%M')}\n\n"
    message += "*DÉTAILS DE LA CONSULTATION*\n"
    message += f"N° suivi : {row.numero_consultation}\n"
    message += f"Âge : {row.age or ''} ans\n"
    message += f"Pays : {country.pays if country else ''}\n"
    message += f"Poids : {row.patient_poids or ''} kg | Taille : {row.patient_taille or ''} cm\n"
    message += f"Condition : {row.description_symptomes or ''}\n"
    if row.duree_symptomes:
        message += f"Durée : {row.duree_symptomes}\n"
    message += "\n*Service Fee*\n"
    message += f"Expert : Dr. {payload.get('prenom', '')} {payload.get('nom', '')}\n"
    message += f"Montant : {row.prix_ht or 0} {row.devise or ''}\n"
    for field in ("examens_demandes", "ordonnances"):
        try:
            names = json.loads(getattr(row, field) or "[]")
        except (TypeError, ValueError):
            names = []
        for name in names:
            message += f"Document : /attachments/Consultations/{name}\n"
    if row.preuve_paiement:
        message += f"Preuve de paiement : /{row.preuve_paiement}\n"
    else:
        message += "\n*NB — Paiement en attente* : la preuve sera envoyée via WhatsApp.\n"
    return f"https://wa.me/{number}?text={quote(message)}"


def save_media(upload):
    if not upload:
        raise ConsultationError("Aucun fichier envoyé")
    allowed = {"image/jpeg": ".jpg", "image/png": ".png", "image/gif": ".gif", "image/webp": ".webp"}
    ext = allowed.get((upload.content_type or "").lower())
    if not ext:
        raise ConsultationError("Type non autorisé")
    content = upload.file.read()
    if len(content) > 5 * 1024 * 1024:
        raise ConsultationError("Fichier trop volumineux (max 5Mo)")
    folder = Path(settings.PROJECT_ROOT) / "attachments" / "Consultations"
    folder.mkdir(parents=True, exist_ok=True)
    filename = f"{uuidlib.uuid4().hex}{ext}"
    (folder / filename).write_bytes(content)
    return f"attachments/Consultations/{filename}", filename


def add_media(data):
    data = data or {}
    consultation_id = _int(data.get("consultation_id"))
    fichier_url = _clean(data.get("fichier_url"))
    if not consultation_id or not fichier_url:
        raise ConsultationError("Données incomplètes")
    if not Consultation.objects.filter(id=consultation_id).exists():
        raise ConsultationError("Consultation non trouvée", 404)
    return ConsultationMedia.objects.create(
        consultation_id=consultation_id,
        fichier_url=fichier_url,
        type=_clean(data.get("type")) or "medical",
        ordre=_int(data.get("ordre")),
        created_at=timezone.now(),
    )


def whatsapp_url(consultation_id):
    row = Consultation.objects.filter(id=consultation_id).first()
    if not row:
        raise ConsultationError("Consultation non trouvée", 404)
    med = Medecin.objects.filter(id=row.medecin_id).first()
    payload = _medecin_payload(med) if med else {}
    whatsapp = payload.get("telephone") or ""
    msg = "Nouvelle consultation\n"
    msg += f"Expert: Dr. {payload.get('prenom', '')} {payload.get('nom', '')}\n"
    msg += f"Patient: {row.patient_prenom or ''} {row.patient_nom or ''}\n"
    if row.patient_poids:
        msg += f"Poids: {row.patient_poids}\n"
    if row.patient_taille:
        msg += f"Taille: {row.patient_taille}\n"
    if row.patient_adresse:
        msg += f"Adresse: {row.patient_adresse}\n"
    msg += f"Symptômes: {row.description_symptomes or ''}\n"
    if row.duree_symptomes:
        msg += f"Durée: {row.duree_symptomes}\n"
    number = "".join(ch for ch in str(whatsapp) if ch.isdigit())
    return f"https://wa.me/{number}?text={quote(msg)}", whatsapp
