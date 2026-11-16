from datetime import datetime

from django.conf import settings
from fastapi import APIRouter, Body, Depends, HTTPException, Request

from api.csrf import require_csrf
from api.deps import get_admin_session
from core.models import Log
from modules.Administration import services
from modules.Administration.services import AdminError


router = APIRouter(prefix="/admin", tags=["administration"])


def _ok(data=None, message="Succès"):
    return {"success": True, "message": message, "data": data}


def _ip(request: Request):
    ip_address = request.client.host if request.client else "127.0.0.1"
    if getattr(settings, "TRUST_PROXY", False):
        forwarded = request.headers.get("x-forwarded-for", "")
        if forwarded:
            ip_address = forwarded.split(",")[0].strip()
    return ip_address


def _audit(request: Request, session, action, description):
    user_id = session.get("id_utilisateur")
    if not user_id:
        return
    Log.objects.create(
        user_id=user_id,
        action=action,
        description=description,
        ip_address=_ip(request),
        user_agent=request.headers.get("user-agent", ""),
        url=str(request.url),
        method=(request.method or "GET").upper(),
        niveau="info",
        created_at=datetime.now(),
    )


def _call(fn, *args):
    try:
        return fn(*args)
    except AdminError as exc:
        raise HTTPException(status_code=exc.status, detail=exc.message)


@router.get("/users")
def users_list(session=Depends(get_admin_session)):
    return _ok(_call(services.list_users))


@router.get("/users/{user_uuid}")
def users_get(user_uuid: str, session=Depends(get_admin_session)):
    return _ok(_call(services.get_user, user_uuid))


@router.post("/users")
def users_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    user = _call(services.create_user, payload)
    _audit(
        request,
        session,
        "user_created",
        f"Création de l'utilisateur {user.email} (id {user.id})",
    )
    return _ok({"id": user.id}, "Utilisateur créé")


@router.post("/users/{user_uuid}/update")
def users_update(
    user_uuid: str,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing, keys = _call(services.update_user, user_uuid, payload)
    _audit(
        request,
        session,
        "user_updated",
        f"Mise à jour de l'utilisateur {existing.email} : {', '.join(keys)}",
    )
    return _ok(None, "Utilisateur mis à jour")


@router.post("/users/{user_uuid}/delete")
def users_delete(
    user_uuid: str,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing = _call(services.delete_user, user_uuid)
    _audit(
        request,
        session,
        "user_deleted",
        f"Suppression de l'utilisateur {existing.email} (id {existing.id})",
    )
    return _ok(None, "Utilisateur supprimé")


@router.get("/roles")
def roles_list(session=Depends(get_admin_session)):
    return _ok(_call(services.list_roles))


@router.get("/roles/{role_uuid}")
def roles_get(role_uuid: str, session=Depends(get_admin_session)):
    return _ok(_call(services.get_role, role_uuid))


@router.post("/roles")
def roles_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    role = _call(services.create_role, payload)
    _audit(
        request,
        session,
        "role_created",
        f"Création du rôle {role.code} (id {role.id})",
    )
    return _ok({"id": role.id}, "Rôle créé")


@router.post("/roles/{role_uuid}/update")
def roles_update(
    role_uuid: str,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing, keys = _call(services.update_role, role_uuid, payload)
    _audit(
        request,
        session,
        "role_updated",
        f"Mise à jour du rôle {existing.code} : {', '.join(keys)}",
    )
    return _ok(None, "Rôle mis à jour")


@router.post("/roles/{role_uuid}/delete")
def roles_delete(
    role_uuid: str,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing = _call(services.delete_role, role_uuid)
    _audit(
        request,
        session,
        "role_deleted",
        f"Suppression du rôle {existing.code} (id {existing.id})",
    )
    return _ok(None, "Rôle supprimé")


@router.get("/menus")
def menus_list(session=Depends(get_admin_session)):
    return _ok(_call(services.list_menus))


@router.get("/menus/{menu_uuid}")
def menus_get(menu_uuid: str, session=Depends(get_admin_session)):
    return _ok(_call(services.get_menu, menu_uuid))


@router.post("/menus")
def menus_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    menu = _call(services.create_menu, payload)
    _audit(
        request,
        session,
        "menu_created",
        f"Création du menu {menu.code} (id {menu.id_menu})",
    )
    return _ok({"id_menu": menu.id_menu}, "Menu créé")


@router.post("/menus/{menu_uuid}/update")
def menus_update(
    menu_uuid: str,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing, keys = _call(services.update_menu, menu_uuid, payload)
    _audit(
        request,
        session,
        "menu_updated",
        f"Mise à jour du menu {existing.code} : {', '.join(keys)}",
    )
    return _ok(None, "Menu mis à jour")


@router.post("/menus/{menu_uuid}/delete")
def menus_delete(
    menu_uuid: str,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing = _call(services.delete_menu, menu_uuid)
    _audit(
        request,
        session,
        "menu_deleted",
        f"Suppression du menu {existing.code} (id {existing.id_menu})",
    )
    return _ok(None, "Menu supprimé")


@router.get("/sessions")
def sessions_list(session=Depends(get_admin_session)):
    return _ok(_call(services.list_sessions))


@router.post("/sessions/{session_id}/terminate")
def sessions_terminate(
    session_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    existing = _call(services.terminate_session, session_id)
    _audit(
        request,
        session,
        "session_terminated",
        f"Session #{session_id} de l'utilisateur {existing.user_id} clôturée par l'administrateur",
    )
    return _ok(None, "Session terminée")


@router.get("/logs")
def logs_list(
    limit: int = 100,
    offset: int = 0,
    user_id: int | None = None,
    action: str | None = None,
    niveau: str | None = None,
    date_from: str | None = None,
    date_to: str | None = None,
    session=Depends(get_admin_session),
):
    filters = {
        "user_id": user_id,
        "action": action,
        "niveau": niveau,
        "date_from": date_from,
        "date_to": date_to,
    }
    return _ok(_call(services.list_logs, limit, offset, filters))


@router.post("/logs/clear")
def logs_clear(
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    _call(services.clear_logs, session.get("email"))
    return _ok(None, "Journaux effacés")


@router.get("/logs/{log_id}")
def logs_get(log_id: int, session=Depends(get_admin_session)):
    return _ok(_call(services.get_log, log_id))


@router.get("/visitors")
def visitors_list(session=Depends(get_admin_session)):
    return _ok(_call(services.list_visitors))


@router.get("/visitors/stats")
def visitors_stats(session=Depends(get_admin_session)):
    return _ok(_call(services.visitor_stats))
