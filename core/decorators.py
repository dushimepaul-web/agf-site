from functools import wraps

from django.http import HttpResponseForbidden, JsonResponse
from django.shortcuts import redirect

from .permissions import is_admin, is_staff


def _is_api(request):
    return request.path.startswith("/api/") or request.headers.get("X-Requested-With") == "XMLHttpRequest"


def login_required(view):
    @wraps(view)
    def wrapper(request, *args, **kwargs):
        if not request.session.get("logged_in"):
            if _is_api(request):
                return JsonResponse({"success": False, "message": "Session expirée"}, status=401)
            return redirect("/Admin")
        return view(request, *args, **kwargs)

    return wrapper


def admin_required(view):
    @wraps(view)
    @login_required
    def wrapper(request, *args, **kwargs):
        if not is_admin(request.session.get("role_code"), request.session.get("id_role")):
            if _is_api(request):
                return JsonResponse(
                    {"success": False, "message": "Accès refusé : privilèges administrateur requis"},
                    status=403,
                )
            return HttpResponseForbidden("Accès refusé : privilèges administrateur requis")
        return view(request, *args, **kwargs)

    return wrapper


def staff_required(view):
    @wraps(view)
    @login_required
    def wrapper(request, *args, **kwargs):
        if not is_staff(request.session.get("role_code"), request.session.get("id_role")):
            if _is_api(request):
                return JsonResponse(
                    {"success": False, "message": "Accès refusé : privilèges administrateur ou médecin requis"},
                    status=403,
                )
            return HttpResponseForbidden("Accès refusé : privilèges administrateur ou médecin requis")
        return view(request, *args, **kwargs)

    return wrapper


def require_post(view):
    @wraps(view)
    def wrapper(request, *args, **kwargs):
        if (request.method or "GET").upper() != "POST":
            return JsonResponse({"success": False, "message": "Méthode non autorisée (POST requis)"}, status=405)
        return view(request, *args, **kwargs)

    return wrapper
