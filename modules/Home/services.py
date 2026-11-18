import mimetypes
import random
import re
from pathlib import Path

from django.conf import settings as django_settings
from django.db.models import Q
from django.http import FileResponse
from django.utils import timezone
from django.utils.html import strip_tags
from django.utils.text import Truncator

from core.helpers import media_url
from core.models import (
    Broker,
    ContactMessage,
    GalerieMedia,
    Investor,
    MediaView,
    NewsletterAbonne,
    Partenaire,
    Pays,
    Produit,
    Setting,
    SocialLink,
    UniteAffaire,
    VisitorLog,
)


SETTING_DEFAULTS = {
    "site_name": "African Green Farmers Limited",
    "nom_entreprise": "African Green Farmers Limited",
    "span_site_name": "Agro-Industrial",
    "slogan": "Zambian Agro-Industrial Investment",
    "site_logo": "attachments/Parametres/african-green-farmers-logo.jpg",
    "favicon_ico": "attachments/Parametres/african-green-farmers-logo.jpg",
    "home_about_image": "attachments/Parametres/agf-limited-certificate.jpg",
    "site_phone": "+260 777 844 844",
    "site_phone_alt": "+260 764 346 468",
    "whatsapp_number": "+260 777 844 844",
    "site_email": "agfcompany2026@gmail.com",
    "contact_email_invest": "agfcompany2026@gmail.com",
    "agf_description_courte": "Integrated agro-industrial and advanced biomanufacturing enterprise",
    "adresse_siege": "Plot No. 20, Chisoko Village, Along Mungule Road, Keembe Area, Liteta Chiefdom, Keembe Ward, Chibombo District, Central Province, Zambia",
    "horaires_travail": "Lun-Ven: 8h-17h",
    "facility_projet": "AFOOPROF - Pilot Agrofood & Bio-Inputs Industrial Validation and Early Commercialization Platform",
    "directeur_general": "Harimenshi Alexis",
    "licence_investissement": "ZDA/59004/10/2025",
    "registration_no": "2685158323",
    "tpin": "200367524",
    "investissement_recherche": "USD 500,000,000",
    "superficie_plateforme": "97 ha",
    "nombre_produits": "12",
    "irr": "15.78%",
}

BANK_LABELS = {
    "bank_name": "Bank",
    "bank_account_name": "Corporate Account Name",
    "bank_account_number": "Corporate Account Number",
    "bank_branch": "Branch",
    "bank_branch_address": "Branch Address",
    "bank_swift": "Bank SWIFT/BIC",
    "bank_sort_code": "Bank Sort Code",
    "bank_rm_name": "Relationship Manager",
    "bank_rm_phone": "Relationship Manager Telephone",
    "bank_rm_email": "Relationship Manager Email",
    "bank_hq_phone": "Bank Head Office / Corporate Banking Telephone",
    "bank_corporate_email": "Bank Corporate Banking Email",
    "bank_correspondent": "Other Bank Coordinates / Correspondent Banking Details",
}


class HomeError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


def settings_map(keys=None):
    data = dict(SETTING_DEFAULTS)
    qs = Setting.objects.filter(deleted_at__isnull=True)
    if keys:
        qs = qs.filter(clef__in=list(keys))
    for row in qs:
        if row.valeur not in (None, ""):
            data[row.clef] = row.valeur
    return data


def _clean(value):
    return str(value or "").strip()


def _phone_tel(value):
    return re.sub(r"[^0-9+]", "", _clean(value))


