from datetime import date, datetime, timedelta

from django.contrib.sessions.models import Session
from django.core.exceptions import ValidationError
from django.core.validators import validate_email
from django.db import transaction

from core.helpers import generate_uuid
from core.models import Log, Menu, Role, RolePermission, User, UserSession, VisitorLog
from core.security import hash_password, password_strength_error


class AdminError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


USER_SAFE_FIELDS = (
    "id",
    "uuid",
    "username",
    "email",
    "nom",
    "prenom",
    "telephone",
    "photo",
    "role_id",
    "actif",
    "is_active",
    "est_verifie",
    "derniere_connexion",
    "last_login_at",
    "last_login_ip",
    "email_verified_at",
    "created_at",
    "updated_at",
)


def _clean(value):
    return str(value or "").strip()


def _as_int(value, default=0):
    try:
        return int(value)
    except (TypeError, ValueError):
        return default


def _email_is_valid(email):
    try:
        validate_email(email)
        return True
    except ValidationError:
        return False


def _role_map():
    return {r["id"]: r["nom"] for r in Role.objects.values("id", "nom")}


def _user_dict(user):
    data = {field: getattr(user, field) for field in USER_SAFE_FIELDS}
    role = Role.objects.filter(id=user.role_id).values("nom").first()
    data["role_nom"] = role["nom"] if role else None
    return data


def list_users():
    roles = _role_map()
    rows = list(User.objects.order_by("-created_at").values(*USER_SAFE_FIELDS))
    for row in rows:
        row["role_nom"] = roles.get(row["role_id"])
    return rows


def get_user(user_uuid):
    user = User.objects.filter(uuid=user_uuid).first()
    if not user:
        raise AdminError("Utilisateur non trouvé", 404)
    return _user_dict(user)


def create_user(data):
    data = data or {}
    username = _clean(data.get("username"))
    email = _clean(data.get("email"))
    nom = _clean(data.get("nom"))
    prenom = _clean(data.get("prenom"))
    role_id = _as_int(data.get("role_id"))
    password = str(data.get("password") or "")

    if not username or not email or not nom or role_id <= 0:
        raise AdminError("Username, email, nom et rôle obligatoires")
    if not _email_is_valid(email):
        raise AdminError("Adresse email invalide")
    if (
        len(username) > 100
        or len(email) > 150
        or len(nom) > 100
        or len(prenom) > 100
    ):
        raise AdminError("Données trop longues")
    pwd_error = password_strength_error(password)
    if pwd_error:
        raise AdminError(pwd_error)
    if not Role.objects.filter(id=role_id).exists():
        raise AdminError("Rôle inconnu")
    if User.objects.filter(username=username).exists():
        raise AdminError("Ce nom d'utilisateur existe déjà")
    if User.objects.filter(email=email).exists():
        raise AdminError("Cet email est déjà utilisé")

    telephone = _clean(data.get("telephone")) or None
    actif = _as_int(data.get("actif", 1), 1)
    is_active = _as_int(data.get("is_active", 1), 1)
    user = User.objects.create(
        uuid=generate_uuid(),
        username=username,
        email=email,
        password_hash=hash_password(password),
        nom=nom,
        prenom=prenom,
        telephone=telephone,
        role_id=role_id,
        actif=actif,
        is_active=is_active,
    )
    return user


def update_user(user_uuid, data):
    data = data or {}
    existing = User.objects.filter(uuid=user_uuid).first()
    if not existing:
        raise AdminError("Utilisateur non trouvé", 404)

    update = {}
    if "username" in data:
        username = _clean(data.get("username"))
        if not username:
            raise AdminError("Le nom d'utilisateur est obligatoire")
        if User.objects.filter(username=username).exclude(id=existing.id).exists():
            raise AdminError("Ce nom d'utilisateur existe déjà")
        update["username"] = username
    if "email" in data:
        email = _clean(data.get("email"))
        if not email:
            raise AdminError("L'email est obligatoire")
        if not _email_is_valid(email):
            raise AdminError("Adresse email invalide")
        if User.objects.filter(email=email).exclude(id=existing.id).exists():
            raise AdminError("Cet email est déjà utilisé")
        update["email"] = email
    if "nom" in data:
        nom = _clean(data.get("nom"))
        if not nom:
            raise AdminError("Le nom est obligatoire")
        update["nom"] = nom
    if "prenom" in data:
        update["prenom"] = _clean(data.get("prenom"))
    if "telephone" in data:
        update["telephone"] = _clean(data.get("telephone")) or None
    if "role_id" in data:
        role_id = _as_int(data.get("role_id"))
        if role_id > 0:
            if not Role.objects.filter(id=role_id).exists():
                raise AdminError("Rôle inconnu")
            update["role_id"] = role_id
    if "actif" in data:
        update["actif"] = _as_int(data.get("actif"))
    if "is_active" in data:
        update["is_active"] = _as_int(data.get("is_active"))

    password = str(data.get("password") or "")
    if password:
        pwd_error = password_strength_error(password)
        if pwd_error:
            raise AdminError(pwd_error)
        update["password_hash"] = hash_password(password)

    if not update:
        raise AdminError("Aucune donnée à modifier")
    User.objects.filter(uuid=user_uuid).update(**update)
    update.pop("password_hash", None)
    return existing, list(update.keys())


