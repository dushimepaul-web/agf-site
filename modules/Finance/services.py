from django.utils import timezone

from core.models import Broker, Investor


class FinanceError(Exception):
    def __init__(self, message, status=400):
        super().__init__(message)
        self.message = message
        self.status = status


def _clean(value):
    return str(value or "").strip()


def _int(value, default=0):
    try:
        return int(value)
    except (TypeError, ValueError):
        return default


def _dict(row):
    data = {}
    for field in row._meta.fields:
        value = getattr(row, field.attname)
        if hasattr(value, "isoformat"):
            value = value.isoformat()
        data[field.attname] = value
    return data


INVESTOR_TEXT = (
    "full_name", "organization", "position_title", "email", "phone",
    "commitment_range", "timeline", "strategic_message", "interest_other",
)
INVESTOR_FLAGS = (
    "agree_contact", "non_binding_confirmation", "interest_equity", "interest_debt",
    "interest_blended_finance", "interest_grant", "interest_strategic_partnership",
    "interest_technical_collaboration", "interest_offtake_distribution",
    "focus_research_lab", "focus_gmp_facility", "focus_medicinal_plant",
    "focus_commercialization", "focus_full_platform",
)


def investors():
    return [_dict(r) for r in Investor.objects.order_by("-created_at")]


def get_investor(investor_id):
    row = Investor.objects.filter(id=investor_id).first()
    if not row:
        raise FinanceError("Investisseur non trouvé", 404)
    return _dict(row)


def create_investor(data):
    data = data or {}
    full_name = _clean(data.get("full_name"))
    email = _clean(data.get("email"))
    if not full_name or not email:
        raise FinanceError("Nom et email obligatoires")
    values = {"full_name": full_name, "email": email}
    for key in INVESTOR_TEXT:
        if key not in values:
            values[key] = _clean(data.get(key)) or None
    for key in INVESTOR_FLAGS:
        values[key] = _int(data.get(key))
    values["id_pays"] = _int(data.get("id_pays")) or None
    values["created_at"] = timezone.now()
    values["updated_at"] = timezone.now()
    return Investor.objects.create(**values)


def update_investor(investor_id, data):
    data = data or {}
    row = Investor.objects.filter(id=investor_id).first()
    if not row:
        raise FinanceError("Investisseur non trouvé", 404)
    update = {}
    for key in INVESTOR_TEXT:
        if key in data:
            update[key] = _clean(data.get(key)) or None
    if "full_name" in update and not update["full_name"]:
        raise FinanceError("Le nom est obligatoire")
    if "email" in update and not update["email"]:
        raise FinanceError("L'email est obligatoire")
    for key in INVESTOR_FLAGS:
        if key in data:
            update[key] = _int(data.get(key))
    if "id_pays" in data:
        update["id_pays"] = _int(data.get("id_pays")) or None
    if not update:
        raise FinanceError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    Investor.objects.filter(id=investor_id).update(**update)
    return row, list(update.keys())


def delete_investor(investor_id):
    row = Investor.objects.filter(id=investor_id).first()
    if not row:
        raise FinanceError("Investisseur non trouvé", 404)
    row.delete()
    return row


BROKER_TEXT = (
    "full_name", "firm_name", "email", "jurisdiction_of_incorporation",
    "registration_number", "regulatory_status", "regulatory_authority",
    "mobile_phone", "whatsapp", "corporate_website", "typical_ticket_size",
    "geographic_coverage", "capacity_other", "engagement_model",
)
BROKER_FLAGS = (
    "capacity_investment_broker", "capacity_placement_agent",
    "capacity_corporate_finance_advisor", "capacity_fund_manager",
    "capacity_family_office_rep", "capacity_esg_advisor",
    "capacity_independent_introducer", "investor_private_equity",
    "investor_venture_capital", "investor_esg_impact", "investor_dfi",
    "investor_institutional", "investor_hnwi", "investor_sovereign",
    "mandate_equity", "mandate_structured_debt", "mandate_blended_finance",
    "mandate_grant", "mandate_strategic_partnership", "mandate_full_program",
    "confirm_authorized", "confirm_aml_kyc", "acknowledge_no_exclusivity",
    "understand_formal_mandate_required",
)


def brokers():
    return [_dict(r) for r in Broker.objects.order_by("-created_at")]


def get_broker(broker_id):
    row = Broker.objects.filter(id=broker_id).first()
    if not row:
        raise FinanceError("Intermédiaire non trouvé", 404)
    return _dict(row)


def create_broker(data):
    data = data or {}
    full_name = _clean(data.get("full_name"))
    firm_name = _clean(data.get("firm_name"))
    email = _clean(data.get("email"))
    if not full_name or not firm_name or not email:
        raise FinanceError("Nom, firme et email obligatoires")
    values = {"full_name": full_name, "firm_name": firm_name, "email": email}
    for key in BROKER_TEXT:
        if key not in values:
            values[key] = _clean(data.get(key)) or None
    for key in BROKER_FLAGS:
        values[key] = _int(data.get(key))
    values["id_pays"] = _int(data.get("id_pays")) or None
    values["created_at"] = timezone.now()
    values["updated_at"] = timezone.now()
    return Broker.objects.create(**values)


def update_broker(broker_id, data):
    data = data or {}
    row = Broker.objects.filter(id=broker_id).first()
    if not row:
        raise FinanceError("Intermédiaire non trouvé", 404)
    update = {}
    for key in BROKER_TEXT:
        if key in data:
            update[key] = _clean(data.get(key)) or None
    for key in ("full_name", "firm_name", "email"):
        if key in update and not update[key]:
            raise FinanceError("Nom, firme et email obligatoires")
    for key in BROKER_FLAGS:
        if key in data:
            update[key] = _int(data.get(key))
    if "id_pays" in data:
        update["id_pays"] = _int(data.get("id_pays")) or None
    if not update:
        raise FinanceError("Aucune donnée à modifier")
    update["updated_at"] = timezone.now()
    Broker.objects.filter(id=broker_id).update(**update)
    return row, list(update.keys())


def delete_broker(broker_id):
    row = Broker.objects.filter(id=broker_id).first()
    if not row:
        raise FinanceError("Intermédiaire non trouvé", 404)
    row.delete()
    return row
