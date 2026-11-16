import os

import django

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")
if not django.apps.apps.ready:
    django.setup()

from django.conf import settings
from fastapi import FastAPI, Request
from fastapi.exceptions import RequestValidationError
from fastapi.responses import JSONResponse
from starlette.exceptions import HTTPException as StarletteHTTPException

from api.routers import admin, consultation, dashboard, finance, home, media, parametres, produits, profile, public_site, temoignages

app = FastAPI(
    title="AGF API",
    version="1.0.0",
    docs_url="/docs" if settings.DEBUG else None,
    redoc_url="/redoc" if settings.DEBUG else None,
    openapi_url="/openapi.json" if settings.DEBUG else None,
)

app.include_router(admin.router)
app.include_router(produits.router)
app.include_router(parametres.router)
app.include_router(public_site.router)
app.include_router(temoignages.router)
app.include_router(finance.router)
app.include_router(consultation.router)
app.include_router(media.router)
app.include_router(dashboard.router)
app.include_router(profile.router)
app.include_router(home.router)


@app.get("/health")
def health():
    return {"success": True, "message": "ok", "data": {"status": "healthy"}}


@app.exception_handler(StarletteHTTPException)
async def http_exception_handler(request: Request, exc: StarletteHTTPException):
    return JSONResponse(
        {"success": False, "message": exc.detail},
        status_code=exc.status_code,
    )


@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    return JSONResponse(
        {"success": False, "message": "Requête invalide", "data": exc.errors()},
        status_code=422,
    )
