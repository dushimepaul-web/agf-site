import mimetypes
from pathlib import Path

from django.conf import settings
from django.contrib import messages
from django.http import FileResponse, Http404, HttpResponseForbidden
from django.shortcuts import redirect, render
from django.utils.safestring import mark_safe
from django.views.decorators.csrf import csrf_exempt

from .auth import (
    authenticate,
    close_user_sessions,
    create_user_session,
    fetch_role,
    fetch_user_by_uuid,
    log_history,
    log_login_attempt,
    start_session,
    touch_last_login,
    update_password,
)
from .csrf import valid_csrf
from .decorators import login_required
from .helpers import client_ip, generate_uuid, json_error, json_success, read_json
from .models import Role, User
from .permissions import is_staff
from .ratelimit import clear, is_limited, register_failure
from .security import hash_password, password_strength_error, verify_password


def _flash(request, level, html):
    messages.add_message(
        request,
        level,
        mark_safe(html),
        extra_tags="alert alert-danger text-center"
        if level == messages.ERROR
        else "alert alert-warning text-center"
        if level == messages.WARNING
        else "alert alert-success text-center",
    )


def root(request):
    if request.session.get("logged_in"):
        return redirect("/Dashboard")
    return redirect("/Admin")


def login_view(request):
    return render(request, "Login_View.html")


def do_login(request):
    if (request.method or "GET").upper() != "POST":
        return redirect("/Admin")

    ip = client_ip(request)
    if is_limited(ip):
        _flash(
            request,
            messages.ERROR,
            "<strong>Trop de tentatives!</strong> Veuillez réessayer dans 15 minutes.",
        )
        return redirect("/Admin")

    email = (request.POST.get("email") or "").strip()
    password = request.POST.get("password") or ""
    user = authenticate(email, password)
    if not user:
        register_failure(ip)
        log_login_attempt(request, email, False, None)
        _flash(
            request,
            messages.ERROR,
            "<strong>Oups!</strong> Email ou mot de passe incorrect.",
        )
        return redirect("/Admin")

    clear(ip)
    role = fetch_role(user.role_id)
    start_session(request, user, role)
    touch_last_login(user)
    log_login_attempt(request, email, True, user.id)
    create_user_session(request, user.id, request.session.session_key)
    return redirect("/Dashboard")


def register_view(request):
    if not settings.ALLOW_PUBLIC_REGISTRATION:
        _flash(
            request,
            messages.WARNING,
            "L'inscription publique est désactivée. Contactez l'administrateur pour créer un compte.",
        )
        return redirect("/Admin")
    return render(request, "Register_View.html")


def do_register(request):
    if not settings.ALLOW_PUBLIC_REGISTRATION:
        return redirect("/Admin")
    if (request.method or "GET").upper() != "POST":
        return redirect("/Admin/register")

    ip = client_ip(request)
    if is_limited(ip):
        _flash(
            request,
            messages.ERROR,
            "<strong>Trop de tentatives!</strong> Veuillez réessayer dans 15 minutes.",
        )
        return redirect("/Admin/register")

    nom_complet = (request.POST.get("nom_complet") or "").strip()
    email = (request.POST.get("email") or "").strip()
    password = request.POST.get("password") or ""
    confirm = request.POST.get("confirm_password") or ""

    def fail(message):
        _flash(request, messages.ERROR, f"<strong>Erreur!</strong> {message}")
        return redirect("/Admin/register")

    if not nom_complet or not email or not password or not confirm:
        return fail("Tous les champs sont requis.")
    if password != confirm:
        return fail("Les mots de passe ne correspondent pas.")
    pwd_error = password_strength_error(password)
    if pwd_error:
        return fail(pwd_error)
    if "@" not in email or "." not in email.split("@")[-1]:
        return fail("Adresse email invalide.")
    if len(nom_complet) > 150 or len(email) > 150 or len(password) > 72:
        return fail("Données trop longues.")
    if User.objects.filter(email=email).exists():
        return fail("Cet email est déjà utilisé.")

    role = Role.objects.filter(code="AGENT_SAISIE").first()
    if role is None:
        role = Role.objects.create(
            uuid=generate_uuid(),
            code="AGENT_SAISIE",
            nom="Agent de saisie",
            description="Saisie des données de surveillance (rôle par défaut à l'inscription)",
        )
    parts = nom_complet.split(" ", 1)
    nom = parts[0]
    prenom = parts[1] if len(parts) > 1 else ""
    username = email.lower().replace(" ", "").replace("@", "").replace(".", "")

    try:
        User.objects.create(
            uuid=generate_uuid(),
            username=username,
            email=email,
            password_hash=hash_password(password),
            nom=nom,
            prenom=prenom,
            role_id=role.id,
            actif=1,
            is_active=1,
        )
    except Exception:
        return fail("Une erreur est survenue lors de la création du compte.")

    _flash(
        request,
        messages.SUCCESS,
        "<strong>Succès!</strong> Votre compte a été créé. Connectez-vous.",
    )
    return redirect("/Admin")