def base_context(title=""):
    cfg = settings_map()
    email = cfg.get("site_email") or ""
    address = cfg.get("adresse_siege") or cfg.get("site_adresse") or ""
    whatsapp = cfg.get("whatsapp_number") or cfg.get("site_phone") or ""
    try:
        social_links = list(
            SocialLink.objects.filter(is_active=1).order_by("display_order", "id")
        )
    except Exception:
        social_links = []
    return {
        "page_title": title or cfg["site_name"],
        "site_settings": cfg,
        "site_name": cfg.get("nom_entreprise") or cfg["site_name"],
        "site_slogan": cfg.get("slogan") or cfg.get("span_site_name") or "",
        "site_logo_url": media_url(cfg.get("site_logo"), "/attachments/Parametres/african-green-farmers-logo.jpg"),
        "site_favicon_url": media_url(cfg.get("favicon_ico"), "/attachments/Parametres/african-green-farmers-logo.jpg"),
        "home_about_image_url": media_url(
            cfg.get("home_about_image"), "/attachments/Parametres/agf-limited-certificate.jpg"
        ),
        "site_phone": cfg["site_phone"],
        "site_phone_tel": _phone_tel(cfg["site_phone"]),
        "site_phone_alt": cfg.get("site_phone_alt") or "",
        "site_phone_alt_tel": _phone_tel(cfg.get("site_phone_alt")),
        "site_email": email,
        "site_address": address,
        "site_hours": cfg.get("horaires_travail") or "",
        "site_whatsapp": whatsapp,
        "site_whatsapp_tel": _phone_tel(whatsapp).lstrip("+"),
        "social_links": social_links,
    }


def home_context():
    produits = list(Produit.objects.filter(est_actif=1).order_by("ordre", "id")[:6])
    unites = list(UniteAffaire.objects.filter(est_actif=1).order_by("ordre", "id"))
    partenaires = list(
        Partenaire.objects.filter(est_actif=1, deleted_at__isnull=True).order_by("created_at", "id_partenaire")
    )
    cfg = settings_map(
        {
            "investissement_recherche",
            "superficie_plateforme",
            "nombre_produits",
            "irr",
            "site_phone",
        }
    )
    ctx = base_context(cfg["site_name"])
    ctx.update(
        {
            "produits": produits,
            "total_produits": Produit.objects.filter(est_actif=1).count(),
            "unites": unites,
            "partenaires": partenaires,
            "stats_investissement": cfg["investissement_recherche"],
            "stats_superficie": cfg["superficie_plateforme"],
            "stats_produits": cfg["nombre_produits"],
            "stats_irr": cfg["irr"],
            "site_phone": cfg["site_phone"],
            "site_phone_tel": _phone_tel(cfg["site_phone"]),
        }
    )
    return ctx


def static_page(title):
    return base_context(title)


def unit_detail(slug):
    unit = UniteAffaire.objects.filter(slug=slug, est_actif=1).first()
    if not unit:
        raise HomeError("Unité introuvable", 404)
    ctx = base_context(unit.nom)
    ctx["unit"] = unit
    return ctx


def products_context():
    from core.models import ProduitCategorie

    cat_map = {row.id: row.nom for row in ProduitCategorie.objects.all()}
    rows = list(
        Produit.objects.filter(est_actif=1)
        .order_by("ordre", "id")
        .values("id", "nom", "description", "prix", "slug", "image", "conditionnement", "est_certifie", "categorie_id")
    )
    for row in rows:
        row["categorie_nom"] = cat_map.get(row.get("categorie_id"), "")
    ctx = base_context("Our Products")
    ctx["produits"] = rows
    return ctx


def _shop_product_payload(product, cat=None):
    return {
        "obj": product,
        "id": product.id,
        "nom": product.nom,
        "description": product.description or "",
        "prix": product.prix or "",
        "slug": product.slug or str(product.id),
        "image": product.image or "",
        "conditionnement": product.conditionnement or "",
        "est_certifie": product.est_certifie,
        "categorie_nom": cat.nom if cat else "",
        "categorie_slug": cat.slug if cat else "",
    }


def shop_context(category_slug="", query=""):
    from core.models import ProduitCategorie

    categories = list(ProduitCategorie.objects.filter(est_actif=1).order_by("ordre", "nom"))
    cat_map = {row.id: row for row in categories}
    category = None
    if category_slug:
        category = next((c for c in categories if c.slug == category_slug), None)
        if not category:
            raise HomeError("Catégorie introuvable", 404)
    query = _clean(query)
    qs = Produit.objects.filter(est_actif=1)
    if category:
        qs = qs.filter(categorie_id=category.id)
    if len(query) >= 2:
        qs = qs.filter(Q(nom__icontains=query) | Q(description__icontains=query))
    products = list(qs.order_by("ordre", "id"))
    enriqued = [_shop_product_payload(product, cat_map.get(product.categorie_id)) for product in products]
    ctx = base_context("Shop - A.G.F")
    ctx.update(
        {
            "produits": enriqued,
            "query": query,
            "categories": [
                {
                    "id": c.id,
                    "nom": c.nom,
                    "slug": c.slug or "",
                    "description": c.description or "",
                    "total_produits": Produit.objects.filter(est_actif=1, categorie_id=c.id).count(),
                }
                for c in categories
            ],
            "current_category": (
                {
                    "id": category.id,
                    "nom": category.nom,
                    "slug": category.slug or "",
                    "description": category.description or "",
                }
                if category
                else None
            ),
        }
    )
    return ctx


