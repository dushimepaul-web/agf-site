import time
from pathlib import Path

from django.conf import settings
from django.utils.text import slugify

from core.helpers import generate_uuid
from core.models import Produit, ProduitCategorie, UniteAffaire


class ProduitError(Exception):
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


def _slug(value, fallback):
    return slugify(_clean(value) or _clean(fallback))[:150]


def _category_dict(row):
    return {
        "id": row.id,
        "uuid": row.uuid,
        "nom": row.nom,
        "description": row.description,
        "slug": row.slug,
        "ordre": row.ordre,
        "est_actif": row.est_actif,
        "created_at": row.created_at,
        "updated_at": row.updated_at,
    }


def list_categories(active_only=False):
    qs = ProduitCategorie.objects.order_by("ordre", "nom")
    if active_only:
        qs = qs.filter(est_actif=1)
    return [_category_dict(row) for row in qs]


def get_category(category_id):
    row = ProduitCategorie.objects.filter(id=category_id).first()
    if not row:
        raise ProduitError("Catégorie non trouvée", 404)
    return _category_dict(row)


def create_category(data):
    data = data or {}
    nom = _clean(data.get("nom"))
    if not nom:
        raise ProduitError("Le nom est obligatoire")
    slug = _slug(data.get("slug"), nom)
    if ProduitCategorie.objects.filter(slug=slug).exists():
        raise ProduitError("Ce slug existe déjà")
    row = ProduitCategorie.objects.create(
        uuid=generate_uuid(),
        nom=nom,
        slug=slug,
        description=_clean(data.get("description")) or None,
        ordre=_int(data.get("ordre")),
        est_actif=_int(data.get("est_actif", 1), 1),
    )
    return row


def update_category(category_id, data):
    data = data or {}
    row = ProduitCategorie.objects.filter(id=category_id).first()
    if not row:
        raise ProduitError("Catégorie non trouvée", 404)
    update = {}
    if "nom" in data:
        nom = _clean(data.get("nom"))
        if not nom:
            raise ProduitError("Le nom est obligatoire")
        update["nom"] = nom
    if "slug" in data:
        slug = _slug(data.get("slug"), row.nom)
        if ProduitCategorie.objects.filter(slug=slug).exclude(id=row.id).exists():
            raise ProduitError("Ce slug existe déjà")
        update["slug"] = slug
    if "description" in data:
        update["description"] = _clean(data.get("description")) or None
    if "ordre" in data:
        update["ordre"] = _int(data.get("ordre"))
    if "est_actif" in data:
        update["est_actif"] = _int(data.get("est_actif"))
    if not update:
        raise ProduitError("Aucune donnée à modifier")
    ProduitCategorie.objects.filter(id=row.id).update(**update)
    return row, list(update.keys())


def delete_category(category_id):
    row = ProduitCategorie.objects.filter(id=category_id).first()
    if not row:
        raise ProduitError("Catégorie non trouvée", 404)
    if Produit.objects.filter(categorie_id=row.id).exists():
        raise ProduitError("Impossible : des produits sont rattachés à cette catégorie")
    row.delete()
    return row


def _product_dict(row, category_name=None):
    return {
        "id": row.id,
        "uuid": row.uuid,
        "categorie_id": row.categorie_id,
        "categorie_nom": category_name,
        "nom": row.nom,
        "description": row.description,
        "prix": row.prix,
        "slug": row.slug,
        "image": row.image,
        "conditionnement": row.conditionnement,
        "est_certifie": row.est_certifie,
        "est_actif": row.est_actif,
        "ordre": row.ordre,
        "created_at": row.created_at,
        "updated_at": row.updated_at,
    }


def list_products(category_id=None):
    categories = {c.id: c.nom for c in ProduitCategorie.objects.all()}
    qs = Produit.objects.order_by("ordre", "nom")
    if category_id:
        qs = qs.filter(categorie_id=_int(category_id))
    return [_product_dict(row, categories.get(row.categorie_id)) for row in qs]


def get_product(product_id):
    row = Produit.objects.filter(id=product_id).first()
    if not row:
        raise ProduitError("Produit non trouvé", 404)
    category = ProduitCategorie.objects.filter(id=row.categorie_id).first()
    return _product_dict(row, category.nom if category else None)


def create_product(data):
    data = data or {}
    nom = _clean(data.get("nom"))
    category_id = _int(data.get("categorie_id"))
    if not nom:
        raise ProduitError("Le nom est obligatoire")
    if category_id <= 0:
        raise ProduitError("La catégorie est obligatoire")
    if not ProduitCategorie.objects.filter(id=category_id).exists():
        raise ProduitError("Catégorie invalide")
    slug = _slug(data.get("slug"), nom)
    row = Produit.objects.create(
        uuid=generate_uuid(),
        nom=nom,
        categorie_id=category_id,
        slug=slug,
        description=_clean(data.get("description")) or None,
        prix=_clean(data.get("prix")) or None,
        image=_clean(data.get("image")) or None,
        conditionnement=_clean(data.get("conditionnement")) or None,
        est_certifie=_int(data.get("est_certifie"), 0),
        est_actif=_int(data.get("est_actif", 1), 1),
        ordre=_int(data.get("ordre")),
    )
    return row


