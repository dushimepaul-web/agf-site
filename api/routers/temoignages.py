from fastapi import APIRouter, Body, Depends, File, Request, UploadFile

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.Temoignages import services


router = APIRouter(prefix="/admin", tags=["temoignages"])


@router.get("/testimonials")
def list_items(session=Depends(get_admin_session)):
    return ok(call(services.list_all))


@router.post("/testimonials/upload")
def upload_image(
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path = call(services.save_image, file)
    audit(request, session, "testimonial_upload", f"Upload témoignage {path}")
    return ok({"path": path}, "Fichier uploadé")


@router.get("/testimonials/{item_id}")
def get_item(item_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get, item_id))


@router.post("/testimonials")
def create_item(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create, payload)
    audit(request, session, "testimonial_created", f"Création témoignage {row.titre}")
    return ok({"id": row.id}, "Témoignage créé")


@router.post("/testimonials/{item_id}/update")
def update_item(
    item_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update, item_id, payload)
    audit(request, session, "testimonial_updated", f"Témoignage {row.titre} : {', '.join(keys)}")
    return ok(None, "Témoignage mis à jour")


@router.post("/testimonials/{item_id}/delete")
def delete_item(
    item_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete, item_id)
    audit(request, session, "testimonial_deleted", f"Suppression témoignage {row.titre}")
    return ok(None, "Témoignage supprimé")
