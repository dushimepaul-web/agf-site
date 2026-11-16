import uuid as uuidlib
from pathlib import Path

from django.conf import settings
from django.db.models import Count, Q
from django.utils import timezone
from django.utils.text import slugify

from core.models import GalerieMedia, MediaView


class MediaError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


ALLOWED_TYPES = {"image", "video", "audio", "document", "link", "autre"}
IMAGE_EXT = {".jpg", ".jpeg", ".png", ".gif", ".webp"}
VIDEO_EXT = {".mp4", ".webm", ".mov", ".avi", ".mkv"}
AUDIO_EXT = {".mp3", ".wav", ".ogg", ".m4a", ".aac"}
DOC_EXT = {".pdf", ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".txt", ".csv"}
FOLDER = {
    "image": "Images",
    "video": "Videos",
    "audio": "Audios",
    "document": "Documents",
    "autre": "Autres",
}
TYPE_EXT = {
    "image": IMAGE_EXT,
    "video": VIDEO_EXT,
    "audio": AUDIO_EXT,
    "document": DOC_EXT,
}
TYPE_MAX_SIZE = {
    "image": 100 * 1024 * 1024,
    "video": 2 * 1024 * 1024 * 1024,
    "audio": 4 * 1024 * 1024 * 1024,
    "document": 500 * 1024 * 1024,
    "autre": 4 * 1024 * 1024 * 1024,
}


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


def _local_path(relative):
    rel = str(relative or "").replace("\\", "/").lstrip("/")
    if not rel or ".." in rel.split("/"):
        return None
    root = Path(settings.PROJECT_ROOT).resolve()
    path = (root / rel).resolve()
    if root != path and root not in path.parents:
        return None
    return path


def _guess_type(filename):
    ext = Path(filename).suffix.lower()
    if ext in IMAGE_EXT:
        return "image"
    if ext in VIDEO_EXT:
        return "video"
    if ext in AUDIO_EXT:
        return "audio"
    if ext in DOC_EXT:
        return "document"
    return "autre"


def unique_slug(title, exclude_id=None):
    base = slugify(_clean(title))[:240] or f"media-{uuidlib.uuid4().hex[:8]}"
    slug = base
    index = 2
    while True:
        qs = GalerieMedia.objects.filter(slug=slug)
        if exclude_id:
            qs = qs.exclude(id_media=exclude_id)
        if not qs.exists():
            return slug
        slug = f"{base}-{index}"
        index += 1


def _save_file(upload, subdir, max_size=4 * 1024 * 1024 * 1024):
    if not upload:
        return None, None, None
    ext = Path(upload.filename or "").suffix.lower()
    folder = Path(settings.PROJECT_ROOT) / "attachments" / "Media" / subdir
    folder.mkdir(parents=True, exist_ok=True)
    filename = f"{uuidlib.uuid4().hex}{ext}"
    target = folder / filename
    size = 0
    try:
        with target.open("wb") as out:
            while True:
                chunk = upload.file.read(1024 * 1024)
                if not chunk:
                    break
                size += len(chunk)
                if size > max_size:
                    raise MediaError("Fichier trop volumineux")
                out.write(chunk)
    except Exception:
        if target.is_file():
            target.unlink()
        raise
    relative = f"attachments/Media/{subdir}/{filename}"
    return relative, size, upload.content_type or ""


def save_temp_file(upload):
    return _save_file(upload, "Temp")


def save_thumbnail(upload):
    if not upload:
        raise MediaError("Aucun fichier reçu")
    ext = Path(upload.filename or "").suffix.lower()
    if ext not in IMAGE_EXT:
        raise MediaError("Format de miniature non supporté")
    content = upload.file.read()
    if len(content) > 5 * 1024 * 1024:
        raise MediaError("Miniature trop volumineuse (max 5 Mo)")
    folder = Path(settings.PROJECT_ROOT) / "attachments" / "Media" / "Thumbnails" / "Custom"
    folder.mkdir(parents=True, exist_ok=True)
    filename = f"{uuidlib.uuid4().hex}{ext}"
    (folder / filename).write_bytes(content)
    return f"attachments/Media/Thumbnails/Custom/{filename}"


