from django.db import models


class Role(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36)
    code = models.CharField(max_length=50)
    nom = models.CharField(max_length=100)
    description = models.TextField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "roles"

    def __str__(self):
        return self.nom


class User(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36)
    username = models.CharField(max_length=100)
    email = models.CharField(max_length=150)
    password_hash = models.CharField(max_length=255)
    nom = models.CharField(max_length=100)
    prenom = models.CharField(max_length=100, blank=True, default="")
    telephone = models.CharField(max_length=30, null=True, blank=True)
    photo = models.CharField(max_length=255, null=True, blank=True)
    role_id = models.IntegerField()
    actif = models.SmallIntegerField(default=1)
    is_active = models.SmallIntegerField(default=1)
    est_verifie = models.SmallIntegerField(default=0)
    derniere_connexion = models.DateTimeField(null=True, blank=True)
    last_login_at = models.DateTimeField(null=True, blank=True)
    last_login_ip = models.CharField(max_length=45, null=True, blank=True)
    two_factor_enabled = models.SmallIntegerField(default=0)
    two_factor_secret = models.CharField(max_length=255, null=True, blank=True)
    email_verification_token = models.CharField(max_length=255, null=True, blank=True)
    email_verified_at = models.DateTimeField(null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "users"

    @property
    def full_name(self):
        return f"{self.nom or ''} {self.prenom or ''}".strip()

    def __str__(self):
        return self.email


class Menu(models.Model):
    id_menu = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36)
    code = models.CharField(max_length=50)
    libelle = models.CharField(max_length=100)
    icon = models.CharField(max_length=50, null=True, blank=True)
    route = models.CharField(max_length=255, null=True, blank=True)
    parent_id = models.IntegerField(null=True, blank=True)
    ordre = models.IntegerField(default=0)

    class Meta:
        managed = False
        db_table = "menus"

    def __str__(self):
        return self.libelle


class RolePermission(models.Model):
    id = models.AutoField(primary_key=True)
    role_id = models.IntegerField()
    module_code = models.CharField(max_length=50)
    permission = models.CharField(max_length=50)
    allowed = models.SmallIntegerField(default=1)

    class Meta:
        managed = False
        db_table = "roles_droits"

    def __str__(self):
        return f"{self.role_id}:{self.module_code}:{self.permission}"


class Log(models.Model):
    id = models.AutoField(primary_key=True)
    user_id = models.IntegerField(null=True, blank=True)
    action = models.CharField(max_length=100)
    description = models.TextField(null=True, blank=True)
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    url = models.CharField(max_length=500, null=True, blank=True)
    method = models.CharField(max_length=10, null=True, blank=True)
    niveau = models.CharField(max_length=20, default="info")
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "logs"

    def __str__(self):
        return self.action


