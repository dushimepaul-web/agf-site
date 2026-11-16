from django.utils import timezone

from core.models import ContactMessage, Faq, Partenaire, SocialLink


class PublicError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


def _clean(value):
    return str(value or "").strip()


def _int(value, default=0):
    try:
        return int(value)
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


def contacts(limit=50, offset=0):
    limit = max(1, min(_int(limit, 50), 200))
    offset = max(0, _int(offset))
    total = ContactMessage.objects.count()
    rows = ContactMessage.objects.order_by("-date_creation")[offset : offset + limit]
    return {"total": total, "data": [_dict(r) for r in rows]}


def get_contact(contact_id):
    row = ContactMessage.objects.filter(id_contact=contact_id).first()
    if not row:
        raise PublicError("Message non trouvé", 404)
    return _dict(row)


def mark_contact_read(contact_id):
    row = ContactMessage.objects.filter(id_contact=contact_id).first()
    if not row:
        raise PublicError("Message non trouvé", 404)
    ContactMessage.objects.filter(id_contact=contact_id).update(is_readed=1)
    return row


def delete_contact(contact_id):
    row = ContactMessage.objects.filter(id_contact=contact_id).first()
    if not row:
        raise PublicError("Message non trouvé", 404)
    row.delete()
    return row


def faqs():
    return [_dict(r) for r in Faq.objects.order_by("ordre", "id_faq")]


def get_faq(faq_id):
    row = Faq.objects.filter(id_faq=faq_id).first()
    if not row:
        raise PublicError("FAQ non trouvée", 404)
    return _dict(row)


def create_faq(data):
    data = data or {}
    question = _clean(data.get("question"))
    reponse = _clean(data.get("reponse"))
    if not question or not reponse:
        raise PublicError("Question et réponse obligatoires")
    return Faq.objects.create(
        question=question,
        reponse=reponse,
        categorie=_clean(data.get("categorie")) or "general",
        ordre=_int(data.get("ordre")),
        est_publiee=_int(data.get("est_publiee", 1), 1),
    )


def update_faq(faq_id, data):
    data = data or {}
    row = Faq.objects.filter(id_faq=faq_id).first()
    if not row:
        raise PublicError("FAQ non trouvée", 404)
    update = {}
    if "question" in data:
        question = _clean(data.get("question"))
        if not question:
            raise PublicError("La question est obligatoire")
        update["question"] = question
    if "reponse" in data:
        reponse = _clean(data.get("reponse"))
        if not reponse:
            raise PublicError("La réponse est obligatoire")
        update["reponse"] = reponse
    if "categorie" in data:
        update["categorie"] = _clean(data.get("categorie")) or "general"
    if "ordre" in data:
        update["ordre"] = _int(data.get("ordre"))
    if "est_publiee" in data:
        update["est_publiee"] = _int(data.get("est_publiee"))
    if not update:
        raise PublicError("Aucune donnée à modifier")
    Faq.objects.filter(id_faq=faq_id).update(**update)
    return row, list(update.keys())


def delete_faq(faq_id):
    row = Faq.objects.filter(id_faq=faq_id).first()
    if not row:
        raise PublicError("FAQ non trouvée", 404)
    row.delete()
    return row


def partners():
    qs = Partenaire.objects.filter(deleted_at__isnull=True).order_by("-created_at")
    return [_dict(r) for r in qs]


def get_partner(partner_id):
    row = Partenaire.objects.filter(id_partenaire=partner_id, deleted_at__isnull=True).first()
    if not row:
        raise PublicError("Partenaire non trouvé", 404)
    return _dict(row)


def create_partner(data):
    data = data or {}
    nom = _clean(data.get("nom"))
    kind = _clean(data.get("type_partenaire"))
    if not nom:
        raise PublicError("Le nom est obligatoire")
    if not kind:
        raise PublicError("Le type est obligatoire")
    return Partenaire.objects.create(
        type_partenaire=kind,
        nom=nom,
        logo_url=_clean(data.get("logo_url")) or None,
        description=_clean(data.get("description")) or None,
        pays=_clean(data.get("pays")) or None,
        site_web=_clean(data.get("site_web")) or None,
        niveau_partenariat=_clean(data.get("niveau_partenariat")) or "commercial",
        date_debut=_clean(data.get("date_debut")) or None,
        est_actif=_int(data.get("est_actif", 1), 1),
        created_at=timezone.now(),
        updated_at=timezone.now(),
    )


