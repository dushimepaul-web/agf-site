from django.urls import path

from . import views

urlpatterns = [
    path("ContactUs", views.contact_us, name="public_contact_us"),
    path("Faq", views.faq, name="public_faq"),
    path("Faq/add_edit", views.faq, name="public_faq_add"),
    path("Faq/add_edit/<int:edit_id>", views.faq, name="public_faq_edit"),
    path("Partenaires", views.partenaires, name="public_partenaires"),
    path("Partenaires/add_edit", views.partenaires, name="public_partenaires_add"),
    path("Partenaires/add_edit/<int:edit_id>", views.partenaires, name="public_partenaires_edit"),
    path("SocialLinks", views.social_links, name="public_social_links"),
]
