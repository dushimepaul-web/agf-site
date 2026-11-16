from fastapi import APIRouter, Body, Depends, File, Request, UploadFile

from api.admin_utils import audit, call, ok
from api.csrf import require_csrf
from api.deps import get_admin_session
from modules.Produits import services


router = APIRouter(prefix="/admin", tags=["produits"])


@router.get("/product-categories")
def categories_list(session=Depends(get_admin_session)):
    return ok(call(services.list_categories))


@router.get("/product-categories/{category_id}")
def categories_get(category_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_category, category_id))


@router.post("/product-categories")
def categories_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_category, payload)
    audit(request, session, "product_category_created", f"Création catégorie {row.nom}")
    return ok({"id": row.id}, "Catégorie créée")


@router.post("/product-categories/{category_id}/update")
def categories_update(
    category_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_category, category_id, payload)
    audit(request, session, "product_category_updated", f"Catégorie {row.nom} : {', '.join(keys)}")
    return ok(None, "Catégorie mise à jour")


@router.post("/product-categories/{category_id}/delete")
def categories_delete(
    category_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_category, category_id)
    audit(request, session, "product_category_deleted", f"Suppression catégorie {row.nom}")
    return ok(None, "Catégorie supprimée")


@router.get("/products")
def products_list(categorie_id: int | None = None, session=Depends(get_admin_session)):
    return ok(call(services.list_products, categorie_id))


@router.post("/products/upload")
def products_upload(
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path, filename = call(services.save_image, file, "Produits")
    audit(request, session, "product_image_uploaded", f"Upload image produit {filename}")
    return ok({"path": path, "filename": filename}, "Fichier uploadé")


@router.get("/products/{product_id}")
def products_get(product_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_product, product_id))


@router.post("/products")
def products_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_product, payload)
    audit(request, session, "product_created", f"Création produit {row.nom}")
    return ok({"id": row.id}, "Produit créé")


@router.post("/products/{product_id}/update")
def products_update(
    product_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_product, product_id, payload)
    audit(request, session, "product_updated", f"Produit {row.nom} : {', '.join(keys)}")
    return ok(None, "Produit mis à jour")


@router.post("/products/{product_id}/delete")
def products_delete(
    product_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_product, product_id)
    audit(request, session, "product_deleted", f"Suppression produit {row.nom}")
    return ok(None, "Produit supprimé")


@router.get("/business-units")
def units_list(session=Depends(get_admin_session)):
    return ok(call(services.list_units))


@router.post("/business-units/upload")
def units_upload(
    request: Request,
    file: UploadFile = File(...),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    path, filename = call(services.save_image, file, "Unites")
    audit(request, session, "business_unit_logo_uploaded", f"Upload logo unité {filename}")
    return ok({"path": path, "filename": filename}, "Fichier uploadé")


@router.get("/business-units/{unit_id}")
def units_get(unit_id: int, session=Depends(get_admin_session)):
    return ok(call(services.get_unit, unit_id))


@router.post("/business-units")
def units_create(
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.create_unit, payload)
    audit(request, session, "business_unit_created", f"Création unité {row.nom}")
    return ok({"id": row.id}, "Unité créée")


@router.post("/business-units/{unit_id}/update")
def units_update(
    unit_id: int,
    request: Request,
    payload: dict = Body(default={}),
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row, keys = call(services.update_unit, unit_id, payload)
    audit(request, session, "business_unit_updated", f"Unité {row.nom} : {', '.join(keys)}")
    return ok(None, "Unité mise à jour")


@router.post("/business-units/{unit_id}/delete")
def units_delete(
    unit_id: int,
    request: Request,
    session=Depends(get_admin_session),
    _csrf=Depends(require_csrf),
):
    row = call(services.delete_unit, unit_id)
    audit(request, session, "business_unit_deleted", f"Suppression unité {row.nom}")
    return ok(None, "Unité supprimée")
