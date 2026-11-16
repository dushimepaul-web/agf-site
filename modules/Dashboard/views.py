from django.shortcuts import render

from core.decorators import staff_required
from core.helpers import json_success

from .services import dashboard_payload


@staff_required
def index(request):
    return render(request, "Dashboard/Dashboard_View.html", {"title": "Tableau de bord"})


@staff_required
def api_data(request):
    try:
        days = int(request.GET.get("days") or 30)
    except (TypeError, ValueError):
        days = 30
    if days <= 0:
        days = 30
    days = max(7, min(90, days))
    return json_success(dashboard_payload(days))


@staff_required
def api_filters(request):
    return json_success(
        {
            "periods": [
                {"days": 7, "label": "7 jours"},
                {"days": 30, "label": "30 jours"},
                {"days": 90, "label": "90 jours"},
            ]
        }
    )
