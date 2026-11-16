from django.urls import path

from . import views

urlpatterns = [
    path("Investors", views.investors, name="finance_investors"),
    path("Investors/add_edit", views.investors, name="finance_investors_add"),
    path("Investors/add_edit/<int:edit_id>", views.investors, name="finance_investors_edit"),
    path("Brokers", views.brokers, name="finance_brokers"),
    path("Brokers/add_edit", views.brokers, name="finance_brokers_add"),
    path("Brokers/add_edit/<int:edit_id>", views.brokers, name="finance_brokers_edit"),
]
