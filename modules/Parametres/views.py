from django.shortcuts import render

from core.decorators import admin_required


@admin_required
def index(request):
    return render(request, "Parametres/index.html", {"title": "Paramètres"})
