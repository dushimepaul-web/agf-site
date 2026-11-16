from fastapi import APIRouter, Body, Depends, File, Request, UploadFile

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.Parametres import services


router = APIRouter(prefix="/admin", tags=["parametres"])


@router.get("/settings")
def settings_list(session=Depends(get_admin_session)):
    return ok(call(services.get_all))


@router.post("/settings")
def settings_update(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    call(services.update_many, payload)
    audit(request, session, "settings_updated", f"Mise à jour paramètres : {', '.join(payload.keys())}")
    return ok(None, "Paramètres mis à jour")


@router.post("/settings/upload/{kind}")
def settings_upload(
    kind: str,
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path = call(services.save_upload, file, kind)
    audit(request, session, "settings_upload", f"Upload {kind} : {path}")
    return ok({"path": path}, "Fichier mis à jour")
