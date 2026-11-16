from django.shortcuts import render

from core.decorators import admin_required


@admin_required
def contact_us(request):
    return render(request, "Public/contact_us.html", {"title": "Messages de contact"})


@admin_required
def faq(request, edit_id=None):
    return render(request, "Public/faq.html", {"title": "Questions fréquentes", "edit_id": edit_id or ""})


@admin_required
def partenaires(request, edit_id=None):
    return render(request, "Public/partenaires.html", {"title": "Partenaires", "edit_id": edit_id or ""})


@admin_required
def social_links(request):
    return render(request, "Public/social_links.html", {"title": "Réseaux sociaux"})