def update_product(product_id, data):
    data = data or {}
    row = Produit.objects.filter(id=product_id).first()
    if not row:
        raise ProduitError("Produit non trouvé", 404)
    update = {}
    if "nom" in data:
        nom = _clean(data.get("nom"))
        if not nom:
            raise ProduitError("Le nom est obligatoire")
        update["nom"] = nom
    if "categorie_id" in data:
        category_id = _int(data.get("categorie_id"))
        if not ProduitCategorie.objects.filter(id=category_id).exists():
            raise ProduitError("Catégorie invalide")
        update["categorie_id"] = category_id
    if "slug" in data:
        update["slug"] = _slug(data.get("slug"), row.nom)
    if "description" in data:
        update["description"] = _clean(data.get("description")) or None
    if "prix" in data:
        update["prix"] = _clean(data.get("prix")) or None
    if "image" in data:
        update["image"] = _clean(data.get("image")) or None
    if "conditionnement" in data:
        update["conditionnement"] = _clean(data.get("conditionnement")) or None
    if "est_certifie" in data:
        update["est_certifie"] = _int(data.get("est_certifie"))
    if "est_actif" in data:
        update["est_actif"] = _int(data.get("est_actif"))
    if "ordre" in data:
        update["ordre"] = _int(data.get("ordre"))
    if not update:
        raise ProduitError("Aucune donnée à modifier")
    Produit.objects.filter(id=row.id).update(**update)
    return row, list(update.keys())


def delete_product(product_id):
    row = Produit.objects.filter(id=product_id).first()
    if not row:
        raise ProduitError("Produit non trouvé", 404)
    row.delete()
    return row


def _unite_dict(row):
    return {
        "id": row.id,
        "uuid": row.uuid,
        "code": row.code,
        "nom": row.nom,
        "description": row.description,
        "slogan": row.slogan,
        "logo": row.logo,
        "slug": row.slug,
        "ordre": row.ordre,
        "est_actif": row.est_actif,
        "created_at": row.created_at,
        "updated_at": row.updated_at,
    }


def list_units(active_only=False):
    qs = UniteAffaire.objects.order_by("ordre", "nom")
    if active_only:
        qs = qs.filter(est_actif=1)
    return [_unite_dict(row) for row in qs]


def get_unit(unit_id):
    row = UniteAffaire.objects.filter(id=unit_id).first()
    if not row:
        raise ProduitError("Unité non trouvée", 404)
    return _unite_dict(row)


def create_unit(data):
    data = data or {}
    code = _clean(data.get("code")).upper()
    nom = _clean(data.get("nom"))
    if not code or not nom:
        raise ProduitError("Code et nom obligatoires")
    if UniteAffaire.objects.filter(code=code).exists():
        raise ProduitError("Ce code existe déjà")
    return UniteAffaire.objects.create(
        uuid=generate_uuid(),
        code=code,
        nom=nom,
        slug=_slug(data.get("slug"), nom),
        description=_clean(data.get("description")) or None,
        slogan=_clean(data.get("slogan")) or None,
        logo=_clean(data.get("logo")) or None,
        ordre=_int(data.get("ordre")),
        est_actif=_int(data.get("est_actif", 1), 1),
    )


def update_unit(unit_id, data):
    data = data or {}
    row = UniteAffaire.objects.filter(id=unit_id).first()
    if not row:
        raise ProduitError("Unité non trouvée", 404)
    update = {}
    if "code" in data:
        code = _clean(data.get("code")).upper()
        if not code:
            raise ProduitError("Le code est obligatoire")
        if UniteAffaire.objects.filter(code=code).exclude(id=row.id).exists():
            raise ProduitError("Ce code existe déjà")
        update["code"] = code
    if "nom" in data:
        nom = _clean(data.get("nom"))
        if not nom:
            raise ProduitError("Le nom est obligatoire")
        update["nom"] = nom
    if "slug" in data:
        update["slug"] = _slug(data.get("slug"), row.nom)
    if "description" in data:
        update["description"] = _clean(data.get("description")) or None
    if "slogan" in data:
        update["slogan"] = _clean(data.get("slogan")) or None
    if "logo" in data:
        update["logo"] = _clean(data.get("logo")) or None
    if "ordre" in data:
        update["ordre"] = _int(data.get("ordre"))
    if "est_actif" in data:
        update["est_actif"] = _int(data.get("est_actif"))
    if not update:
        raise ProduitError("Aucune donnée à modifier")
    UniteAffaire.objects.filter(id=row.id).update(**update)
    return row, list(update.keys())


def delete_unit(unit_id):
    row = UniteAffaire.objects.filter(id=unit_id).first()
    if not row:
        raise ProduitError("Unité non trouvée", 404)
    row.delete()
    return row


IMAGE_TYPES = {
    "image/jpeg": ".jpg",
    "image/png": ".png",
    "image/gif": ".gif",
    "image/webp": ".webp",
}


def save_image(upload, subdir):
    if not upload:
        raise ProduitError("Aucun fichier envoyé")
    ext = IMAGE_TYPES.get((upload.content_type or "").lower())
    if not ext:
        raise ProduitError("Type de fichier non autorisé (jpg, png, gif, webp uniquement)")
    data = upload.file.read()
    if len(data) > 5 * 1024 * 1024:
        raise ProduitError("Fichier trop volumineux (max 5Mo)")
    folder = Path(settings.PROJECT_ROOT) / "attachments" / subdir
    folder.mkdir(parents=True, exist_ok=True)
    base = slugify(Path(upload.filename or "image").stem) or "image"
    filename = f"{base}_{int(time.time())}{ext}"
    (folder / filename).write_bytes(data)
    return f"attachments/{subdir}/{filename}", filename