def delete_user(user_uuid):
    existing = User.objects.filter(uuid=user_uuid).first()
    if not existing:
        raise AdminError("Utilisateur non trouvé", 404)
    role = Role.objects.filter(id=existing.role_id).first()
    role_code = (role.code if role else "").upper()
    if int(existing.role_id) == 1 or role_code in {"ADMIN", "SUPER_ADMIN"}:
        raise AdminError("Impossible de supprimer un administrateur")
    user_id = existing.id
    existing.delete()
    existing.id = user_id
    return existing


def list_roles():
    return list(Role.objects.order_by("nom").values())


def get_role(role_uuid):
    role = Role.objects.filter(uuid=role_uuid).values().first()
    if not role:
        raise AdminError("Rôle non trouvé", 404)
    return role


def create_role(data):
    data = data or {}
    code = _clean(data.get("code")).upper()
    nom = _clean(data.get("nom"))
    if not code or not nom:
        raise AdminError("Code et nom obligatoires")
    if len(code) > 50 or len(nom) > 100:
        raise AdminError("Code ou nom trop long")
    if Role.objects.filter(code=code).exists():
        raise AdminError("Ce code existe déjà")
    return Role.objects.create(
        uuid=generate_uuid(),
        code=code,
        nom=nom,
        description=_clean(data.get("description")) or None,
    )


def update_role(role_uuid, data):
    data = data or {}
    existing = Role.objects.filter(uuid=role_uuid).first()
    if not existing:
        raise AdminError("Rôle non trouvé", 404)
    is_admin_role = int(existing.id) == 1 or existing.code.upper() == "ADMIN"

    update = {}
    if "code" in data:
        code = _clean(data.get("code")).upper()
        if not code:
            raise AdminError("Le code est obligatoire")
        if is_admin_role and code != existing.code.upper():
            raise AdminError("Le code du rôle administrateur ne peut pas être modifié")
        if Role.objects.filter(code=code).exclude(id=existing.id).exists():
            raise AdminError("Ce code existe déjà")
        update["code"] = code
    if "nom" in data:
        nom = _clean(data.get("nom"))
        if not nom:
            raise AdminError("Le nom est obligatoire")
        update["nom"] = nom
    if "description" in data:
        update["description"] = _clean(data.get("description")) or None

    if not update:
        raise AdminError("Aucune donnée à modifier")
    Role.objects.filter(uuid=role_uuid).update(**update)
    return existing, list(update.keys())


def delete_role(role_uuid):
    existing = Role.objects.filter(uuid=role_uuid).first()
    if not existing:
        raise AdminError("Rôle non trouvé", 404)
    if int(existing.id) == 1 or existing.code.upper() == "ADMIN":
        raise AdminError("Impossible de supprimer le rôle administrateur")
    if User.objects.filter(role_id=existing.id).exists():
        raise AdminError("Impossible : des utilisateurs sont rattachés à ce rôle")
    role_id = existing.id
    with transaction.atomic():
        RolePermission.objects.filter(role_id=role_id).delete()
        existing.delete()
    existing.id = role_id
    return existing


def list_menus():
    return list(Menu.objects.order_by("ordre").values())


def get_menu(menu_uuid):
    menu = Menu.objects.filter(uuid=menu_uuid).values().first()
    if not menu:
        raise AdminError("Menu non trouvé", 404)
    return menu


def create_menu(data):
    data = data or {}
    code = _clean(data.get("code"))
    libelle = _clean(data.get("libelle"))
    if not code or not libelle:
        raise AdminError("Code et libellé obligatoires")
    if Menu.objects.filter(code=code).exists():
        raise AdminError("Ce code existe déjà")
    parent_id = data.get("parent_id")
    parent_id = int(parent_id) if str(parent_id or "").isdigit() else None
    ordre = data.get("ordre")
    ordre = int(ordre) if str(ordre or "").lstrip("-").isdigit() else 0
    return Menu.objects.create(
        uuid=generate_uuid(),
        code=code,
        libelle=libelle,
        icon=_clean(data.get("icon")) or None,
        route=_clean(data.get("route")) or None,
        parent_id=parent_id,
        ordre=ordre,
    )