def shop_products(category_slug="", query=""):
    from core.models import ProduitCategorie

    categories = list(ProduitCategorie.objects.filter(est_actif=1).order_by("ordre", "nom"))
    cat_map = {row.id: row for row in categories}
    qs = Produit.objects.filter(est_actif=1)
    if category_slug and category_slug != "all":
        category = next((c for c in categories if c.slug == category_slug), None)
        if not category:
            qs = qs.none()
        else:
            qs = qs.filter(categorie_id=category.id)
    query = _clean(query)
    if len(query) >= 2:
        qs = qs.filter(Q(nom__icontains=query) | Q(description__icontains=query))
    rows = [_shop_product_payload(product, cat_map.get(product.categorie_id)) for product in qs.order_by("ordre", "id")]
    return rows


def shop_detail(slug):
    from core.models import ProduitCategorie

    product = Produit.objects.filter(est_actif=1).filter(Q(slug=slug) | Q(id=slug if str(slug).isdigit() else 0)).first()
    if not product:
        raise HomeError("Produit introuvable", 404)
    categories = {row.id: row for row in ProduitCategorie.objects.all()}
    cat = categories.get(product.categorie_id)
    related_qs = list(
        Produit.objects.filter(est_actif=1, categorie_id=product.categorie_id).exclude(id=product.id)
    )
    random.shuffle(related_qs)
    related = []
    for row in related_qs[:4]:
        rcat = categories.get(row.categorie_id)
        related.append(
            {
                "nom": row.nom,
                "prix": row.prix or "",
                "slug": row.slug or str(row.id),
                "image": row.image or "",
                "est_certifie": row.est_certifie,
                "categorie_nom": rcat.nom if rcat else "",
            }
        )
    ctx = base_context(product.nom)
    ctx["product"] = {
        "id": product.id,
        "nom": product.nom,
        "description": product.description or "",
        "prix": product.prix or "",
        "slug": product.slug or str(product.id),
        "image": product.image or "",
        "conditionnement": product.conditionnement or "",
        "est_certifie": product.est_certifie,
        "categorie_nom": cat.nom if cat else "",
        "categorie_slug": cat.slug if cat else "",
        "categorie_description": cat.description if cat else "",
    }
    ctx["related"] = related
    return ctx


def contact_context():
    cfg = settings_map()
    banque = {}
    for key, label in BANK_LABELS.items():
        value = _clean(cfg.get(key))
        if value:
            banque[label] = value
    ctx = base_context("Contact - A.G.F")
    ctx.update(
        {
            "company": cfg["site_name"],
            "facility": cfg["facility_projet"],
            "site_address": cfg["adresse_siege"],
            "ceo": cfg["directeur_general"],
            "site_email": cfg["site_email"],
            "site_phone": cfg["site_phone"],
            "site_phone_alt": cfg["site_phone_alt"],
            "licence_investissement": cfg["licence_investissement"],
            "registration_no": cfg.get("registration_no", ""),
            "tpin": cfg.get("tpin", ""),
            "incorporation_date": "12 March 2025",
            "horaires": cfg["horaires_travail"],
            "banque": banque,
        }
    )
    return ctx


