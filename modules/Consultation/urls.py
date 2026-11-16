from django.urls import path

from . import views

urlpatterns = [
    path("Consultations", views.admin_list, name="consultations"),
    path("Consultations/medecins", views.experts, name="consultation_experts"),
    path("Consultations/medecin_form", views.experts, name="consultation_expert_add"),
    path("Consultations/medecin_form/<str:edit_uuid>", views.experts, name="consultation_expert_edit"),
    path("Patients", views.patients, name="consultation_patients"),
    path("Consultation", views.public_index, name="public_consultation"),
    path("Consultation/detail/<int:medecin_id>", views.public_detail, name="public_consultation_detail"),
    path("Consultation/formulaire/<int:medecin_id>", views.public_form, name="public_consultation_form"),
]
