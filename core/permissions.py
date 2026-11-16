from .models import RolePermission


ADMIN_ROLE_CODES = {"ADMIN", "SUPER_ADMIN"}


def is_admin(role_code=None, role_id=None):
    return str(role_code or "").upper() in ADMIN_ROLE_CODES or int(role_id or 0) == 1


def is_medecin(role_code=None):
    return str(role_code or "").upper() == "MEDECIN"


def is_staff(role_code=None, role_id=None):
    return is_admin(role_code, role_id) or is_medecin(role_code)


def has_perm(role_id, slug, perm="can_view"):
    if int(role_id or 0) == 1:
        return True
    if not role_id or not slug:
        return False
    return RolePermission.objects.filter(
        role_id=role_id,
        module_code=slug,
        permission=perm,
        allowed=1,
    ).exists()


def can_view(role_id, slug):
    return has_perm(role_id, slug, "can_view")


def can_create(role_id, slug):
    return has_perm(role_id, slug, "can_add")


def can_edit(role_id, slug):
    return has_perm(role_id, slug, "can_edit")


def can_delete(role_id, slug):
    return has_perm(role_id, slug, "can_delete")


def can_export(role_id, slug):
    return has_perm(role_id, slug, "can_export")


def can_imprimer(role_id, slug):
    return has_perm(role_id, slug, "can_imprimer")