def send_contact(data, request):
    name = _clean(data.get("name"))
    email = _clean(data.get("email"))
    subject = _clean(data.get("subject"))
    message = _clean(data.get("message"))
    phone = _clean(data.get("phone")) or "0000000000"
    if len(name) < 3:
        raise HomeError("Please enter your full name.")
    if "@" not in email or "." not in email.split("@")[-1]:
        raise HomeError("Please enter a valid email address.")
    if not subject:
        raise HomeError("Please choose a subject.")
    if len(message) < 10:
        raise HomeError("Your message must contain at least 10 characters.")
    ContactMessage.objects.create(
        full_name=name[:250],
        email=email[:250],
        subject=subject[:250],
        message=message,
        phone_number=phone[:12],
        date_creation=timezone.now(),
        location="Site Web",
        ip_address=request.META.get("REMOTE_ADDR"),
        user_agent=request.META.get("HTTP_USER_AGENT"),
    )
    return "Your message has been sent successfully! We will reply shortly."


def subscribe_newsletter(email, request):
    email = _clean(email)
    if not email or "@" not in email or "." not in email.split("@")[-1] or len(email) > 190:
        raise HomeError("Please enter a valid email address.")
    exists = NewsletterAbonne.objects.filter(email=email, deleted_at__isnull=True).exists()
    if exists:
        return "You are already subscribed. Thank you!"
    NewsletterAbonne.objects.create(
        email=email,
        source="footer",
        ip_address=request.META.get("REMOTE_ADDR"),
        user_agent=request.META.get("HTTP_USER_AGENT"),
        created_at=timezone.now(),
        updated_at=timezone.now(),
    )
    return "Thank you! You are now subscribed to the A.G.F newsletter."


def _bool(value):
    return 1 if str(value or "").lower() in {"1", "true", "on", "yes"} else 0


def _payload(model, data, boolean_fields=()):
    allowed = {field.attname for field in model._meta.fields if not field.primary_key}
    values = {}
    for key in allowed:
        if key in data:
            values[key] = data.get(key)
    for key in boolean_fields:
        values[key] = _bool(data.get(key))
    return values


def _required_text(model, data, labels):
    values = {}
    for field, label in labels.items():
        value = _clean(data.get(field))
        if not value:
            raise HomeError(f'"{label}" is required.')
        try:
            max_length = model._meta.get_field(field).max_length
        except Exception:
            max_length = None
        values[field] = value[:max_length] if max_length else value
    return values


def _valid_email(data):
    email = _clean(data.get("email"))
    if "@" not in email or "." not in email.split("@")[-1]:
        raise HomeError("Invalid email address.")
    return email


def _valid_country(data):
    try:
        country_id = int(data.get("id_pays") or 0)
    except (TypeError, ValueError):
        country_id = 0
    if country_id <= 0 or not Pays.objects.filter(id=country_id).exists():
        raise HomeError("Please select a valid country.")
    return country_id


def _require_check(data, fields, message):
    if not any(_bool(data.get(field)) for field in fields):
        raise HomeError(message)


def _require_all(data, fields, message):
    if any(not _bool(data.get(field)) for field in fields):
        raise HomeError(message)


def create_investor(data):
    labels = {
        "full_name": "Full Name",
        "organization": "Organization",
        "position_title": "Position / Title",
        "phone": "Phone",
        "commitment_range": "Commitment Range",
    }
    values = _required_text(Investor, data, labels)
    email = _valid_email(data)
    if Investor.objects.filter(email__iexact=email).exists():
        raise HomeError("This email is already registered.")
    values["email"] = email
    values["id_pays"] = _valid_country(data)
    values["timeline"] = _clean(data.get("timeline"))[:100]
    for field in ("interest_other", "strategic_message"):
        values[field] = _clean(data.get(field))
    _require_check(
        data,
        (
            "interest_equity", "interest_debt", "interest_blended_finance",
            "interest_grant", "interest_strategic_partnership",
            "interest_technical_collaboration", "interest_offtake_distribution",
        ),
        "Select at least one Investment Interest.",
    )
    focus_botanical = _bool(data.get("focus_botanical_plant"))
    CHECKBOXES = (
        "interest_equity", "interest_debt", "interest_blended_finance",
        "interest_grant", "interest_strategic_partnership",
        "interest_technical_collaboration", "interest_offtake_distribution",
        "focus_research_lab", "focus_gmp_facility", "focus_commercialization",
        "focus_full_platform", "agree_contact", "non_binding_confirmation",
    )
    for field in CHECKBOXES:
        values[field] = _bool(data.get(field))
    values["focus_medicinal_plant"] = focus_botanical
    if not any(values[field] for field in ("focus_research_lab", "focus_gmp_facility", "focus_medicinal_plant", "focus_commercialization", "focus_full_platform")):
        raise HomeError("Select at least one Focus Area.")
    _require_all(data, ("agree_contact", "non_binding_confirmation"), "Both confirmations are required.")
    values["created_at"] = timezone.now()
    values["updated_at"] = timezone.now()
    Investor.objects.create(**values)
    return "Your investor registration has been submitted."


