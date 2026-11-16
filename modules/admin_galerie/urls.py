from django.urls import path

from . import views

urlpatterns = [
    path("Mediatheque", views.index, name="mediatheque"),
    path("Mediatheque/<str:media_type>", views.index, name="mediatheque_type"),
    path("Media", views.index, name="media"),
    path("Media/index/<str:media_type>", views.index, name="media_type"),
    path("GalerieMedias", views.index, name="galerie_medias"),
    path("GalerieMedias/index", views.index, name="galerie_medias_index"),
    path("GalerieMedias/index/<str:media_type>", views.index, name="galerie_medias_type"),
]
