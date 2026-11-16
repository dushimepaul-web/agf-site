from django.conf import settings
from django.contrib.sessions.backends.db import SessionStore
from fastapi import Depends, HTTPException, Request

from core.auth import fetch_role, fetch_user_by_uuid, user_is_valid
from core.permissions import is_admin, is_staff


def get_session(request: Request) -> SessionStore:
    session_key = request.cookies.get(settings.SESSION_COOKIE_NAME)
    if not session_key:
        raise HTTPException(status_code=401, detail="Session expirée")

    session = SessionStore(session_key=session_key)
    if not session.get("logged_in"):
        raise HTTPException(status_code=401, detail="Session expirée")

    user = fetch_user_by_uuid(session.get("uuid"))
    if not user_is_valid(user):
        session.flush()
        raise HTTPException(status_code=401, detail="Compte désactivé ou session expirée")

    role = fetch_role(user.role_id)
    session["role_libelle"] = role.nom if role else ""
    session["role_code"] = role.code if role else ""
    session["id_role"] = int(user.role_id)
    session["nom_complet"] = user.full_name
    session["email"] = user.email
    session.save()

    request.state.session = session
    request.state.user = user
    return session


def get_admin_session(session: SessionStore = Depends(get_session)) -> SessionStore:
    if not is_admin(session.get("role_code"), session.get("id_role")):
        raise HTTPException(
            status_code=403,
            detail="Accès refusé : privilèges administrateur requis",
        )
    return session


def get_staff_session(session: SessionStore = Depends(get_session)) -> SessionStore:
    if not is_staff(session.get("role_code"), session.get("id_role")):
        raise HTTPException(
            status_code=403,
            detail="Accès refusé : privilèges administrateur ou médecin requis",
        )
    return session
