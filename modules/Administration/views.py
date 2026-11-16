from django.shortcuts import redirect, render

from core.decorators import admin_required
from core.models import Menu, Role


@admin_required
def users(request):
    roles = list(Role.objects.order_by("nom").values("id", "nom"))
    return render(
        request,
        "Administration/users.html",
        {"title": "Gestion des utilisateurs", "roles": roles},
    )


@admin_required
def roles(request):
    return render(
        request,
        "Administration/roles.html",
        {"title": "Gestion des rôles"},
    )


@admin_required
def menus(request):
    menus = list(Menu.objects.order_by("ordre").values("id_menu", "libelle"))
    return render(
        request,
        "Administration/menus.html",
        {"title": "Gestion des menus", "menus": menus},
    )


@admin_required
def sessions(request):
    return render(
        request,
        "Administration/sessions.html",
        {"title": "Sessions utilisateurs"},
    )


@admin_required
def logs(request):
    return render(
        request,
        "Administration/logs.html",
        {"title": "Journaux d'activité"},
    )


@admin_required
def visitors(request):
    return render(
        request,
        "Administration/visitors.html",
        {"title": "Journal des visiteurs"},
    )


@admin_required
def permissions(request):
    return redirect("/Roles")
