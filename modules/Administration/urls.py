from django.urls import path

from . import views

urlpatterns = [
    path("Users", views.users, name="admin_users"),
    path("Roles", views.roles, name="admin_roles"),
    path("Menus", views.menus, name="admin_menus"),
    path("Sessions", views.sessions, name="admin_sessions"),
    path("Logs", views.logs, name="admin_logs"),
    path("Visitors", views.visitors, name="admin_visitors"),
    path("Permissions", views.permissions, name="admin_permissions"),
]
