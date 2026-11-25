from django.conf import settings
from django.urls import include, path
from django.views.static import serve

from core import views as core_views
from modules.Consultation import views as consultation_views
from modules.Home import views as home_views

urlpatterns = [
    path("", home_views.home, name="root"),
    path("index", home_views.home),
    path("doctor", consultation_views.public_index, name="front_doctor"),
    path("consultation", consultation_views.public_index, name="front_consultation"),
    path("patient-form", consultation_views.public_patient_form, name="front_patient_form"),
    path("patient-form/create", consultation_views.patient_form_create, name="front_patient_form_create"),
    path("patient-form/api_submit", consultation_views.patient_form_submit, name="front_patient_form_submit"),
    path("patient-form/changeDoctor", consultation_views.patient_form_change_doctor, name="front_patient_form_change_doctor"),
    path("patient-form/get_countries", consultation_views.patient_form_countries, name="front_patient_form_countries"),
    path("Admin", core_views.login_view, name="admin_login"),
    path("Admin/Login", core_views.login_view, name="admin_login_alias"),
    path("ADMIN", core_views.login_view, name="admin_login_upper"),
    path("admin", core_views.login_view, name="admin_login_lower"),
    path("Login", core_views.login_view, name="login_alias_upper"),
    path("login", core_views.login_view, name="login_alias"),
    path("Admin/do_login", core_views.do_login, name="admin_do_login"),
    path("Admin/register", core_views.register_view, name="admin_register"),
    path("Admin/do_register", core_views.do_register, name="admin_do_register"),
    path("Logout", core_views.logout_view, name="logout"),
    path("Admin/Logout", core_views.logout_view, name="admin_logout"),
    path("Profile", core_views.profile_view, name="profile"),
    path("api/profile/update", core_views.api_profile_update, name="api_profile_update"),
    path(
        "api/profile/change_password",
        core_views.api_profile_change_password,
        name="api_profile_change_password",
    ),
    path("", include("modules.Home.urls")),
    path("", include("modules.Administration.urls")),
    path("", include("modules.Dashboard.urls")),
    path("", include("modules.Produits.urls")),
    path("", include("modules.Parametres.urls")),
    path("", include("modules.Public.urls")),
    path("", include("modules.Temoignages.urls")),
    path("", include("modules.Finance.urls")),
    path("", include("modules.Consultation.urls")),
    path("", include("modules.admin_galerie.urls")),
]

urlpatterns += [
    path("attachments/<path:path>", core_views.serve_attachment, name="serve_attachment"),
]

if settings.DEBUG:
    urlpatterns += [
        path(
            "uploads/<path:path>",
            serve,
            {"document_root": str(settings.PROJECT_ROOT / "uploads")},
        ),
    ]