def logout_view(request):
    if (request.method or "GET").upper() != "POST":
        return redirect("/Dashboard")
    user_id = request.session.get("id_utilisateur")
    if user_id:
        log_history(
            request,
            user_id,
            "logout",
            f"Déconnexion de {request.session.get('email', '')}",
        )
        close_user_sessions(user_id, "manual")
    request.session.flush()
    return redirect("/Admin")


@login_required
def profile_view(request):
    user = fetch_user_by_uuid(request.session.get("uuid"))
    if not user:
        raise Http404("Utilisateur introuvable")
    return render(request, "Profile_View.html", {"title": "Mon Profil", "user": user})


@csrf_exempt
@login_required
def api_profile_update(request):
    if (request.method or "GET").upper() != "POST":
        return json_error("Méthode non autorisée (POST requis)", 405)
    data = read_json(request)
    if not valid_csrf(request, data.get("csrf_token")):
        return json_error("Jeton CSRF manquant ou invalide", 403)
    user_uuid = request.session.get("uuid")
    if not any(data.get(key) for key in ("nom", "prenom", "email")):
        return json_error("Aucune donnée à modifier")

    update = {}
    if "nom" in data:
        nom = (data.get("nom") or "").strip()
        if not nom:
            return json_error("Le nom est obligatoire")
        update["nom"] = nom
    if "prenom" in data:
        update["prenom"] = (data.get("prenom") or "").strip()
    if "email" in data:
        email = (data.get("email") or "").strip()
        if not email:
            return json_error("L'email est obligatoire")
        if User.objects.filter(email=email).exclude(uuid=user_uuid).exists():
            return json_error("Cet email est déjà utilisé")
        update["email"] = email

    if update:
        User.objects.filter(uuid=user_uuid).update(**update)
    user = fetch_user_by_uuid(user_uuid)
    if user:
        request.session["nom_complet"] = user.full_name
        request.session["email"] = user.email
    return json_success(None, "Profil mis à jour")


@csrf_exempt
@login_required
def api_profile_change_password(request):
    if (request.method or "GET").upper() != "POST":
        return json_error("Méthode non autorisée (POST requis)", 405)
    data = read_json(request)
    if not valid_csrf(request, data.get("csrf_token")):
        return json_error("Jeton CSRF manquant ou invalide", 403)
    current = data.get("current_password") or ""
    new_password = data.get("new_password") or ""
    confirm = data.get("confirm_password") or ""
    if not current or not new_password or not confirm:
        return json_error("Tous les champs sont requis")
    if new_password != confirm:
        return json_error("Les mots de passe ne correspondent pas")
    pwd_error = password_strength_error(new_password)
    if pwd_error:
        return json_error(pwd_error)
    user = fetch_user_by_uuid(request.session.get("uuid"))
    if not user or not verify_password(current, user.password_hash):
        return json_error("Mot de passe actuel incorrect")
    update_password(user, new_password)
    return json_success(None, "Mot de passe changé avec succès")


SENSITIVE_ATTACHMENT_DIRS = ("Consultations", "Payments")


def serve_attachment(request, path):
    path = (path or "").replace("\\", "/").lstrip("/")
    if not path:
        raise Http404("Fichier introuvable")
    root = (settings.PROJECT_ROOT / "attachments").resolve()
    full = (root / path).resolve()
    if root != full and root not in full.parents:
        raise Http404("Fichier introuvable")
    if not full.is_file():
        raise Http404("Fichier introuvable")
    if path.split("/", 1)[0] in SENSITIVE_ATTACHMENT_DIRS:
        if not is_staff(request.session.get("role_code"), request.session.get("id_role")):
            return HttpResponseForbidden("Accès refusé")
    content_type = mimetypes.guess_type(full.name)[0] or "application/octet-stream"
    return FileResponse(open(full, "rb"), content_type=content_type)
