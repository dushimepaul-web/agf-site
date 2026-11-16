import json
import math
import uuid
from datetime import datetime

from django.conf import settings
from django.core.cache import cache
from django.http import JsonResponse

from .models import Setting


def get_setting(key, default=None):
    cache_key = f"agf:setting:{key}"
    cached = cache.get(cache_key)
    if cached is not None:
        return cached
    try:
        value = (
            Setting.objects.filter(clef=key, deleted_at__isnull=True)
            .values_list("valeur", flat=True)
            .first()
        )
    except Exception:
        value = None
    result = value if value is not None else default
    cache.set(cache_key, result, 300)
    return result


def generate_uuid():
    return str(uuid.uuid4())


def media_url(value, default="assets/images/logo.png"):
    value = (value or "").strip() or default
    if value.startswith(("http://", "https://", "//")):
        return value
    if value.startswith("/"):
        return value
    return "/" + value.lstrip("./")


def format_bytes(num, precision=2):
    if num in (0, None, ""):
        return "0 B"
    units = ["B", "KB", "MB", "GB", "TB"]
    num = max(int(num), 0)
    if num <= 0:
        return "0 B"
    power = min(int(math.floor(math.log(num) / math.log(1024))), len(units) - 1)
    value = num / (1024 ** power)
    return f"{round(value, precision)} {units[power]}"


def format_duration(seconds):
    if not seconds or seconds <= 0:
        return "0:00"
    seconds = int(seconds)
    hours, remainder = divmod(seconds, 3600)
    minutes, secs = divmod(remainder, 60)
    if hours > 0:
        return f"{hours}:{minutes:02d}:{secs:02d}"
    return f"{minutes}:{secs:02d}"


def get_type_badge(media_type):
    mapping = {
        "audio": {"label": "Audio", "class": "success", "icon": "bi bi-music-note-beamed"},
        "video": {"label": "Vidéo", "class": "danger", "icon": "bi bi-camera-reels"},
        "image": {"label": "Image", "class": "primary", "icon": "bi bi-image"},
        "document": {"label": "Document", "class": "warning", "icon": "bi bi-file-earmark"},
        "link": {"label": "Lien", "class": "info", "icon": "bi bi-link-45deg"},
    }
    return mapping.get(media_type, {"label": "Média", "class": "secondary", "icon": "bi bi-file"})


def client_ip(request):
    ip = request.META.get("REMOTE_ADDR", "")
    if getattr(settings, "TRUST_PROXY", False):
        forwarded = request.META.get("HTTP_X_FORWARDED_FOR", "")
        if forwarded:
            ip = forwarded.split(",")[0].strip()
    return ip or "127.0.0.1"


def user_agent(request):
    return request.META.get("HTTP_USER_AGENT", "") or ""


def request_method(request):
    return (request.method or "GET").upper()


def now_local():
    return datetime.now()


def truncate(value, length, suffix="…"):
    value = "" if value is None else str(value)
    return value if len(value) <= length else value[:length] + suffix


def read_json(request):
    try:
        body = request.body.decode("utf-8-sig")
    except Exception:
        return {}
    if not body:
        return {}
    try:
        data = json.loads(body)
    except (TypeError, ValueError):
        return {}
    return data if isinstance(data, dict) else {}


def json_success(data=None, message="Succès", status=200):
    return JsonResponse(
        {"success": True, "message": message, "data": data},
        status=status,
        json_dumps_params={"ensure_ascii": False},
    )


def json_error(message="Erreur", status=400):
    return JsonResponse(
        {"success": False, "message": message},
        status=status,
        json_dumps_params={"ensure_ascii": False},
    )