def list_media(page=1, limit=24, media_type=None, search=""):
    page = max(1, _int(page, 1))
    limit = min(50, max(12, _int(limit, 24)))
    qs = GalerieMedia.objects.all()
    if media_type in ALLOWED_TYPES:
        qs = qs.filter(type=media_type)
    search = _clean(search)
    if search:
        qs = qs.filter(
            Q(titre__icontains=search)
            | Q(fichier__icontains=search)
            | Q(description__icontains=search)
            | Q(credits__icontains=search)
            | Q(categorie__icontains=search)
            | Q(slug__icontains=search)
        )
    total = qs.count()
    pages = max(1, (total + limit - 1) // limit)
    rows = qs.order_by("-id_media")[(page - 1) * limit : page * limit]
    return {
        "medias": [_dict(r) for r in rows],
        "pagination": {"page": page, "limit": limit, "total": total, "pages": pages},
    }


def statistics():
    counts = {row["type"]: row["total"] for row in GalerieMedia.objects.values("type").annotate(total=Count("id_media"))}
    return {
        "total": GalerieMedia.objects.count(),
        "actifs": GalerieMedia.objects.filter(est_actif=1).count(),
        "par_type": counts,
        "vues": MediaView.objects.count(),
    }


def categories():
    return list(
        GalerieMedia.objects.exclude(categorie__isnull=True)
        .exclude(categorie="")
        .values_list("categorie", flat=True)
        .distinct()
        .order_by("categorie")
    )


def get_media(media_id):
    row = GalerieMedia.objects.filter(id_media=media_id).first()
    if not row:
        raise MediaError("Média introuvable", 404)
    return _dict(row)


def create_media(data, upload=None, thumbnail_upload=None):
    data = data or {}
    titre = _clean(data.get("titre"))
    if not titre:
        raise MediaError("Le titre est obligatoire")
    media_type = _clean(data.get("type")) or "autre"
    if media_type not in ALLOWED_TYPES:
        raise MediaError("Type de média invalide")
    fichier = None
    taille = None
    mime = None
    lien = _clean(data.get("lien")) or None
    if media_type == "link":
        if not lien or not lien.startswith(("http://", "https://")):
            raise MediaError("URL invalide")
    else:
        if not upload:
            raise MediaError("Le fichier est obligatoire")
        ext = Path(upload.filename or "").suffix.lower()
        allowed = TYPE_EXT.get(media_type)
        if allowed is not None and ext not in allowed:
            raise MediaError(f"Format de fichier non supporté pour ce type : {ext or 'inconnu'}")
        fichier, taille, mime = _save_file(
            upload,
            FOLDER.get(media_type, "Autres"),
            TYPE_MAX_SIZE.get(media_type, TYPE_MAX_SIZE["autre"]),
        )
    miniature = _clean(data.get("miniature")) or None
    if thumbnail_upload:
        miniature = save_thumbnail(thumbnail_upload)
    now = timezone.now()
    return GalerieMedia.objects.create(
        titre=titre,
        slug=unique_slug(titre),
        type=media_type,
        sous_type=_clean(data.get("sous_type")) or None,
        fichier=fichier,
        lien=lien,
        miniature=miniature,
        description=_clean(data.get("description")) or None,
        categorie=_clean(data.get("categorie")) or None,
        date_media=_clean(data.get("date_media")) or now.date(),
        credits=_clean(data.get("credits")) or None,
        taille=taille,
        mime_type=mime,
        est_actif=_int(data.get("est_actif", 1), 1),
        is_for_website=_int(data.get("is_for_website", 0)),
        is_for_whatsapp=_int(data.get("is_for_whatsapp", 0)),
        created_at=now,
        updated_at=now,
    )


def update_media(media_id, data, thumbnail_upload=None):
    data = data or {}
    row = GalerieMedia.objects.filter(id_media=media_id).first()
    if not row:
        raise MediaError("Média introuvable", 404)
    update = {}
    if "titre" in data:
        titre = _clean(data.get("titre"))
        if not titre:
            raise MediaError("Le titre est obligatoire")
        update["titre"] = titre
        update["slug"] = unique_slug(titre, row.id_media)
    for key in ("sous_type", "description", "categorie", "date_media", "credits", "message_reseaux", "contenu_texte"):
        if key in data:
            update[key] = _clean(data.get(key)) or None
    if "lien" in data and row.type == "link":
        lien = _clean(data.get("lien"))
        if not lien or not lien.startswith(("http://", "https://")):
            raise MediaError("URL invalide")
        update["lien"] = lien
    if "miniature" in data:
        update["miniature"] = _clean(data.get("miniature")) or None
    if thumbnail_upload:
        update["miniature"] = save_thumbnail(thumbnail_upload)
    for key in ("est_actif", "is_for_website", "is_for_whatsapp"):
        if key in data:
            update[key] = _int(data.get(key))
    if not update:
        raise MediaError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    GalerieMedia.objects.filter(id_media=media_id).update(**update)
    return row, list(update.keys())


def toggle_field(media_id, field, value):
    if field not in {"is_for_website", "est_actif", "is_for_whatsapp"}:
        raise MediaError("Champ non autorisé")
    row = GalerieMedia.objects.filter(id_media=media_id).first()
    if not row:
        raise MediaError("Média introuvable", 404)
    GalerieMedia.objects.filter(id_media=media_id).update(**{field: _int(value)}, updated_at=timezone.now())
    return row


def delete_media(media_id):
    row = GalerieMedia.objects.filter(id_media=media_id).first()
    if not row:
        raise MediaError("Média introuvable", 404)
    for relative in (row.fichier, row.miniature):
        if relative and not str(relative).startswith(("http://", "https://")):
            path = _local_path(relative)
            try:
                if path and path.is_file():
                    path.unlink()
            except OSError:
                pass
    MediaView.objects.filter(id_media=row.id_media).delete()
    row.delete()
    return row