def create_broker(data):
    labels = {
        "full_name": "Full Name",
        "firm_name": "Firm Name",
        "mobile_phone": "Mobile Phone",
        "jurisdiction_of_incorporation": "Jurisdiction of Incorporation",
        "registration_number": "Registration Number",
        "regulatory_status": "Regulatory Status",
        "regulatory_authority": "Regulatory Authority",
    }
    values = _required_text(Broker, data, labels)
    if values["regulatory_status"] not in {"Licensed", "Exempt", "Unlicensed"}:
        raise HomeError("Please select a valid regulatory status.")
    email = _valid_email(data)
    if Broker.objects.filter(email__iexact=email).exists():
        raise HomeError("This email is already registered.")
    values["email"] = email
    values["id_pays"] = _valid_country(data)
    for field in (
        "whatsapp", "corporate_website", "capacity_other", "typical_ticket_size",
        "geographic_coverage",
    ):
        values[field] = _clean(data.get(field))
    values["engagement_model"] = _clean(data.get("engagement_model")) or None
    _require_check(
        data,
        (
            "capacity_investment_broker", "capacity_placement_agent",
            "capacity_corporate_finance_advisor", "capacity_fund_manager",
            "capacity_family_office_rep", "capacity_esg_advisor",
            "capacity_independent_introducer",
        ),
        "Select at least one Broker Capacity.",
    )
    _require_check(
        data,
        (
            "investor_private_equity", "investor_venture_capital",
            "investor_esg_impact", "investor_dfi", "investor_institutional",
            "investor_hnwi", "investor_sovereign",
        ),
        "Select at least one Investor Type.",
    )
    for field in (
        "capacity_investment_broker", "capacity_placement_agent",
        "capacity_corporate_finance_advisor", "capacity_fund_manager",
        "capacity_family_office_rep", "capacity_esg_advisor",
        "capacity_independent_introducer", "investor_private_equity",
        "investor_venture_capital", "investor_esg_impact", "investor_dfi",
        "investor_institutional", "investor_hnwi", "investor_sovereign",
        "mandate_equity", "mandate_structured_debt", "mandate_blended_finance",
        "mandate_grant", "mandate_strategic_partnership", "mandate_full_program",
        "confirm_authorized", "confirm_aml_kyc", "acknowledge_no_exclusivity",
        "understand_formal_mandate_required",
    ):
        values[field] = _bool(data.get(field))
    _require_all(
        data,
        ("confirm_authorized", "confirm_aml_kyc", "acknowledge_no_exclusivity", "understand_formal_mandate_required"),
        "All confirmations are required.",
    )
    values["created_at"] = timezone.now()
    values["updated_at"] = timezone.now()
    Broker.objects.create(**values)
    return "Your broker registration has been submitted."


def search_countries(query):
    query = _clean(query)
    if not query:
        return []
    rows = Pays.objects.filter(pays__icontains=query).order_by("pays")[:15]
    return [
        {"id": row.id, "pays": row.pays, "ISO_3166_1_2_Letter_Code": row.iso2}
        for row in rows
    ]


