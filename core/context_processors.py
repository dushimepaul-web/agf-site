from .auth import session_payload
from .helpers import get_setting, media_url
from .models import Menu
from .permissions import is_admin


def branding(request):
    try:
        login_img = (get_setting("login_img", "") or "").strip()
        return {
            "nom_app": get_setting("nom_app", "Surveillance des maladies"),
            "logo_url": media_url(get_setting("logo_app", "assets/images/logo.png")),
            "login_img": media_url(login_img) if login_img else "",
            "favicon_url": media_url(
                get_setting("favicon_ico", "assets/images/favicon.png")
            ),
        }
    except Exception:
        return {
            "nom_app": "Surveillance des maladies",
            "logo_url": "/assets/images/logo.png",
            "login_img": "",
            "favicon_url": "/assets/images/favicon.png",
        }


def current_user(request):
    return {"current_user": session_payload(request)}


def _segment(route):
    route = (route or "").strip("/")
    return route.split("/")[0].lower() if route else ""


def sidebar(request):
    path = (request.path or "/").strip("/")
    current_segment = path.split("/")[0] if path else "Dashboard"
    role_id = request.session.get("id_role")
    role_code = request.session.get("role_code")
    admin_user = is_admin(role_code, role_id)

    parents = []
    try:
        parent_query = Menu.objects.filter(parent_id__isnull=True).order_by("ordre")
        parent_rows = list(parent_query)
        if not admin_user:
            parent_rows = [
                m for m in parent_rows
                if (m.code or "").upper() not in {"ADMINISTRATION", "PARAMETRES"}
            ]
        parent_ids = [m.id_menu for m in parent_rows]
        children_rows = list(
            Menu.objects.filter(parent_id__in=parent_ids).order_by("parent_id", "ordre")
        ) if parent_ids else []

        children_by_parent = {}
        for child in children_rows:
            children_by_parent.setdefault(child.parent_id, []).append(child)

        active_parent_id = None
        for child in children_rows:
            if _segment(child.route) == current_segment.lower():
                active_parent_id = child.parent_id
                break
        if active_parent_id is None:
            for parent in parent_rows:
                if not children_by_parent.get(parent.id_menu) and _segment(parent.route) == current_segment.lower():
                    active_parent_id = parent.id_menu
                    break

        for parent in parent_rows:
            children = children_by_parent.get(parent.id_menu, [])
            parents.append(
                {
                    "id": parent.id_menu,
                    "code": parent.code or "",
                    "libelle": parent.libelle or "",
                    "icon": parent.icon or "bi-folder",
                    "route": (parent.route or "").strip("/"),
                    "href": "/" + (parent.route or "").strip("/"),
                    "active": active_parent_id == parent.id_menu,
                    "direct_active": not children and _segment(parent.route) == current_segment.lower(),
                    "children": [
                        {
                            "id": child.id_menu,
                            "libelle": child.libelle or "",
                            "route": (child.route or "").strip("/"),
                            "href": "/" + (child.route or "").strip("/"),
                            "active": _segment(child.route) == current_segment.lower(),
                        }
                        for child in children
                    ],
                }
            )
    except Exception:
        parents = []

    return {
        "sidebar_menus": parents,
        "sidebar_empty": not parents,
        "current_segment": current_segment,
        "is_admin_user": admin_user,
    }
