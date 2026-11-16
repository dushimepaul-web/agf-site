from django.conf import settings
from django.middleware.csrf import (
    CSRF_SECRET_LENGTH,
    CSRF_TOKEN_LENGTH,
    InvalidTokenFormat,
    _check_token_format,
    _does_token_match,
    _unmask_cipher_token,
)


def _cookies(request):
    cookies = getattr(request, "COOKIES", None)
    if cookies is not None:
        return cookies
    return getattr(request, "cookies", {})


def valid_csrf(request, token=None):
    if (request.method or "GET").upper() in {"GET", "HEAD", "OPTIONS", "TRACE"}:
        return True
    secret = _cookies(request).get(settings.CSRF_COOKIE_NAME)
    token = token or request.headers.get("X-CSRFToken") or request.headers.get("X-CSRF-Token")
    if not secret or not token:
        return False
    try:
        _check_token_format(secret)
        _check_token_format(token)
    except InvalidTokenFormat:
        return False
    if len(token) not in (CSRF_SECRET_LENGTH, CSRF_TOKEN_LENGTH):
        return False
    if len(secret) == CSRF_TOKEN_LENGTH:
        secret = _unmask_cipher_token(secret)
    return _does_token_match(token, secret)
