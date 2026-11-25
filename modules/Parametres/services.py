import hashlib
import time
from pathlib import Path

from django.conf import settings
from django.core.cache import cache
from django.utils import timezone

from core.helpers import generate_uuid
from core.models import Setting


class ParametreError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


UPLOAD_RULES = {
    "logo": ("logo_app", {"jpg", "jpeg", "png", "gif", "webp"}),
    "favicon": ("favicon_ico", {"png", "ico", "jpg", "jpeg", "webp"}),
    "login_img": ("login_img", {"jpg", "jpeg", "png", "gif", "webp"}),
    "home_about": ("home_about_image", {"jpg", "jpeg", "png", "gif", "webp"}),
}


def get_all():
    settings_map = {}
    for row in Setting.objects.filter(deleted_at__isnull=True):
        settings_map[row.clef] = row.valeur
    return settings_map


def set_value(key, value):
    row = Setting.objects.filter(clef=key, deleted_at__isnull=True).first()
    if row:
        Setting.objects.filter(id=row.id).update(valeur=value, modifie_le=timezone.now())
        cache.delete(f"agf:setting:{key}")
        return True
    Setting.objects.create(
        clef=key,
        valeur=value,
        uuid=generate_uuid(),
        cree_le=timezone.now(),
        modifie_le=timezone.now(),
    )
    cache.delete(f"agf:setting:{key}")
    return True


def update_many(data):
    if not isinstance(data, dict) or not data:
        raise ParametreError("Données invalides")
    allowed = set(Setting.objects.filter(deleted_at__isnull=True).values_list("clef", flat=True))
    failures = []
    for key, value in data.items():
        if isinstance(value, (dict, list)):
            continue
        if key not in allowed and not key.startswith("email_"):
            continue
        if not set_value(key, "" if value is None else str(value)):
            failures.append(key)
    if failures:
        raise ParametreError("Erreur lors de la mise à jour de : " + ", ".join(failures))
    return True


def save_upload(upload, kind):
    if kind not in UPLOAD_RULES:
        raise ParametreError("Type d'upload invalide", 404)
    if not upload:
        raise ParametreError("Aucun fichier reçu")
    setting_key, allowed_ext = UPLOAD_RULES[kind]
    filename = upload.filename or "image"
    ext = Path(filename).suffix.lower().lstrip(".")
    if ext not in allowed_ext:
        raise ParametreError("Format non autorisé")
    content = upload.file.read()
    if len(content) > 5 * 1024 * 1024:
        raise ParametreError("Fichier trop volumineux (max 5 Mo)")
    folder = Path(settings.PROJECT_ROOT) / "assets" / "uploads" / "logo"
    folder.mkdir(parents=True, exist_ok=True)
    new_name = f"{hashlib.md5(f'{time.time()}-{filename}'.encode()).hexdigest()}.{ext}"
    (folder / new_name).write_bytes(content)
    path = f"assets/uploads/logo/{new_name}"
    old = Setting.objects.filter(clef=setting_key, deleted_at__isnull=True).values_list("valeur", flat=True).first()
    set_value(setting_key, path)
    if old and old != path and str(old).startswith("assets/uploads/"):
        old_file = Path(settings.PROJECT_ROOT) / old
        try:
            if old_file.is_file():
                old_file.unlink()
        except OSError:
            pass
    return path
