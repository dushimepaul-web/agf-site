from datetime import datetime

from django.conf import settings
from fastapi import HTTPException, Request

from core.models import Log


def ok(data=None, message="Succès"):
    return {"success": True, "message": message, "data": data}


def call(fn, *args):
    try:
        return fn(*args)
    except Exception as exc:
        if hasattr(exc, "status") and hasattr(exc, "message"):
            raise HTTPException(status_code=exc.status, detail=exc.message)
        raise


def ip(request: Request):
    ip_address = request.client.host if request.client else "127.0.0.1"
    if getattr(settings, "TRUST_PROXY", False):
        forwarded = request.headers.get("x-forwarded-for", "")
        if forwarded:
            ip_address = forwarded.split(",")[0].strip()
    return ip_address


def audit(request: Request, session, action, description):
    user_id = session.get("id_utilisateur")
    if not user_id:
        return
    Log.objects.create(
        user_id=user_id,
        action=action,
        description=description,
        ip_address=ip(request),
        user_agent=request.headers.get("user-agent", ""),
        url=str(request.url),
        method=(request.method or "GET").upper(),
        niveau="info",
        created_at=datetime.now(),
    )
