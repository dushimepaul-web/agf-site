from django.shortcuts import render

from core.decorators import admin_required


@admin_required
def investors(request, edit_id=None):
    return render(request, "Finance/investors.html", {"title": "Investisseurs", "edit_id": edit_id or ""})


@admin_required
def brokers(request, edit_id=None):
    return render(request, "Finance/brokers.html", {"title": "Intermédiaires financiers", "edit_id": edit_id or ""})
