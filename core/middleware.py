from django.http import JsonResponse
from django.shortcuts import redirect

from .auth import revalidate_session

LANGUAGE_CODES = frozenset(
    {
        "en", "fr", "sw", "rn", "zh-CN", "es", "hi", "ar", "bn", "pt", "ru",
        "ur", "id", "de", "ja", "ms", "tr", "ko", "vi", "it", "fa", "ta", "th",
        "pl", "nl", "uk", "el", "he", "am", "so", "yo", "ha", "ig", "zu", "af",
        "km", "ne", "mr", "te", "kn", "gu", "pa", "da", "no", "fi", "cs", "hu",
        "sv", "ro",
    }
)


def split_language_prefix(path):
    """Return (language, remaining_path) for a /<lang>/... path."""
    if not path or path == "/":
        return None, path
    head, sep, tail = path.lstrip("/").partition("/")
    if head in LANGUAGE_CODES:
        return head, "/" + tail if sep else "/"
    return None, path


class LanguagePrefixMiddleware:
    """Accept the cosmetic /<lang>/... URLs produced by the language switcher.

    Translation is done client side through Google Translate, so Django does
    not expose i18n_patterns. Without this middleware the prefixed URLs
    (e.g. /fr/credit-summary) reach the resolver unchanged and return 404.
    """

    COOKIE_NAME = "agf_lang"
    COOKIE_MAX_AGE = 60 * 60 * 24 * 365

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        lang, path = split_language_prefix(request.path_info)
        if lang is None:
            return self.get_response(request)

        request.path_info = path
        request.path = path
        request.META["PATH_INFO"] = path
        request.LANGUAGE_PREFIX = lang
        if not request.COOKIES.get(self.COOKIE_NAME):
            request.COOKIES[self.COOKIE_NAME] = lang

        response = self.get_response(request)
        if not response.cookies.get(self.COOKIE_NAME):
            response.set_cookie(
                self.COOKIE_NAME,
                lang,
                max_age=self.COOKIE_MAX_AGE,
                path="/",
                samesite="Lax",
            )
        return response


class SessionRevalidationMiddleware:
    """Mirror of MY_Controller::__construct account revalidation.

    A soft-deleted, disabled or role-less account must lose access even when
    its session cookie is still valid.
    """

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        if request.session.get("logged_in"):
            user = revalidate_session(request)
            if user is None:
                is_api = request.path.startswith("/api/") or request.headers.get("X-Requested-With") == "XMLHttpRequest"
                if is_api:
                    return JsonResponse(
                        {"success": False, "message": "Compte désactivé ou session expirée"},
                        status=401,
                    )
                return redirect("/Admin")
        return self.get_response(request)
