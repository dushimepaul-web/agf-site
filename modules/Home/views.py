import json

from django.http import Http404, JsonResponse
from django.shortcuts import redirect, render
from django.template.loader import render_to_string
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_POST

from core.helpers import brand_sigle

from . import services


def _json_body(request):
    if request.content_type and "application/json" in request.content_type:
        try:
            return json.loads(request.body or "{}")
        except json.JSONDecodeError:
            return {}
    return request.POST


def home(request):
    return render(request, "Home/Home_View.html", services.home_context())


def about(request):
    services.log_visit(request)
    return render(request, "Home/about_us.html", services.static_page("Company Profile"))


def investors_form(request):
    return redirect("/investor")


def about_detail(request, slug):
    try:
        ctx = services.unit_detail(slug)
    except services.HomeError:
        raise Http404("Unité introuvable")
    services.log_visit(request)
    return render(request, "Home/Unit_Detail_View.html", ctx)


def aninova_industries(request):
    services.log_visit(request)
    return render(request, "Home/aninova_industries.html", services.static_page("ANINOVA INDUSTRIES"))


def ceriqa_labo(request):
    services.log_visit(request)
    return render(request, "Home/ceriqa_labo.html", services.static_page("CERIQA LABORATORIES"))


def our_products(request):
    services.log_visit(request)
    return render(request, "Home/our_Products.html", services.products_context())


def agriculture_bioresources(request):
    services.log_visit(request)
    return render(request, "Home/agriculture_bioresources.html", services.static_page("Agriculture & Bioresources"))


def legal(request):
    services.log_visit(request)
    return render(request, "Home/legal.html", services.static_page("Legal Notice"))


def privacy(request):
    services.log_visit(request)
    return render(request, "Home/privacy.html", services.static_page("Privacy Policy"))


def markets_partners(request):
    services.log_visit(request)
    return render(request, "Home/markets_partners.html", services.static_page("Markets & Partners"))


def sustainability_impact(request):
    services.log_visit(request)
    return render(request, "Home/sustainability_impact.html", services.static_page("Sustainability & Impact"))


def broker(request):
    services.log_visit(request)
    return render(request, "Home/broker.html", services.static_page("Become a Broker"))


def investor(request):
    services.log_visit(request)
    return render(request, "Home/investor.html", services.static_page("Become an Investor"))


def credit_summary(request):
    services.log_visit(request)
    return render(request, "Home/credit_summary.html", services.static_page("Executive Credit Summary"))


def contact(request):
    return render(request, "Home/Contact_View.html", services.contact_context())


def shop(request, category_slug=""):
    try:
        ctx = services.shop_context(category_slug, request.GET.get("q", ""))
    except services.HomeError:
        raise Http404("Catégorie introuvable")
    return render(request, "Home/shop.html", ctx)


def shop_api_products(request):
    products = services.shop_products(
        category_slug=request.GET.get("category", ""),
        query=request.GET.get("q", ""),
    )
    html = "".join(render_to_string("Home/_shop_card.html", {"p": product}, request=request) for product in products)
    return JsonResponse({"success": True, "html": html, "count": len(products)})


def shop_detail(request, slug):
    try:
        ctx = services.shop_detail(slug)
    except services.HomeError:
        raise Http404("Produit introuvable")
    return render(request, "Home/shop_detail.html", ctx)


@require_POST
def contact_send(request):
    try:
        message = services.send_contact(_json_body(request), request)
    except services.HomeError as exc:
        return JsonResponse({"ok": False, "message": exc.message}, status=exc.status)
    return JsonResponse({"ok": True, "message": message})


@require_POST
def newsletter(request):
    data = _json_body(request)
    try:
        message = services.subscribe_newsletter(data.get("email"), request)
    except services.HomeError as exc:
        return JsonResponse({"ok": False, "message": exc.message}, status=exc.status)
    return JsonResponse({"ok": True, "message": message})


@require_POST
def broker_store(request):
    try:
        message = services.create_broker(_json_body(request))
    except services.HomeError as exc:
        return JsonResponse({"status": "error", "message": exc.message}, status=exc.status)
    return JsonResponse({"status": "success", "message": message})


