from fastapi import APIRouter, Request

from api.admin_utils import ip
from core.models import Pays

router = APIRouter(tags=["home"])


def _country_payload(row):
    return {
        "id": row.id,
        "pays": row.pays,
        "ISO_3166_1_2_Letter_Code": row.iso2,
    }


@router.get("/pays/search")
def pays_search(q: str = ""):
    q = (q or "").strip()
    if not q:
        return []
    rows = Pays.objects.filter(pays__icontains=q).order_by("pays")[:15]
    return [_country_payload(row) for row in rows]


@router.get("/detect-country")
def detect_country(request: Request):
    client_ip = ip(request)
    if client_ip in {"127.0.0.1", "::1", "localhost"}:
        return {"id": "", "pays": ""}

    code = (
        request.headers.get("cf-ipcountry")
        or request.headers.get("x-country-code")
        or ""
    ).upper()
    if not code or code in {"XX", "T1"}:
        return {"id": "", "pays": ""}

    row = Pays.objects.filter(iso2__iexact=code).first()
    return {"id": row.id, "pays": row.pays} if row else {"id": "", "pays": ""}