SEARCH_PAGES = [
    ("Accueil", "/", "Menu"),
    ("Company Profile", "/about-us", "About"),
    ("ANINOVA Industries", "/aninova-industries", "About"),
    ("CERIQA Laboratories", "/ceriqa-labo", "About"),
    ("Agriculture & Bioresources", "/agriculture-bioresources", "About"),
    ("Markets & Partners", "/markets-partners", "About"),
    ("Sustainability & Impact", "/sustainability-impact", "About"),
    ("Produits & Innovation", "/our-Products", "About"),
    ("Boutique", "/shop", "Shop"),
    ("Média", "/media", "Media"),
    ("Tendances", "/media/trending", "Media"),
    ("Actualités", "/media/news", "Media"),
    ("Témoignages", "/media/temoignages", "Media"),
    ("Contact", "/contact", "Contact"),
    ("Devenir Courtier", "/broker", "Investment"),
    ("Devenir Investisseur", "/investor", "Investment"),
    ("Executive Credit Summary", "/credit-summary", "Investment"),
    ("Advisory Service", "/doctor", "Advisory"),
    ("Choose an Expert", "/consultation", "Advisory"),
    ("Patient Form", "/patient-form", "Advisory"),
    ("Mentions légales", "/legal", "Legal"),
    ("Politique de confidentialité", "/privacy", "Legal"),
]


def global_search(query):
    query = _clean(query)
    if not query:
        return {"produits": [], "actualites": [], "pages": []}

    produits = []
    for product in Produit.objects.filter(est_actif=1).filter(
        Q(nom__icontains=query) | Q(description__icontains=query)
    )[:6]:
        produits.append(
            {
                "id": product.id,
                "nom": product.nom,
                "titre": product.nom,
                "slug": product.slug or product.id,
                "extrait": Truncator(strip_tags(product.description or "")).chars(80, truncate="..."),
            }
        )

    lowered = query.lower()
    pages = []
    for title, url, group in SEARCH_PAGES:
        simple = re.sub(r"[^a-z0-9 ]", "", title.lower())
        if lowered in title.lower() or lowered in simple:
            pages.append({"titre": title, "url": url, "description": group, "icon": "bi-file-text"})
        elif _levenshtein(lowered, title.lower()) <= 3:
            pages.append({"titre": title, "url": url, "description": group, "icon": "bi-file-text"})

    return {"produits": produits, "actualites": [], "pages": pages}


def _levenshtein(a, b):
    if abs(len(a) - len(b)) > 3:
        return 99
    previous = list(range(len(b) + 1))
    for i, char_a in enumerate(a, 1):
        current = [i]
        for j, char_b in enumerate(b, 1):
            current.append(
                min(
                    previous[j] + 1,
                    current[j - 1] + 1,
                    previous[j - 1] + (char_a != char_b),
                )
            )
        previous = current
    return previous[-1]


def detect_country(request):
    client_ip = request.META.get("REMOTE_ADDR", "")
    if client_ip in {"127.0.0.1", "::1", "localhost"}:
        return {"id": "", "pays": ""}
    code = (request.META.get("HTTP_CF_IPCOUNTRY") or request.META.get("HTTP_X_COUNTRY_CODE") or "").upper()
    if not code or code in {"XX", "T1"}:
        return {"id": "", "pays": ""}
    row = Pays.objects.filter(iso2__iexact=code).first()
    return {"id": row.id, "pays": row.pays} if row else {"id": "", "pays": ""}


MEDIA_TYPES = {"image", "video", "audio", "document", "link", "book", "autre"}
VIDEO_LINK_PARTS = ("youtube", "youtu.be", "vimeo", "dailymotion")


def _youtube_id(url):
    url = _clean(url)
    if "youtu.be/" in url:
        return url.split("youtu.be/")[-1].split("?")[0]
    if "youtube.com/watch" in url:
        for part in url.split("?")[-1].split("&"):
            if part.startswith("v="):
                return part[2:]
    if "youtube.com/embed/" in url:
        return url.split("/embed/")[-1].split("?")[0]
    return ""


def _media_url(value):
    value = str(value or "")
    if not value:
        return ""
    if value.startswith(("http://", "https://")):
        return value
    if (Path(django_settings.PROJECT_ROOT) / value).is_file():
        return f"/{value}"
    return ""


def _format_duration(seconds):
    try:
        seconds = int(seconds or 0)
    except (TypeError, ValueError):
        return "0:00"
    if seconds <= 0:
        return "0:00"
    hours, remainder = divmod(seconds, 3600)
    minutes, secs = divmod(remainder, 60)
    if hours:
        return f"{hours}:{minutes:02d}:{secs:02d}"
    return f"{minutes}:{secs:02d}"


