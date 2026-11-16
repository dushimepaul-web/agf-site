from fastapi import APIRouter, Body, Depends, File, HTTPException, Request, UploadFile

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session, get_staff_session
from modules.Consultation import services


router = APIRouter(tags=["consultation"])


def _require_admin(session):
    if str(session.get("role_code") or "").upper() not in {"ADMIN", "SUPER_ADMIN"} and int(session.get("id_role") or 0) != 1:
        raise HTTPException(status_code=403, detail="Accès refusé : administration requise")


@router.get("/admin/consultations")
def consultations_list(session=Depends(get_staff_session)):
    return ok(call(services.consultations, session))


@router.get("/admin/consultations/{consultation_uuid}")
def consultations_get(consultation_uuid: str, session=Depends(get_staff_session)):
    return ok(call(services.get_consultation, consultation_uuid, session))


@router.post("/admin/consultations/{consultation_uuid}/update")
def consultations_update(
    consultation_uuid: str,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_staff_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_consultation, consultation_uuid, payload, session)
    audit(request, session, "consultation_updated", f"Consultation {row.numero_consultation} : {', '.join(keys)}")
    return ok(None, "Consultation mise à jour")


@router.post("/admin/consultations/{consultation_uuid}/delete")
def consultations_delete(
    consultation_uuid: str,
    request: Request,
    session=Depends(get_staff_session),
    _csrf=Depends(require_csrf),
):
    _require_admin(session)
    row = call(services.delete_consultation, consultation_uuid)
    audit(request, session, "consultation_deleted", f"Suppression consultation {row.numero_consultation}")
    return ok(None, "Consultation supprimée")


@router.get("/admin/consultation-experts")
def experts_list(session=Depends(get_admin_session)):
    return ok(call(services.medecins, False))


@router.post("/admin/consultation-experts")
def experts_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_medecin, payload)
    audit(request, session, "expert_created", f"Création expert #{row.id}")
    return ok({"id": row.id, "uuid": row.uuid}, "Expert créé")


@router.get("/admin/consultation-experts/{medecin_uuid}")
def experts_get(medecin_uuid: str, session=Depends(get_admin_session)):
    return ok(call(services.get_medecin_by_uuid, medecin_uuid))


@router.post("/admin/consultation-experts/{medecin_uuid}/update")
def experts_update(
    medecin_uuid: str,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_medecin, medecin_uuid, payload)
    audit(request, session, "expert_updated", f"Expert #{row.id} : {', '.join(keys)}")
    return ok(None, "Expert mis à jour")


@router.post("/admin/consultation-experts/{medecin_uuid}/delete")
def experts_delete(
    medecin_uuid: str,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_medecin, medecin_uuid)
    audit(request, session, "expert_deleted", f"Suppression expert #{row.id}")
    return ok(None, "Expert supprimé")


@router.get("/admin/patients")
def patients_list(session=Depends(get_staff_session)):
    return ok(call(services.patients, session))


@router.get("/admin/patients/detail")
def patients_detail(nom: str = "", prenom: str = "", whatsapp: str = "", session=Depends(get_staff_session)):
    return ok(call(services.patient_detail, nom, prenom, whatsapp or None, session))


@router.post("/consultation/submit")
def public_submit(
    request: Request,
    payload: dict = Body(default={}),
    _csrf=Depends(require_csrf),
):
    row = call(services.public_submit, payload, None, None, False)
    url = services.patient_whatsapp_url(row)
    return {
        "ok": True,
        "success": True,
        "id": row.id,
        "tracking_number": row.numero_consultation,
        "whatsapp_url": url,
        "data": {"id": row.id, "numero": row.numero_consultation, "whatsapp_url": url},
    }


@router.post("/consultation/upload-media")
def public_upload_media(
    request: Request,
    file: UploadFile = File(...),
    _csrf=Depends(require_csrf),
):
    path, filename = call(services.save_media, file)
    return ok({"path": path, "filename": filename}, "Fichier uploadé")


@router.post("/consultation/add-media")
def public_add_media(
    request: Request,
    payload: dict = Body(default={}),
    _csrf=Depends(require_csrf),
):
    row = call(services.add_media, payload)
    return ok({"id": row.id}, "Média ajouté")


@router.get("/consultation/whatsapp/{consultation_id}")
def public_whatsapp(consultation_id: int, session=Depends(get_staff_session)):
    url, whatsapp = call(services.whatsapp_url, consultation_id)
    return ok({"url": url, "whatsapp": whatsapp})
