from fastapi import APIRouter, Body, Depends, File, Form, Request, UploadFile

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.admin_galerie import services


router = APIRouter(prefix="/admin", tags=["media"])


@router.get("/media/stats")
def stats(session=Depends(get_admin_session)):
    return ok(call(services.statistics))


@router.get("/media/categories")
def categories(session=Depends(get_admin_session)):
    return ok(call(services.categories))


@router.get("/media")
def list_items(
    page: int = 1,
    limit: int = 24,
    type: str = "",
    search: str = "",
    session=Depends(get_admin_session),
):
    return ok(call(services.list_media, page, limit, type, search))


@router.post("/media/create")
def create_item(
    request: Request,
    titre: str = Form(""),
    type: str = Form("autre"),
    sous_type: str = Form(""),
    lien: str = Form(""),
    miniature: str = Form(""),
    description: str = Form(""),
    categorie: str = Form(""),
    date_media: str = Form(""),
    credits: str = Form(""),
    est_actif: int = Form(1),
    is_for_website: int = Form(0),
    is_for_whatsapp: int = Form(0),
    file: UploadFile = File(None),
    thumbnail_file: UploadFile = File(None),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    payload = {
        "titre": titre,
        "type": type,
        "sous_type": sous_type,
        "lien": lien,
        "miniature": miniature,
        "description": description,
        "categorie": categorie,
        "date_media": date_media,
        "credits": credits,
        "est_actif": est_actif,
        "is_for_website": is_for_website,
        "is_for_whatsapp": is_for_whatsapp,
    }
    row = call(services.create_media, payload, file, thumbnail_file)
    audit(request, session, "media_created", f"Création média {row.titre}")
    return ok({"id": row.id_media}, "Média créé")


@router.post("/media/upload")
def upload_file(
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path, size, mime = call(services.save_temp_file, file)
    audit(request, session, "media_upload", f"Upload média {path}")
    return ok({"path": path, "taille": size, "mime_type": mime}, "Fichier uploadé")


@router.get("/media/{media_id}")
def get_item(media_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_media, media_id))


@router.post("/media/{media_id}/update")
def update_item(
    media_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_media, media_id, payload)
    audit(request, session, "media_updated", f"Média {row.titre} : {', '.join(keys)}")
    return ok(None, "Média mis à jour")


@router.post("/media/{media_id}/thumbnail")
def upload_thumbnail(
    media_id: int,
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path = call(services.save_thumbnail, file)
    row, _ = call(services.update_media, media_id, {"miniature": path})
    audit(request, session, "media_thumbnail", f"Miniature média {row.titre}")
    return ok({"path": path}, "Miniature mise à jour")


@router.post("/media/{media_id}/toggle")
def toggle_item(
    media_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.toggle_field, media_id, payload.get("field"), payload.get("value"))
    audit(request, session, "media_toggled", f"Média {row.titre} : {payload.get('field')}")
    return ok(None, "Statut mis à jour")


@router.post("/media/{media_id}/delete")
def delete_item(
    media_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_media, media_id)
    audit(request, session, "media_deleted", f"Suppression média {row.titre}")
    return ok(None, "Média supprimé")