class UserSession(models.Model):
    id = models.AutoField(primary_key=True)
    user_id = models.IntegerField()
    session_id = models.CharField(max_length=255)
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    device_type = models.CharField(max_length=20, default="unknown")
    platform = models.CharField(max_length=100, null=True, blank=True)
    browser = models.CharField(max_length=100, null=True, blank=True)
    login_time = models.DateTimeField()
    last_activity = models.DateTimeField()
    expiry_time = models.DateTimeField()
    is_active = models.SmallIntegerField(default=1)
    logout_time = models.DateTimeField(null=True, blank=True)
    logout_reason = models.CharField(max_length=20, null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "user_sessions"

    def __str__(self):
        return self.session_id


class VisitorLog(models.Model):
    id = models.AutoField(primary_key=True)
    ip_address = models.CharField(max_length=45)
    user_agent = models.TextField(null=True, blank=True)
    device = models.CharField(max_length=20, default="Desktop")
    page = models.CharField(max_length=255, null=True, blank=True)
    referer = models.CharField(max_length=255, null=True, blank=True)
    visit_date = models.DateField()
    visit_time = models.TimeField()
    country = models.CharField(max_length=100, null=True, blank=True)
    city = models.CharField(max_length=100, null=True, blank=True)
    latitude = models.CharField(max_length=50, null=True, blank=True)
    longitude = models.CharField(max_length=50, null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "visitors_logs"

    def __str__(self):
        return f"{self.ip_address} {self.visit_date}"


class Setting(models.Model):
    id = models.AutoField(primary_key=True)
    clef = models.CharField(max_length=100)
    valeur = models.TextField(null=True, blank=True)
    uuid = models.CharField(max_length=36)
    deleted_at = models.DateTimeField(null=True, blank=True)
    cree_le = models.DateTimeField(null=True, blank=True)
    modifie_le = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "parametres"

    def __str__(self):
        return self.clef

class ProduitCategorie(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36, null=True, blank=True)
    nom = models.CharField(max_length=150)
    description = models.TextField(null=True, blank=True)
    slug = models.CharField(max_length=100, null=True, blank=True)
    ordre = models.IntegerField(default=0)
    est_actif = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "produit_categories"

    def __str__(self):
        return self.nom


class Produit(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36, null=True, blank=True)
    categorie_id = models.IntegerField(default=0)
    nom = models.CharField(max_length=200)
    description = models.TextField(null=True, blank=True)
    prix = models.CharField(max_length=100, null=True, blank=True)
    slug = models.CharField(max_length=150, null=True, blank=True)
    image = models.CharField(max_length=255, null=True, blank=True)
    conditionnement = models.CharField(max_length=100, null=True, blank=True)
    est_certifie = models.SmallIntegerField(default=0)
    est_actif = models.SmallIntegerField(default=1)
    ordre = models.IntegerField(default=0)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "produits"

    def __str__(self):
        return self.nom


class UniteAffaire(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36, null=True, blank=True)
    code = models.CharField(max_length=50)
    nom = models.CharField(max_length=200)
    description = models.TextField(null=True, blank=True)
    slogan = models.TextField(null=True, blank=True)
    logo = models.CharField(max_length=255, null=True, blank=True)
    slug = models.CharField(max_length=100, null=True, blank=True)
    ordre = models.IntegerField(default=0)
    est_actif = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "unites_affaires"

    def __str__(self):
        return self.nom


class Medecin(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36, null=True, blank=True)
    user_id = models.IntegerField(null=True, blank=True)
    specialite = models.CharField(max_length=150, null=True, blank=True)
    numero_licence = models.CharField(max_length=100, null=True, blank=True)
    annees_experience = models.IntegerField(null=True, blank=True)
    honoraires_consultation = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    currency = models.CharField(max_length=10, null=True, blank=True)
    USD_EUR_Equivalent_en_BIF = models.DecimalField(max_digits=12, decimal_places=2, null=True, blank=True)
    prix_pour_residant_burundi = models.DecimalField(max_digits=12, decimal_places=2, null=True, blank=True)
    est_disponible = models.SmallIntegerField(default=1)
    note_moyenne = models.DecimalField(max_digits=3, decimal_places=2, null=True, blank=True)
    nombre_avis = models.IntegerField(default=0)
    bio = models.TextField(null=True, blank=True)
    diplomes = models.TextField(null=True, blank=True)
    langues_parlees = models.CharField(max_length=255, null=True, blank=True)
    disponibilite = models.TextField(null=True, blank=True)
    actif = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "medecins"

    def __str__(self):
        return self.specialite or str(self.id)


class HoraireMedecin(models.Model):
    id = models.AutoField(primary_key=True)
    medecin_id = models.IntegerField()
    jour_semaine = models.CharField(max_length=20)
    heure_debut = models.TimeField(null=True, blank=True)
    heure_fin = models.TimeField(null=True, blank=True)
    est_actif = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "horaires_medecins"


class Consultation(models.Model):
    id = models.AutoField(primary_key=True)
    uuid = models.CharField(max_length=36, null=True, blank=True)
    numero_consultation = models.CharField(max_length=50, null=True, blank=True)
    patient_id = models.IntegerField(null=True, blank=True)
    medecin_id = models.IntegerField(null=True, blank=True)
    type = models.CharField(max_length=30, null=True, blank=True)
    patient_nom = models.CharField(max_length=150, null=True, blank=True)
    patient_prenom = models.CharField(max_length=150, null=True, blank=True)
    patient_poids = models.CharField(max_length=20, null=True, blank=True)
    patient_taille = models.CharField(max_length=20, null=True, blank=True)
    poids = models.CharField(max_length=20, null=True, blank=True)
    taille = models.CharField(max_length=20, null=True, blank=True)
    country_id = models.IntegerField(null=True, blank=True)
    age = models.IntegerField(null=True, blank=True)
    whatsapppatient = models.CharField(max_length=30, null=True, blank=True)
    patient_adresse = models.TextField(null=True, blank=True)
    description_symptomes = models.TextField(null=True, blank=True)
    duree_symptomes = models.CharField(max_length=100, null=True, blank=True)
    consultation_precedente = models.CharField(max_length=10, null=True, blank=True)
    examens_demandes = models.TextField(null=True, blank=True)
    ordonnances = models.TextField(null=True, blank=True)
    diagnostic = models.TextField(null=True, blank=True)
    traitement = models.TextField(null=True, blank=True)
    notes_medecin = models.TextField(null=True, blank=True)
    date_souhaitee = models.DateTimeField(null=True, blank=True)
    date_confirmee = models.DateTimeField(null=True, blank=True)
    whatsapp_envoye = models.SmallIntegerField(default=0)
    statut = models.CharField(max_length=30, default="en_attente")
    honoraires_consultation = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    motif_annulation = models.TextField(null=True, blank=True)
    prix_ht = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    devise = models.CharField(max_length=10, null=True, blank=True)
    tva = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    paiement_statut = models.CharField(max_length=30, null=True, blank=True)
    mode_paiement = models.CharField(max_length=100, null=True, blank=True)
    preuve_paiement = models.CharField(max_length=255, null=True, blank=True)
    ip_creation = models.CharField(max_length=45, null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "consultations"

    def __str__(self):
        return self.numero_consultation or str(self.id)


class ConsultationMedia(models.Model):
    id = models.AutoField(primary_key=True)
    consultation_id = models.IntegerField()
    fichier_url = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=20, default="medical")
    ordre = models.IntegerField(default=0)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "consultation_medias"


class ContactMessage(models.Model):
    id_contact = models.AutoField(primary_key=True, db_column="IdContact")
    full_name = models.CharField(max_length=250, db_column="FullName")
    email = models.CharField(max_length=250, db_column="Email")
    subject = models.CharField(max_length=250, null=True, blank=True, db_column="Subject")
    message = models.TextField(db_column="Message")
    phone_number = models.CharField(max_length=12, blank=True, default="", db_column="PhoneNumber")
    date_creation = models.DateTimeField(null=True, blank=True, db_column="Date_creation")
    location = models.CharField(max_length=200, blank=True, default="", db_column="Location")
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    is_readed = models.SmallIntegerField(default=0)

    class Meta:
        managed = False
        db_table = "contact_us"


class Faq(models.Model):
    id_faq = models.AutoField(primary_key=True)
    question = models.TextField()
    reponse = models.TextField(null=True, blank=True)
    categorie = models.CharField(max_length=100, null=True, blank=True)
    ordre = models.IntegerField(default=0)
    est_publiee = models.SmallIntegerField(default=1)
    id_page_associee = models.IntegerField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "faq"


class Partenaire(models.Model):
    id_partenaire = models.AutoField(primary_key=True)
    type_partenaire = models.CharField(max_length=50, null=True, blank=True)
    nom = models.CharField(max_length=255)
    logo_url = models.CharField(max_length=500, null=True, blank=True)
    description = models.TextField(null=True, blank=True)
    pays = models.CharField(max_length=100, null=True, blank=True)
    site_web = models.CharField(max_length=255, null=True, blank=True)
    niveau_partenariat = models.CharField(max_length=50, null=True, blank=True)
    date_debut = models.DateField(null=True, blank=True)
    est_actif = models.SmallIntegerField(default=1)
    id_page_associee = models.IntegerField(null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "partenaires"

    def __str__(self):
        return self.nom


class SocialLink(models.Model):
    id = models.AutoField(primary_key=True)
    platform = models.CharField(max_length=50)
    label = models.CharField(max_length=100, null=True, blank=True)
    url = models.CharField(max_length=500)
    icon_class = models.CharField(max_length=100, null=True, blank=True)
    icon_name = models.CharField(max_length=50, null=True, blank=True)
    display_order = models.IntegerField(default=0)
    is_active = models.SmallIntegerField(default=1)
    target_blank = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)
    followers = models.CharField(max_length=50, null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "social_links"


class Temoignage(models.Model):
    id = models.AutoField(primary_key=True)
    titre = models.CharField(max_length=255, null=True, blank=True)
    video_url = models.CharField(max_length=500, null=True, blank=True)
    miniature = models.CharField(max_length=500, null=True, blank=True)
    auteur = models.CharField(max_length=255, null=True, blank=True)
    description = models.TextField(null=True, blank=True)
    est_actif = models.SmallIntegerField(default=1)
    ordre = models.IntegerField(default=0)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "temoignages"


class GalerieMedia(models.Model):
    id_media = models.AutoField(primary_key=True)
    titre = models.CharField(max_length=255, null=True, blank=True)
    slug = models.CharField(max_length=255, null=True, blank=True)
    type = models.CharField(max_length=20, null=True, blank=True)
    sous_type = models.CharField(max_length=50, null=True, blank=True)
    fichier = models.CharField(max_length=500, null=True, blank=True)
    lien = models.CharField(max_length=500, null=True, blank=True)
    miniature = models.CharField(max_length=500, null=True, blank=True)
    description = models.TextField(null=True, blank=True)
    categorie = models.CharField(max_length=100, null=True, blank=True)
    date_media = models.DateField(null=True, blank=True)
    credits = models.CharField(max_length=255, null=True, blank=True)
    message_reseaux = models.TextField(null=True, blank=True)
    taille = models.IntegerField(null=True, blank=True)
    mime_type = models.CharField(max_length=100, null=True, blank=True)
    duree = models.IntegerField(null=True, blank=True)
    telechargements = models.IntegerField(default=0)
    bitrate = models.IntegerField(null=True, blank=True)
    sample_rate = models.IntegerField(null=True, blank=True)
    channels = models.SmallIntegerField(null=True, blank=True)
    waveform = models.CharField(max_length=500, null=True, blank=True)
    spectrogram = models.CharField(max_length=500, null=True, blank=True)
    waveform_data = models.TextField(null=True, blank=True)
    metadata_id3 = models.TextField(null=True, blank=True)
    converted_versions = models.TextField(null=True, blank=True)
    est_actif = models.SmallIntegerField(default=1)
    is_for_website = models.SmallIntegerField(default=1)
    is_for_whatsapp = models.SmallIntegerField(default=0)
    contenu_texte = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "galerie_medias"

    def __str__(self):
        return self.titre or str(self.id_media)


class MediaView(models.Model):
    id = models.AutoField(primary_key=True)
    id_media = models.IntegerField()
    user_id = models.IntegerField(null=True, blank=True)
    session_id = models.CharField(max_length=255, null=True, blank=True)
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    viewed_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "media_views"


class Investor(models.Model):
    id = models.AutoField(primary_key=True)
    full_name = models.CharField(max_length=150)
    organization = models.CharField(max_length=150, null=True, blank=True)
    position_title = models.CharField(max_length=150, null=True, blank=True)
    id_pays = models.IntegerField(null=True, blank=True)
    email = models.CharField(max_length=150)
    phone = models.CharField(max_length=50, null=True, blank=True)
    interest_equity = models.SmallIntegerField(default=0)
    interest_debt = models.SmallIntegerField(default=0)
    interest_blended_finance = models.SmallIntegerField(default=0)
    interest_grant = models.SmallIntegerField(default=0)
    interest_strategic_partnership = models.SmallIntegerField(default=0)
    interest_technical_collaboration = models.SmallIntegerField(default=0)
    interest_offtake_distribution = models.SmallIntegerField(default=0)
    interest_other = models.CharField(max_length=255, null=True, blank=True)
    commitment_range = models.CharField(max_length=100, null=True, blank=True)
    focus_research_lab = models.SmallIntegerField(default=0)
    focus_gmp_facility = models.SmallIntegerField(default=0)
    focus_medicinal_plant = models.SmallIntegerField(default=0)
    focus_commercialization = models.SmallIntegerField(default=0)
    focus_full_platform = models.SmallIntegerField(default=0)
    timeline = models.CharField(max_length=100, null=True, blank=True)
    strategic_message = models.TextField(null=True, blank=True)
    agree_contact = models.SmallIntegerField(default=0)
    non_binding_confirmation = models.SmallIntegerField(default=0)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "investors"


class Broker(models.Model):
    id = models.AutoField(primary_key=True)
    full_name = models.CharField(max_length=150)
    firm_name = models.CharField(max_length=200, null=True, blank=True)
    jurisdiction_of_incorporation = models.CharField(max_length=150, null=True, blank=True)
    registration_number = models.CharField(max_length=100, null=True, blank=True)
    regulatory_status = models.CharField(max_length=50, null=True, blank=True)
    regulatory_authority = models.CharField(max_length=150, null=True, blank=True)
    id_pays = models.IntegerField(null=True, blank=True)
    email = models.CharField(max_length=150)
    mobile_phone = models.CharField(max_length=50, null=True, blank=True)
    whatsapp = models.CharField(max_length=50, null=True, blank=True)
    corporate_website = models.CharField(max_length=200, null=True, blank=True)
    capacity_investment_broker = models.SmallIntegerField(default=0)
    capacity_placement_agent = models.SmallIntegerField(default=0)
    capacity_corporate_finance_advisor = models.SmallIntegerField(default=0)
    capacity_fund_manager = models.SmallIntegerField(default=0)
    capacity_family_office_rep = models.SmallIntegerField(default=0)
    capacity_esg_advisor = models.SmallIntegerField(default=0)
    capacity_independent_introducer = models.SmallIntegerField(default=0)
    capacity_other = models.CharField(max_length=255, null=True, blank=True)
    investor_private_equity = models.SmallIntegerField(default=0)
    investor_venture_capital = models.SmallIntegerField(default=0)
    investor_esg_impact = models.SmallIntegerField(default=0)
    investor_dfi = models.SmallIntegerField(default=0)
    investor_institutional = models.SmallIntegerField(default=0)
    investor_hnwi = models.SmallIntegerField(default=0)
    investor_sovereign = models.SmallIntegerField(default=0)
    typical_ticket_size = models.CharField(max_length=150, null=True, blank=True)
    geographic_coverage = models.TextField(null=True, blank=True)
    mandate_equity = models.SmallIntegerField(default=0)
    mandate_structured_debt = models.SmallIntegerField(default=0)
    mandate_blended_finance = models.SmallIntegerField(default=0)
    mandate_grant = models.SmallIntegerField(default=0)
    mandate_strategic_partnership = models.SmallIntegerField(default=0)
    mandate_full_program = models.SmallIntegerField(default=0)
    engagement_model = models.CharField(max_length=100, null=True, blank=True)
    confirm_authorized = models.SmallIntegerField(default=0)
    confirm_aml_kyc = models.SmallIntegerField(default=0)
    acknowledge_no_exclusivity = models.SmallIntegerField(default=0)
    understand_formal_mandate_required = models.SmallIntegerField(default=0)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "brokers"


class NewsletterAbonne(models.Model):
    id = models.AutoField(primary_key=True)
    email = models.CharField(max_length=190)
    source = models.CharField(max_length=50, null=True, blank=True)
    ip_address = models.CharField(max_length=45, null=True, blank=True)
    user_agent = models.TextField(null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)
    updated_at = models.DateTimeField(null=True, blank=True)
    deleted_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "newsletter_abonnes"


class ModePaiement(models.Model):
    id = models.AutoField(primary_key=True)
    nom = models.CharField(max_length=100)
    description = models.TextField(null=True, blank=True)
    numero_compte = models.CharField(max_length=50, null=True, blank=True)
    nom_compte = models.CharField(max_length=150, null=True, blank=True)
    actif = models.SmallIntegerField(default=1)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        managed = False
        db_table = "mode_paiements"


class Pays(models.Model):
    id = models.AutoField(primary_key=True)
    id_country = models.CharField(max_length=255, null=True, blank=True)
    pays = models.CharField(max_length=255, null=True, blank=True)
    formal_name = models.CharField(max_length=255, null=True, blank=True, db_column="Formal_Name")
    type = models.CharField(max_length=255, null=True, blank=True)
    sub_type = models.CharField(max_length=255, null=True, blank=True, db_column="Sub_Type")
    sovereignty = models.CharField(max_length=255, null=True, blank=True)
    capital = models.CharField(max_length=255, null=True, blank=True)
    currency_code = models.CharField(max_length=255, null=True, blank=True, db_column="ISO_4217_Currency_Code")
    currency_name = models.CharField(max_length=255, null=True, blank=True, db_column="ISO_4217_Currency_Name")
    telephone_code = models.CharField(max_length=255, null=True, blank=True, db_column="ITU_T_Telephone_Code")
    iso2 = models.CharField(max_length=255, null=True, blank=True, db_column="ISO_3166_1_2_Letter_Code")
    iso3 = models.CharField(max_length=255, null=True, blank=True, db_column="ISO_3166_1_3_Letter_Code")
    iso_number = models.CharField(max_length=255, null=True, blank=True, db_column="ISO_3166_1_Number")
    tld = models.CharField(max_length=255, null=True, blank=True, db_column="IANA_Country_Code_TLD")

    class Meta:
        managed = False
        db_table = "pays"

    def __str__(self):
        return self.pays or ""
