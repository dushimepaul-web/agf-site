from fastapi import APIRouter, Depends

from api.deps import get_staff_session
from modules.Dashboard.services import dashboard_payload

router = APIRouter(tags=["dashboard"])


@router.get("/dashboard/data")
def dashboard_data(days: int = 30, session=Depends(get_staff_session)):
    if days <= 0:
        days = 30
    days = max(7, min(90, days))
    return {"success": True, "message": "Succès", "data": dashboard_payload(days)}


@router.get("/dashboard/filters")
def dashboard_filters(session=Depends(get_staff_session)):
    return {
        "success": True,
        "message": "Succès",
        "data": {
            "periods": [
                {"days": 7, "label": "7 jours"},
                {"days": 30, "label": "30 jours"},
                {"days": 90, "label": "90 jours"},
            ]
        },
    }
