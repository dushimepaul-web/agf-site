from django.shortcuts import render

from core.decorators import admin_required

from .services import ALLOWED_TYPES


@admin_required
def index(request, media_type=None):
    if media_type not in ALLOWED_TYPES:
        media_type = ""
    return render(
        request,
        "admin_galerie/index.html",
        {"title": "Médiathèque", "current_type": media_type},
    )
