from django.urls import path

from . import views

urlpatterns = [
    path("Dashboard", views.index, name="dashboard"),
    path("Dashboard/api_data", views.api_data, name="dashboard_api_data"),
    path("Dashboard/api_filters", views.api_filters, name="dashboard_api_filters"),
    path("api/dashboard/data", views.api_data, name="api_dashboard_data"),
    path("api/dashboard/filters", views.api_filters, name="api_dashboard_filters"),
]
