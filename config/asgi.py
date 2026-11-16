import os

from django.conf import settings as django_settings

os.environ.setdefault("DJANGO_SETTINGS_MODULE", "config.settings")

import django

django.setup()

from django.core.asgi import get_asgi_application
from starlette.applications import Starlette
from starlette.routing import Mount
from starlette.staticfiles import StaticFiles

from api.main import app as api_app

django_app = get_asgi_application()

application = Starlette(
    routes=[
        Mount("/api", app=api_app),
        Mount(
            "/assets",
            app=StaticFiles(directory=str(django_settings.PROJECT_ROOT / "assets")),
            name="assets",
        ),
        Mount("/", app=django_app),
    ]
)