def update_partner(partner_id, data):
    data = data or {}
    row = Partenaire.objects.filter(id_partenaire=partner_id, deleted_at__isnull=True).first()
    if not row:
        raise PublicError("Partenaire non trouvé", 404)
    update = {}
    for key in ("nom", "type_partenaire"):
        if key in data:
            value = _clean(data.get(key))
            if not value:
                raise PublicError("Nom et type sont obligatoires")
            update[key] = value
    for key in ("logo_url", "description", "pays", "site_web", "niveau_partenariat", "date_debut"):
        if key in data:
            update[key] = _clean(data.get(key)) or None
    if "est_actif" in data:
        update["est_actif"] = _int(data.get("est_actif"))
    if not update:
        raise PublicError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    Partenaire.objects.filter(id_partenaire=partner_id).update(**update)
    return row, list(update.keys())


def delete_partner(partner_id):
    row = Partenaire.objects.filter(id_partenaire=partner_id, deleted_at__isnull=True).first()
    if not row:
        raise PublicError("Partenaire non trouvé", 404)
    row.delete()
    return row


def social_links():
    return [_dict(r) for r in SocialLink.objects.order_by("display_order", "id")]


def get_social_link(link_id):
    row = SocialLink.objects.filter(id=link_id).first()
    if not row:
        raise PublicError("Lien non trouvé", 404)
    return _dict(row)


def create_social_link(data):
    data = data or {}
    platform = _clean(data.get("platform"))
    url = _clean(data.get("url"))
    if not platform or not url:
        raise PublicError("Plateforme et URL obligatoires")
    if SocialLink.objects.filter(platform=platform).exists():
        raise PublicError("Cette plateforme existe déjà")
    return SocialLink.objects.create(
        platform=platform,
        label=_clean(data.get("label")) or platform,
        url=url,
        icon_class=_clean(data.get("icon_class")) or "bi",
        icon_name=_clean(data.get("icon_name")),
        display_order=_int(data.get("display_order")),
        is_active=_int(data.get("is_active", 1), 1),
        target_blank=_int(data.get("target_blank", 1), 1),
        created_at=timezone.now(),
        updated_at=timezone.now(),
    )


def update_social_link(link_id, data):
    data = data or {}
    row = SocialLink.objects.filter(id=link_id).first()
    if not row:
        raise PublicError("Lien non trouvé", 404)
    update = {}
    if "platform" in data:
        platform = _clean(data.get("platform"))
        if not platform:
            raise PublicError("La plateforme est obligatoire")
        if SocialLink.objects.filter(platform=platform).exclude(id=row.id).exists():
            raise PublicError("Cette plateforme existe déjà")
        update["platform"] = platform
    if "url" in data:
        url = _clean(data.get("url"))
        if not url:
            raise PublicError("L'URL est obligatoire")
        update["url"] = url
    if "label" in data:
        update["label"] = _clean(data.get("label")) or now_platform(row, update)
    if "icon_class" in data:
        update["icon_class"] = _clean(data.get("icon_class")) or "bi"
    if "icon_name" in data:
        update["icon_name"] = _clean(data.get("icon_name"))
    if "display_order" in data:
        update["display_order"] = _int(data.get("display_order"))
    if "is_active" in data:
        update["is_active"] = _int(data.get("is_active"))
    if "target_blank" in data:
        update["target_blank"] = _int(data.get("target_blank"))
    if not update:
        raise PublicError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    SocialLink.objects.filter(id=link_id).update(**update)
    return row, list(update.keys())


def now_platform(row, update):
    return update.get("platform") or row.platform


def delete_social_link(link_id):
    row = SocialLink.objects.filter(id=link_id).first()
    if not row:
        raise PublicError("Lien non trouvé", 404)
    row.delete()
    return row
