from fastapi import APIRouter, Body, Depends, HTTPException

from api.csrf import require_csrf
from api.deps import get_session
from core.auth import fetch_user_by_uuid, update_password
from core.models import User
from core.security import password_strength_error, verify_password

router = APIRouter(tags=["profile"])


@router.post("/profile/update")
def profile_update(
    payload: dict = Body(default={}),
    session=Depends(get_session),
    _csrf=Depends(require_csrf),
):
    user_uuid = session.get("uuid")
    if not any(payload.get(key) for key in ("nom", "prenom", "email")):
        return {"success": False, "message": "Aucune donnée à modifier"}

    update = {}
    if "nom" in payload:
        nom = str(payload.get("nom") or "").strip()
        if not nom:
            return {"success": False, "message": "Le nom est obligatoire"}
        update["nom"] = nom
    if "prenom" in payload:
        update["prenom"] = str(payload.get("prenom") or "").strip()
    if "email" in payload:
        email = str(payload.get("email") or "").strip()
        if not email:
            return {"success": False, "message": "L'email est obligatoire"}
        if User.objects.filter(email=email).exclude(uuid=user_uuid).exists():
            return {"success": False, "message": "Cet email est déjà utilisé"}
        update["email"] = email

    if update:
        User.objects.filter(uuid=user_uuid).update(**update)

    user = fetch_user_by_uuid(user_uuid)
    if user:
        session["nom_complet"] = user.full_name
        session["email"] = user.email
        session.save()
    return {"success": True, "message": "Profil mis à jour", "data": None}


@router.post("/profile/change_password")
def profile_change_password(
    payload: dict = Body(default={}),
    session=Depends(get_session),
    _csrf=Depends(require_csrf),
):
    current = str(payload.get("current_password") or "")
    new_password = str(payload.get("new_password") or "")
    confirm = str(payload.get("confirm_password") or "")
    if not current or not new_password or not confirm:
        return {"success": False, "message": "Tous les champs sont requis"}
    if new_password != confirm:
        return {"success": False, "message": "Les mots de passe ne correspondent pas"}
    pwd_error = password_strength_error(new_password)
    if pwd_error:
        return {"success": False, "message": pwd_error}

    user = fetch_user_by_uuid(session.get("uuid"))
    if not user or not verify_password(current, user.password_hash):
        raise HTTPException(status_code=400, detail="Mot de passe actuel incorrect")
    update_password(user, new_password)
    return {"success": True, "message": "Mot de passe changé avec succès", "data": None}