@require_POST
def investor_store(request):
    try:
        message = services.create_investor(_json_body(request))
    except services.HomeError as exc:
        return JsonResponse({"status": "error", "message": exc.message}, status=exc.status)
    return JsonResponse({"status": "success", "message": message})


def pays_search(request):
    return JsonResponse(services.search_countries(request.GET.get("q", "")), safe=False)


def detect_country(request):
    return JsonResponse(services.detect_country(request))


def ajax_search(request):
    return JsonResponse(services.global_search(request.GET.get("q", "")))


def media_index(request):
    return render(
        request,
        "Home/media.html",
        services.media_page(
            f"Media - {brand_sigle()}",
            page=request.GET.get("page", 1),
            media_type=request.GET.get("type", ""),
            search=request.GET.get("q", ""),
            sort=request.GET.get("sort", "recent"),
            category=request.GET.get("category", ""),
        ),
    )


def media_trending(request):
    return render(
        request,
        "Home/media.html",
        services.media_page(f"Trending - {brand_sigle()}", page=request.GET.get("page", 1), media_type="video"),
    )


def media_news(request):
    return render(
        request,
        "Home/media.html",
        services.media_page(f"News - {brand_sigle()}", page=request.GET.get("page", 1), category="News"),
    )


def media_testimonials(request):
    return render(
        request,
        "Home/media.html",
        services.media_page(f"Témoignages - {brand_sigle()}", page=request.GET.get("page", 1), category="Témoignage"),
    )


def media_type(request, media_type):
    if media_type not in services.MEDIA_TYPES:
        raise Http404("Type de média introuvable")
    return render(
        request,
        "Home/media.html",
        services.media_page(f"{media_type.title()} - {brand_sigle()}", page=request.GET.get("page", 1), media_type=media_type),
    )


def media_detail(request, identifier):
    try:
        ctx = services.media_detail(identifier, request)
    except services.HomeError:
        raise Http404("Média introuvable")
    return render(request, "Home/media_detail.html", ctx)


def _media_items_html(request, items):
    return "".join(render_to_string("Home/_media_search_item.html", {"item": item}, request=request) for item in items)


def media_search(request):
    query = request.GET.get("q", "")
    if len(query.strip()) < 2:
        return redirect("/media")
    return render(
        request,
        "Home/media.html",
        services.media_page(
            f"Recherche : {query} - {brand_sigle()}",
            page=request.GET.get("page", 1),
            search=query,
            media_type=request.GET.get("type", ""),
            sort=request.GET.get("sort", "recent"),
        ),
    )


def media_live_search(request):
    query = request.GET.get("q", "").strip()
    if len(query) < 2:
        return JsonResponse({"success": True, "html": "", "count": 0})
    items = services.media_list(search=query, limit=12)["items"]
    html = _media_items_html(request, items)
    if not html:
        html = '<div class="search-no-result"><i class="bi bi-search"></i><p>No results</p></div>'
    return JsonResponse({"success": True, "html": html, "count": len(items)})


def media_grid_api(request):
    data = services.media_grid(
        page=request.GET.get("page", 1),
        media_type=request.GET.get("type", ""),
        search=request.GET.get("q", ""),
        category=request.GET.get("category", ""),
    )
    for item in data["medias"]:
        item["card_html"] = render_to_string("Home/_media_card.html", {"item": item}, request=request)
    return JsonResponse(
        {
            "success": True,
            "medias": data["medias"],
            "categories": data["categories"],
            "total": data["total"],
            "page": data["page"],
            "has_more": data["has_more"],
        }
    )


@csrf_exempt
@require_POST
def media_track_view(request):
    data = _json_body(request)
    media_id = data.get("id_media") or request.POST.get("id_media")
    try:
        media_id = int(media_id)
    except (TypeError, ValueError):
        return JsonResponse({"success": False, "message": "Média invalide"}, status=400)
    row = services.GalerieMedia.objects.filter(id_media=media_id, est_actif=1).first()
    if not row:
        return JsonResponse({"success": False, "message": "Média introuvable"}, status=404)
    services.record_media_view(row, request)
    views = services.MediaView.objects.filter(id_media=media_id).count()
    return JsonResponse({"success": True, "views": views})


def media_download(request, identifier=""):
    try:
        return services.media_file(identifier, request)
    except services.HomeError:
        raise Http404("Fichier introuvable")
