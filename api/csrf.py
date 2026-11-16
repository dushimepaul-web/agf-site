from fastapi import HTTPException, Request

from core.csrf import valid_csrf


async def require_csrf(request: Request):
    if (request.method or "GET").upper() in {"GET", "HEAD", "OPTIONS", "TRACE"}:
        return
    token = request.headers.get("X-CSRFToken") or request.headers.get("X-CSRF-Token")
    if not token:
        try:
            body = await request.json()
        except Exception:
            body = None
        if isinstance(body, dict):
            token = body.get("csrf_token")
    if not token or not valid_csrf(request, token):
        raise HTTPException(status_code=403, detail="Jeton CSRF manquant ou invalide")