def _media_views(row):
    return MediaView.objects.filter(id_media=row.id_media).count()


def _media_payload(row):
    thumb = row.miniature or (row.fichier if row.type == "image" else "")
    yt = _youtube_id(row.lien) if row.type == "link" else ""
    if not thumb and yt:
        thumb = f"https://img.youtube.com/vi/{yt}/hqdefault.jpg"
    thumb_url = _media_url(thumb)
    file_url = _media_url(row.fichier)
    return {
        "id": row.id_media,
        "id_media": row.id_media,
        "titre": row.titre or "",
        "slug": row.slug or "",
        "type": row.type or "autre",
        "sous_type": row.sous_type or "",
        "fichier": row.fichier or "",
        "lien": row.lien or "",
        "miniature": thumb,
        "thumb_url": thumb_url,
        "thumbnail_url": thumb_url,
        "file_url": file_url,
        "description": row.description or "",
        "categorie": row.categorie or "",
        "date_media": row.date_media.isoformat() if row.date_media else "",
        "credits": row.credits or "",
        "taille": row.taille or 0,
        "mime_type": row.mime_type or "",
        "youtube_id": yt,
        "telechargements": row.telechargements or 0,
        "duree": row.duree or 0,
        "duration_formatted": _format_duration(row.duree),
        "views_count": _media_views(row),
    }


def _apply_media_filter(qs, media_type=""):
    if not media_type or media_type == "all":
        return qs
    if media_type == "book":
        return qs.filter(type="document", sous_type="book")
    if media_type == "video":
        links = None
        for part in VIDEO_LINK_PARTS:
            condition = Q(type="link", lien__icontains=part)
            links = condition if links is None else links | condition
        return qs.filter(Q(type="video") | links)
    if media_type in MEDIA_TYPES:
        return qs.filter(type=media_type)
    return qs.none()


