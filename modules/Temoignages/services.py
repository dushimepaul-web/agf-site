import time
from pathlib import Path

from django.conf import settings
from django.utils import timezone
from django.utils.text import slugify

from core.models import Temoignage


class TemoignageError(Exception):
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
    return {
        "id": row.id,
        "titre": row.titre,
        "video_url": row.video_url,
        "miniature": row.miniature,
        "auteur": row.auteur,
        "description": row.description,
        "est_actif": row.est_actif,
        "ordre": row.ordre,
        "created_at": row.created_at,
        "updated_at": row.updated_at,
    }


def list_all():
    return [_dict(r) for r in Temoignage.objects.order_by("ordre", "-created_at")]


def get(item_id):
    row = Temoignage.objects.filter(id=item_id).first()
    if not row:
        raise TemoignageError("Témoignage non trouvé", 404)
    return _dict(row)


def create(data):
    data = data or {}
    titre = _clean(data.get("titre"))
    if not titre:
        raise TemoignageError("Le titre est obligatoire")
    return Temoignage.objects.create(
        titre=titre,
        video_url=_clean(data.get("video_url")) or None,
        miniature=_clean(data.get("miniature")) or None,
        auteur=_clean(data.get("auteur")) or None,
        description=_clean(data.get("description")) or None,
        est_actif=_int(data.get("est_actif", 1), 1),
        ordre=_int(data.get("ordre")),
        created_at=timezone.now(),
        updated_at=timezone.now(),
    )


def update(item_id, data):
    data = data or {}
    row = Temoignage.objects.filter(id=item_id).first()
    if not row:
        raise TemoignageError("Témoignage non trouvé", 404)
    update_data = {}
    if "titre" in data:
        titre = _clean(data.get("titre"))
        if not titre:
            raise TemoignageError("Le titre est obligatoire")
        update_data["titre"] = titre
    for key in ("video_url", "miniature", "auteur", "description"):
        if key in data:
            update_data[key] = _clean(data.get(key)) or None
    if "est_actif" in data:
        update_data["est_actif"] = _int(data.get("est_actif"))
    if "ordre" in data:
        update_data["ordre"] = _int(data.get("ordre"))
    if not update_data:
        raise TemoignageError("Aucune donnée à modifier")
    update_data["updated_at"] = timezone.now()
    Temoignage.objects.filter(id=item_id).update(**update_data)
    return row, list(update_data.keys())


def delete(item_id):
    row = Temoignage.objects.filter(id=item_id).first()
    if not row:
        raise TemoignageError("Témoignage non trouvé", 404)
    row.delete()
    return row


def save_image(upload):
    if not upload:
        raise TemoignageError("Aucun fichier envoyé")
    allowed = {"image/jpeg": ".jpg", "image/png": ".png", "image/gif": ".gif", "image/webp": ".webp"}
    ext = allowed.get((upload.content_type or "").lower())
    if not ext:
        raise TemoignageError("Type non autorisé")
    content = upload.file.read()
    if len(content) > 5 * 1024 * 1024:
        raise TemoignageError("Fichier trop volumineux (max 5Mo)")
    folder = Path(settings.PROJECT_ROOT) / "attachments" / "Temoignages"
    folder.mkdir(parents=True, exist_ok=True)
    base = slugify(Path(upload.filename or "media").stem) or "media"
    filename = f"{base}_{int(time.time())}{ext}"
    (folder / filename).write_bytes(content)
    return f"attachments/Temoignages/{filename}"
