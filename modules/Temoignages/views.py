from django.shortcuts import render

from core.decorators import admin_required


@admin_required
def index(request):
    return render(request, "Temoignages/index.html", {"title": "Gestion des témoignages"})
