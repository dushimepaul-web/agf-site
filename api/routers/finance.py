from fastapi import APIRouter, Body, Depends, Request

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.Finance import services


router = APIRouter(prefix="/admin", tags=["finance"])


@router.get("/investors")
def investors_list(session=Depends(get_admin_session)):
    return ok(call(services.investors))


@router.get("/investors/{investor_id}")
def investors_get(investor_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_investor, investor_id))


@router.post("/investors")
def investors_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_investor, payload)
    audit(request, session, "investor_created", f"Création investisseur {row.full_name}")
    return ok({"id": row.id}, "Investisseur créé")


@router.post("/investors/{investor_id}/update")
def investors_update(
    investor_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_investor, investor_id, payload)
    audit(request, session, "investor_updated", f"Investisseur {row.full_name} : {', '.join(keys)}")
    return ok(None, "Investisseur mis à jour")


@router.post("/investors/{investor_id}/delete")
def investors_delete(
    investor_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_investor, investor_id)
    audit(request, session, "investor_deleted", f"Suppression investisseur {row.full_name}")
    return ok(None, "Investisseur supprimé")


@router.get("/brokers")
def brokers_list(session=Depends(get_admin_session)):
    return ok(call(services.brokers))


@router.get("/brokers/{broker_id}")
def brokers_get(broker_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_broker, broker_id))


@router.post("/brokers")
def brokers_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_broker, payload)
    audit(request, session, "broker_created", f"Création intermédiaire {row.firm_name}")
    return ok({"id": row.id}, "Intermédiaire créé")


@router.post("/brokers/{broker_id}/update")
def brokers_update(
    broker_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_broker, broker_id, payload)
    audit(request, session, "broker_updated", f"Intermédiaire {row.firm_name} : {', '.join(keys)}")
    return ok(None, "Intermédiaire mis à jour")


@router.post("/brokers/{broker_id}/delete")
def brokers_delete(
    broker_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_broker, broker_id)
    audit(request, session, "broker_deleted", f"Suppression intermédiaire {row.firm_name}")
    return ok(None, "Intermédiaire supprimé")
