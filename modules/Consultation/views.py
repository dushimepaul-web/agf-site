import json

from django.http import Http404, HttpResponseRedirect, JsonResponse
from django.shortcuts import render
from django.views.decorators.http import require_POST

from core.decorators import admin_required, staff_required
from core.models import User

from . import services


@staff_required
def admin_list(request):
    role = str(request.session.get("role_code") or "").upper()
    title = "Mes consultations" if role == "MEDECIN" else "Gestion des consultations"
    is_admin_user = role in {"ADMIN", "SUPER_ADMIN"} or int(request.session.get("id_role") or 0) == 1
    return render(
        request,
        "Consultation/admin_list.html",
        {"title": title, "is_admin_user": is_admin_user},
    )


@admin_required
def experts(request, edit_uuid=None):
    users = User.objects.filter(deleted_at__isnull=True).order_by("nom", "prenom")
    return render(
        request,
        "Consultation/experts.html",
        {"title": "Gestion des experts", "users": users, "edit_uuid": edit_uuid or ""},
    )


@staff_required
def patients(request):
    return render(request, "Consultation/patients.html", {"title": "Gestion des patients"})


def public_index(request):
    return render(request, "Consultation/public_index.html", {"title": "Consultation en ligne", "medecins": services.medecins(True)})


def _medecin_for_form(request):
    medecin = None
    for lookup in (
        request.GET.get("doctor_uuid"),
        request.session.get("pending_doctor"),
        request.GET.get("medecin_id"),
    ):
        if not lookup:
            continue
        try:
            medecin = services.get_medecin_by_uuid(lookup) if not str(lookup).isdigit() else services.get_medecin(lookup)
        except services.ConsultationError:
            medecin = None
        if medecin:
            break
    if not medecin:
        available = services.medecins(True)
        if not available:
            raise Http404("Aucun expert disponible")
        medecin = available[0]
    request.session["pending_doctor"] = medecin["uuid"]
    return medecin


def _form_context(request, medecin, error=""):
    return {
        "title": f"Consultation - Dr. {medecin['prenom']} {medecin['nom']}",
        "medecin": medecin,
        "horaires": services.get_horaires(medecin["id"]),
        "payment_methods": services.payment_methods(),
        "form_error": error,
        "form_action": "/patient-form/api_submit",
    }


def public_patient_form(request):
    medecin = _medecin_for_form(request)
    return render(request, "Consultation/public_form.html", _form_context(request, medecin))


def public_detail(request, medecin_id):
    try:
        medecin = services.get_medecin(medecin_id)
    except services.ConsultationError:
        raise Http404("Expert introuvable")
    return render(
        request,
        "Consultation/public_detail.html",
        {"title": f"Dr. {medecin['prenom']} {medecin['nom']}", "medecin": medecin, "horaires": services.get_horaires(medecin_id)},
    )


def public_form(request, medecin_id):
    try:
        medecin = services.get_medecin(medecin_id)
    except services.ConsultationError:
        raise Http404("Expert introuvable")
    return render(request, "Consultation/public_form.html", _form_context(request, medecin))


def _request_data(request):
    if request.content_type and "application/json" in request.content_type:
        try:
            return json.loads(request.body or "{}")
        except json.JSONDecodeError:
            return {}
    return request.POST


def _submission_response(row):
    url = services.patient_whatsapp_url(row)
    return {
        "ok": True,
        "success": True,
        "id": row.id,
        "tracking_number": row.numero_consultation,
        "whatsapp_url": url,
        "data": {"id": row.id, "numero": row.numero_consultation, "whatsapp_url": url},
    }


@require_POST
def patient_form_submit(request):
    try:
        row = services.public_submit(_request_data(request), request.FILES, request, strict=False)
    except services.ConsultationError as exc:
        return JsonResponse({"ok": False, "success": False, "error": exc.message, "message": exc.message}, status=exc.status)
    return JsonResponse(_submission_response(row))


@require_POST
def patient_form_create(request):
    try:
        row = services.public_submit(_request_data(request), request.FILES, request, strict=True)
    except services.ConsultationError as exc:
        medecin = _medecin_for_form(request)
        return render(
            request,
            "Consultation/public_form.html",
            _form_context(request, medecin, error=exc.message),
            status=exc.status,
        )
    return HttpResponseRedirect(services.patient_whatsapp_url(row) or "/Consultation")


@require_POST
def patient_form_change_doctor(request):
    for key in ("pending_doctor", "consultation_form_data", "temp_files", "uploaded_files"):
        request.session.pop(key, None)
    return HttpResponseRedirect("/doctor")


def patient_form_countries(request):
    return JsonResponse(services.countries_for_form(request.GET.get("q", "")), safe=False)