def media_list(page=1, media_type="", search="", sort="recent", category="", limit=12):
    page = max(1, int(page or 1))
    limit = max(1, int(limit or 12))
    qs = _apply_media_filter(GalerieMedia.objects.filter(est_actif=1), media_type)
    if category:
        qs = qs.filter(categorie__iexact=category)
    search = _clean(search)
    if len(search) >= 2:
        qs = qs.filter(
            Q(titre__icontains=search)
            | Q(credits__icontains=search)
            | Q(description__icontains=search)
            | Q(categorie__icontains=search)
        )
    if sort == "downloads":
        qs = qs.order_by("-telechargements", "-id_media")
    elif sort == "oldest":
        qs = qs.order_by("id_media")
    else:
        qs = qs.order_by("-created_at", "-id_media")
    total = qs.count()
    pages = max(1, (total + limit - 1) // limit)
    page = min(page, pages)
    rows = [_media_payload(row) for row in qs[(page - 1) * limit : page * limit]]
    categories = list(
        GalerieMedia.objects.filter(est_actif=1)
        .exclude(categorie__isnull=True)
        .exclude(categorie="")
        .values_list("categorie", flat=True)
        .distinct()
        .order_by("categorie")
    )
    return {
        "items": rows,
        "medias": rows,
        "categories": categories,
        "pagination": {"page": page, "pages": pages, "total": total},
        "total": total,
        "page": page,
        "has_more": page * limit < total,
        "limit": limit,
    }


def media_page(title="Media", **kwargs):
    ctx = base_context(title)
    ctx.update(media_list(**kwargs))
    return ctx


def media_grid(**kwargs):
    kwargs["limit"] = 20
    return media_list(**kwargs)


def record_media_view(row, request):
    if request is None:
        return
    session = getattr(request, "session", None)
    if session is None:
        return
    session_id = session.session_key
    if not session_id:
        session.save()
        session_id = session.session_key
    if not session_id:
        return
    if MediaView.objects.filter(id_media=row.id_media, session_id=session_id).exists():
        return
    MediaView.objects.create(
        id_media=row.id_media,
        user_id=session.get("id_utilisateur"),
        session_id=session_id,
        ip_address=request.META.get("REMOTE_ADDR"),
        user_agent=request.META.get("HTTP_USER_AGENT"),
        viewed_at=timezone.now(),
    )


def media_detail(identifier, request=None):
    qs = GalerieMedia.objects.filter(est_actif=1)
    row = qs.filter(slug=identifier).first() if not str(identifier).isdigit() else qs.filter(id_media=int(identifier)).first()
    if not row and str(identifier).isdigit():
        row = qs.filter(id_media=int(identifier)).first()
    if not row:
        raise HomeError("Média introuvable", 404)
    record_media_view(row, request)
    ctx = base_context(row.titre or "Média")
    ctx["media"] = _media_payload(row)
    related_qs = list(
        GalerieMedia.objects.filter(est_actif=1, type=row.type).exclude(id_media=row.id_media)
    )
    random.shuffle(related_qs)
    ctx["related"] = [_media_payload(x) for x in related_qs[:8]]
    return ctx


def _media_download_candidates(row):
    raw = str(row.fichier or "").lstrip("/")
    candidates = [Path(django_settings.PROJECT_ROOT) / raw]
    name = Path(raw).name
    if row.type == "video":
        candidates.extend(
            [
                Path(django_settings.PROJECT_ROOT) / "attachments" / "Video" / "Originals" / name,
                Path(django_settings.PROJECT_ROOT) / "attachments" / "Video" / "Encoded" / name,
            ]
        )
    elif row.type == "audio":
        candidates.extend(
            [
                Path(django_settings.PROJECT_ROOT) / "attachments" / "Audio" / "Originals" / name,
                Path(django_settings.PROJECT_ROOT) / "attachments" / "Audio" / "Converted" / name,
            ]
        )
    elif row.type == "image":
        candidates.append(Path(django_settings.PROJECT_ROOT) / "attachments" / "Image" / name)
    else:
        candidates.append(Path(django_settings.PROJECT_ROOT) / "attachments" / "Documents" / name)
    return candidates


def media_file(identifier, request=None):
    if not identifier and request is not None:
        identifier = request.GET.get("slug") or request.GET.get("id")
    if not identifier:
        raise HomeError("Média introuvable", 404)
    qs = GalerieMedia.objects.filter(est_actif=1)
    row = qs.filter(id_media=int(identifier)).first() if str(identifier).isdigit() else qs.filter(slug=identifier).first()
    if not row:
        raise HomeError("Média introuvable", 404)
    if not row.fichier or str(row.fichier).startswith(("http://", "https://")):
        raise HomeError("Aucun fichier téléchargeable", 404)
    path = next((candidate for candidate in _media_download_candidates(row) if candidate.is_file()), None)
    if not path:
        raise HomeError("Fichier introuvable", 404)
    extension = path.suffix.lower()
    mime_type = mimetypes.guess_type(str(path))[0] or row.mime_type or "application/octet-stream"
    safe_title = re.sub(r"[^a-zA-Z0-9_-]+", "_", row.titre or f"media-{row.id_media}").strip("_")
    filename = f"{safe_title or 'media'}{extension}"
    return FileResponse(open(path, "rb"), as_attachment=True, filename=filename, content_type=mime_type)

def log_visit(request):
    try:
        ip = request.META.get("REMOTE_ADDR", "")
        if getattr(django_settings, "TRUST_PROXY", False):
            forwarded = request.META.get("HTTP_X_FORWARDED_FOR", "")
            if forwarded:
                ip = forwarded.split(",")[0].strip()
        if ip in ("::1", "127.0.0.1"):
            ip = "197.255.128.0"
        user_agent = request.META.get("HTTP_USER_AGENT", "")
        device = "Mobile" if re.search(r"Mobile|Android|iPhone|iPad|iPod|Windows Phone|Opera Mini", user_agent, re.I) else "Desktop"
        now = timezone.now()
        page = request.build_absolute_uri()[:255]
        if VisitorLog.objects.filter(ip_address=ip, visit_date=now.date(), page=page).exists():
            return False
        VisitorLog.objects.create(
            ip_address=ip,
            user_agent=user_agent or None,
            device=device,
            page=page,
            referer=(request.META.get("HTTP_REFERER") or None),
            visit_date=now.date(),
            visit_time=now.time().replace(microsecond=0),
            created_at=now,
        )
        return True
    except Exception:
        return False
