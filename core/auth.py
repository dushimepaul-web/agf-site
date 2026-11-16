import re
from datetime import datetime, timedelta

from .helpers import client_ip, user_agent
from .models import Log, Role, User, UserSession
from .security import hash_password, verify_password


def fetch_user_by_uuid(user_uuid):
    if not user_uuid:
        return None
    return User.objects.filter(uuid=user_uuid, deleted_at__isnull=True).first()


def user_is_valid(user):
    return bool(
        user
        and int(user.actif or 0) == 1
        and int(user.is_active or 0) == 1
        and user.deleted_at is None
    )


def fetch_role(role_id):
    if not role_id:
        return None
    return Role.objects.filter(id=role_id).first()


def authenticate(email, password):
    email = (email or "").strip()
    if not email or not password:
        return None
    user = User.objects.filter(
        email=email,
        actif=1,
        is_active=1,
        deleted_at__isnull=True,
    ).first()
    if user and verify_password(password, user.password_hash):
        return user
    return None


def start_session(request, user, role):
    request.session.cycle_key()
    request.session["id_utilisateur"] = int(user.id)
    request.session["uuid"] = user.uuid
    request.session["email"] = user.email
    request.session["nom_complet"] = f"{user.nom or ''} {user.prenom or ''}".strip()
    request.session["user"] = user.username
    request.session["id_role"] = int(user.role_id)
    request.session["role_code"] = role.code if role else ""
    request.session["role_libelle"] = role.nom if role else ""
    request.session["logged_in"] = True
    request.session.modified = True


def revalidate_session(request):
    if not request.session.get("logged_in"):
        return None
    user = fetch_user_by_uuid(request.session.get("uuid"))
    if not user_is_valid(user):
        request.session.flush()
        return None
    role = fetch_role(user.role_id)
    request.session["role_libelle"] = role.nom if role else ""
    request.session["role_code"] = role.code if role else ""
    request.session["id_role"] = int(user.role_id)
    request.session["nom_complet"] = user.full_name
    request.session["email"] = user.email
    request.session.modified = True
    return user


def session_payload(request):
    return {
        "id": request.session.get("id_utilisateur"),
        "uuid": request.session.get("uuid"),
        "email": request.session.get("email"),
        "full_name": request.session.get("nom_complet", ""),
        "username": request.session.get("user", ""),
        "role_id": request.session.get("id_role"),
        "role_code": request.session.get("role_code", ""),
        "role_label": request.session.get("role_libelle", ""),
        "logged_in": bool(request.session.get("logged_in")),
    }


def _device_info(request):
    ua = user_agent(request).lower()
    if re.search(r"bot|crawl|spider|slurp|bingpreview|facebookexternal", ua):
        device_type = "bot"
    elif any(token in ua for token in ("ipad", "tablet", "playbook", "silk")) or (
        "android" in ua and "mobile" not in ua
    ):
        device_type = "tablet"
    elif any(token in ua for token in ("mobile", "iphone", "ipod", "android")):
        device_type = "mobile"
    else:
        device_type = "desktop"

    if "windows" in ua:
        platform = "Windows"
    elif "android" in ua:
        platform = "Android"
    elif "iphone" in ua or "ipad" in ua or "ipod" in ua:
        platform = "iOS"
    elif "mac os" in ua or "macintosh" in ua:
        platform = "macOS"
    elif "linux" in ua:
        platform = "Linux"
    else:
        platform = ""

    for name, token in (
        ("Edge", "edg/"),
        ("Opera", "opr/"),
        ("Chrome", "chrome/"),
        ("Firefox", "firefox/"),
        ("Safari", "safari/"),
    ):
        if token in ua:
            browser = name
            break
    else:
        browser = ""

    return device_type, platform, browser


def create_user_session(request, user_id, session_key=None):
    now = datetime.now()
    device_type, platform, browser = _device_info(request)
    UserSession.objects.filter(user_id=user_id, is_active=1).update(
        is_active=0,
        logout_time=now,
        logout_reason="force",
    )
    UserSession.objects.create(
        user_id=user_id,
        session_id=session_key or request.session.session_key or "",
        ip_address=client_ip(request),
        user_agent=user_agent(request),
        device_type=device_type,
        platform=platform,
        browser=browser,
        login_time=now,
        last_activity=now,
        expiry_time=now + timedelta(hours=24),
        is_active=1,
        created_at=now,
    )


def close_user_sessions(user_id, reason="manual"):
    now = datetime.now()
    UserSession.objects.filter(user_id=user_id, is_active=1).update(
        is_active=0,
        logout_time=now,
        logout_reason=reason,
    )


def log_history(request, user_id, action, description=""):
    if not user_id:
        return
    Log.objects.create(
        user_id=user_id,
        action=action,
        description=description,
        ip_address=client_ip(request),
        user_agent=user_agent(request),
        url=request.build_absolute_uri(),
        method=(request.method or "GET").upper(),
        niveau="info",
        created_at=datetime.now(),
    )


def log_login_attempt(request, email, success=False, user_id=None):
    now = datetime.now()
    if success and user_id:
        User.objects.filter(id=user_id).update(
            last_login_at=now,
            last_login_ip=client_ip(request),
        )
    Log.objects.create(
        user_id=user_id,
        action="login_success" if success else "login_failed",
        description=f"Tentative de connexion avec l'email: {email}",
        ip_address=client_ip(request),
        user_agent=user_agent(request),
        url=request.build_absolute_uri(),
        method=(request.method or "GET").upper(),
        niveau="info" if success else "warning",
        created_at=now,
    )


def update_password(user, new_password):
    user.password_hash = hash_password(new_password)
    user.save(update_fields=["password_hash"])


def touch_last_login(user):
    now = datetime.now()
    User.objects.filter(id=user.id).update(derniere_connexion=now)
