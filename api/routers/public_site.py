from fastapi import APIRouter, Body, Depends, Request

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.Public import services


router = APIRouter(prefix="/admin", tags=["public"])


@router.get("/contact-messages")
def contacts_list(limit: int = 50, offset: int = 0, session=Depends(get_admin_session)):
    return ok(call(services.contacts, limit, offset))


@router.get("/contact-messages/{contact_id}")
def contacts_get(contact_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_contact, contact_id))


@router.post("/contact-messages/{contact_id}/read")
def contacts_read(
    contact_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    call(services.mark_contact_read, contact_id)
    audit(request, session, "contact_message_read", f"Message contact #{contact_id} marqué lu")
    return ok(None, "Marqué comme lu")


@router.post("/contact-messages/{contact_id}/delete")
def contacts_delete(
    contact_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_contact, contact_id)
    audit(request, session, "contact_message_deleted", f"Suppression message contact #{contact_id} ({row.email})")
    return ok(None, "Message supprimé")


@router.get("/faqs")
def faqs_list(session=Depends(get_admin_session)):
    return ok(call(services.faqs))


@router.get("/faqs/{faq_id}")
def faqs_get(faq_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_faq, faq_id))


@router.post("/faqs")
def faqs_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_faq, payload)
    audit(request, session, "faq_created", f"Création FAQ {row.question[:80]}")
    return ok({"id": row.id_faq}, "FAQ créée")


@router.post("/faqs/{faq_id}/update")
def faqs_update(
    faq_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_faq, faq_id, payload)
    audit(request, session, "faq_updated", f"FAQ #{faq_id} : {', '.join(keys)}")
    return ok(None, "FAQ mise à jour")


@router.post("/faqs/{faq_id}/delete")
def faqs_delete(
    faq_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    call(services.delete_faq, faq_id)
    audit(request, session, "faq_deleted", f"Suppression FAQ #{faq_id}")
    return ok(None, "FAQ supprimée")


@router.get("/partners")
def partners_list(session=Depends(get_admin_session)):
    return ok(call(services.partners))


@router.get("/partners/{partner_id}")
def partners_get(partner_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_partner, partner_id))


@router.post("/partners")
def partners_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_partner, payload)
    audit(request, session, "partner_created", f"Création partenaire {row.nom}")
    return ok({"id": row.id_partenaire}, "Partenaire créé")


@router.post("/partners/{partner_id}/update")
def partners_update(
    partner_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_partner, partner_id, payload)
    audit(request, session, "partner_updated", f"Partenaire {row.nom} : {', '.join(keys)}")
    return ok(None, "Partenaire mis à jour")


@router.post("/partners/{partner_id}/delete")
def partners_delete(
    partner_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_partner, partner_id)
    audit(request, session, "partner_deleted", f"Suppression partenaire {row.nom}")
    return ok(None, "Partenaire supprimé")


@router.get("/social-links")
def social_links_list(session=Depends(get_admin_session)):
    return ok(call(services.social_links))


@router.get("/social-links/{link_id}")
def social_links_get(link_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_social_link, link_id))


@router.post("/social-links")
def social_links_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_social_link, payload)
    audit(request, session, "social_link_created", f"Création réseau social {row.platform}")
    return ok({"id": row.id}, "Lien créé")


@router.post("/social-links/{link_id}/update")
def social_links_update(
    link_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_social_link, link_id, payload)
    audit(request, session, "social_link_updated", f"Réseau social {row.platform} : {', '.join(keys)}")
    return ok(None, "Lien mis à jour")


@router.post("/social-links/{link_id}/delete")
def social_links_delete(
    link_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_social_link, link_id)
    audit(request, session, "social_link_deleted", f"Suppression réseau social {row.platform}")
    return ok(None, "Lien supprimé")
