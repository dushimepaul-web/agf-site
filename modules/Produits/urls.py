from django.urls import path

from . import views

urlpatterns = [
    path("Categories", views.categories, name="produits_categories"),
    path("Categories/add_edit", views.categories, name="produits_categories_add"),
    path("Categories/add_edit/<int:edit_id>", views.categories, name="produits_categories_edit"),
    path("Produits", views.products, name="produits_list"),
    path("Produits/add_edit", views.products, name="produits_add"),
    path("Produits/add_edit/<int:edit_id>", views.products, name="produits_edit"),
    path("Unites", views.units, name="produits_unites"),
    path("Unites/add_edit", views.units, name="produits_unites_add"),
    path("Unites/add_edit/<int:edit_id>", views.units, name="produits_unites_edit"),
]
