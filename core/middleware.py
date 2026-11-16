from django.http import JsonResponse
from django.shortcuts import redirect

from .auth import revalidate_session


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
