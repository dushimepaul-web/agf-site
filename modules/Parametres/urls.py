from django.urls import path

from . import views

urlpatterns = [
    path("Parametres", views.index, name="parametres"),
]
