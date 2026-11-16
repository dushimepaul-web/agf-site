from django.shortcuts import render

from core.decorators import admin_required

from . import services


@admin_required
def categories(request, edit_id=None):
    return render(
        request,
        "Produits/categories.html",
        {"title": "Catégories de produits", "edit_id": edit_id or ""},
    )


@admin_required
def products(request, edit_id=None):
    return render(
        request,
        "Produits/produits.html",
        {
            "title": "Gestion des produits",
            "edit_id": edit_id or "",
            "categories": services.list_categories(),
        },
    )


@admin_required
def units(request, edit_id=None):
    return render(
        request,
        "Produits/unites.html",
        {"title": "Unités d'affaires", "edit_id": edit_id or ""},
    )