def update_menu(menu_uuid, data):
    data = data or {}
    existing = Menu.objects.filter(uuid=menu_uuid).first()
    if not existing:
        raise AdminError("Menu non trouvé", 404)

    update = {}
    if "code" in data:
        code = _clean(data.get("code"))
        if not code:
            raise AdminError("Le code est obligatoire")
        if Menu.objects.filter(code=code).exclude(id_menu=existing.id_menu).exists():
            raise AdminError("Ce code existe déjà")
        update["code"] = code
    if "libelle" in data:
        libelle = _clean(data.get("libelle"))
        if not libelle:
            raise AdminError("Le libellé est obligatoire")
        update["libelle"] = libelle
    if "icon" in data:
        update["icon"] = _clean(data.get("icon")) or None
    if "route" in data:
        update["route"] = _clean(data.get("route")) or None
    if "parent_id" in data:
        parent_id = data.get("parent_id")
        update["parent_id"] = int(parent_id) if str(parent_id or "").isdigit() else None
    if "ordre" in data:
        ordre = data.get("ordre")
        update["ordre"] = int(ordre) if str(ordre or "").lstrip("-").isdigit() else 0

    if not update:
        raise AdminError("Aucune donnée à modifier")
    Menu.objects.filter(uuid=menu_uuid).update(**update)
    return existing, list(update.keys())


def delete_menu(menu_uuid):
    existing = Menu.objects.filter(uuid=menu_uuid).first()
    if not existing:
        raise AdminError("Menu non trouvé", 404)
    if Menu.objects.filter(parent_id=existing.id_menu).exists():
        raise AdminError("Supprimez d'abord les sous-menus")
    menu_id = existing.id_menu
    existing.delete()
    existing.id_menu = menu_id
    return existing


def list_sessions():
    users = {
        u["id"]: u
        for u in User.objects.values("id", "username", "nom", "prenom")
    }
    rows = list(UserSession.objects.order_by("-login_time")[:500].values())
    for row in rows:
        user = users.get(row["user_id"], {})
        row["username"] = user.get("username")
        row["nom"] = user.get("nom")
        row["prenom"] = user.get("prenom")
    return rows


def terminate_session(session_id):
    existing = UserSession.objects.filter(id=session_id).first()
    if not existing:
        raise AdminError("Session non trouvée", 404)
    now = datetime.now()
    UserSession.objects.filter(id=session_id).update(
        is_active=0,
        logout_time=now,
        logout_reason="force",
    )
    sid = (existing.session_id or "").strip()
    if sid:
        try:
            Session.objects.filter(session_key=sid).delete()
        except Exception:
            pass
    return existing


def _logs_queryset(filters):
    filters = filters or {}
    qs = Log.objects.all()
    if filters.get("user_id"):
        qs = qs.filter(user_id=int(filters["user_id"]))
    if filters.get("action"):
        qs = qs.filter(action__icontains=filters["action"])
    if filters.get("niveau"):
        qs = qs.filter(niveau=filters["niveau"])
    if filters.get("date_from"):
        qs = qs.filter(created_at__gte=filters["date_from"])
    if filters.get("date_to"):
        qs = qs.filter(created_at__lte=f"{filters['date_to']} 23:59:59")
    return qs


def list_logs(limit=100, offset=0, filters=None):
    limit = max(1, min(int(limit or 100), 500))
    offset = max(0, int(offset or 0))
    qs = _logs_queryset(filters)
    total = qs.count()
    rows = list(qs.order_by("-id")[offset : offset + limit].values())
    user_ids = {row["user_id"] for row in rows if row["user_id"]}
    users = {
        u["id"]: u
        for u in User.objects.filter(id__in=user_ids).values(
            "id", "username", "nom", "prenom"
        )
    }
    for row in rows:
        user = users.get(row["user_id"], {})
        row["username"] = user.get("username")
        row["nom"] = user.get("nom")
        row["prenom"] = user.get("prenom")
    return {"total": total, "data": rows}


def get_log(log_id):
    row = Log.objects.filter(id=log_id).values().first()
    if not row:
        raise AdminError("Journal non trouvé", 404)
    return row


def clear_logs(actor_email=None):
    Log.objects.all().delete()
    Log.objects.create(
        action="logs_cleared",
        description="Effacement intégral des journaux d'activité"
        + (f" par {actor_email}" if actor_email else ""),
        niveau="warning",
        created_at=datetime.now(),
    )


def list_visitors():
    return list(
        VisitorLog.objects.order_by("-visit_date", "-visit_time")[:500].values()
    )


def visitor_stats():
    today = date.today()
    week_start = today - timedelta(days=7)
    month_start = today - timedelta(days=30)
    return {
        "today": VisitorLog.objects.filter(visit_date=today).count(),
        "week": VisitorLog.objects.filter(visit_date__gte=week_start).count(),
        "month": VisitorLog.objects.filter(visit_date__gte=month_start).count(),
    }
