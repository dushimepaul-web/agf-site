<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1><?= $title ?></h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li><li class="breadcrumb-item">Finance</li><li class="breadcrumb-item"><a href="<?= base_url('Brokers') ?>">Intermédiaires</a></li><li class="breadcrumb-item active"><?= $broker ? 'Modifier' : 'Ajouter' ?></li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-10"><div class="card"><div class="card-body">
    <form id="brokerForm">
      <input type="hidden" id="id" value="<?= $broker['id'] ?? '' ?>">
      <h6 class="text-primary mb-3">Informations personnelles</h6>
      <div class="row mb-3">
        <div class="col-md-6"><label class="form-label">Nom complet *</label><input type="text" class="form-control" id="full_name" value="<?= htmlspecialchars($broker['full_name'] ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label">Firme *</label><input type="text" class="form-control" id="firm_name" value="<?= htmlspecialchars($broker['firm_name'] ?? '') ?>" required></div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control" id="email" value="<?= htmlspecialchars($broker['email'] ?? '') ?>" required></div>
        <div class="col-md-3"><label class="form-label">Téléphone</label><input type="text" class="form-control" id="mobile_phone" value="<?= htmlspecialchars($broker['mobile_phone'] ?? '') ?>"></div>
        <div class="col-md-3"><label class="form-label">WhatsApp</label><input type="text" class="form-control" id="whatsapp" value="<?= htmlspecialchars($broker['whatsapp'] ?? '') ?>"></div>
      </div>
      <h6 class="text-primary mb-3 mt-4">Informations réglementaires</h6>
      <div class="row mb-3">
        <div class="col-md-4"><label class="form-label">Juridiction</label><input type="text" class="form-control" id="jurisdiction" value="<?= htmlspecialchars($broker['jurisdiction_of_incorporation'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">N° d'enregistrement</label><input type="text" class="form-control" id="reg_number" value="<?= htmlspecialchars($broker['registration_number'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Statut réglementaire</label>
          <select class="form-select" id="reg_status">
            <option value="">-- Sélectionner --</option>
            <option value="licensed" <?= ($broker['regulatory_status'] ?? '') == 'licensed' ? 'selected' : '' ?>>Licensed</option>
            <option value="registered" <?= ($broker['regulatory_status'] ?? '') == 'registered' ? 'selected' : '' ?>>Registered</option>
            <option value="authorized" <?= ($broker['regulatory_status'] ?? '') == 'authorized' ? 'selected' : '' ?>>Authorized</option>
            <option value="exempted" <?= ($broker['regulatory_status'] ?? '') == 'exempted' ? 'selected' : '' ?>>Exempted</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><label class="form-label">Autorité réglementaire</label><input type="text" class="form-control" id="reg_auth" value="<?= htmlspecialchars($broker['regulatory_authority'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label">Site web</label><input type="url" class="form-control" id="website" value="<?= htmlspecialchars($broker['corporate_website'] ?? '') ?>"></div>
      </div>
      <h6 class="text-primary mb-3 mt-4">Capacités</h6>
      <div class="row mb-3">
        <?php foreach(['investment_broker','placement_agent','corporate_finance_advisor','fund_manager','family_office_rep','esg_advisor','independent_introducer'] as $cap): ?>
        <div class="col-md-3">
          <div class="form-check"><input class="form-check-input" type="checkbox" id="cap_<?= $cap ?>" <?= ($broker['capacity_'.$cap] ?? 0) ? 'checked' : '' ?>><label class="form-check-label" for="cap_<?= $cap ?>"><?= ucwords(str_replace('_',' ',$cap)) ?></label></div>
        </div>
        <?php endforeach; ?>
      </div>
      <h6 class="text-primary mb-3 mt-4">Intérêts investisseurs</h6>
      <div class="row mb-3">
        <?php foreach(['private_equity','venture_capital','esg_impact','dfi','institutional','hnwi','sovereign'] as $inv): ?>
        <div class="col-md-3">
          <div class="form-check"><input class="form-check-input" type="checkbox" id="inv_<?= $inv ?>" <?= ($broker['investor_'.$inv] ?? 0) ? 'checked' : '' ?>><label class="form-check-label" for="inv_<?= $inv ?>"><?= ucwords(str_replace('_',' ',$inv)) ?></label></div>
        </div>
        <?php endforeach; ?>
      </div>
      <h6 class="text-primary mb-3 mt-4">Modèle d'engagement</h6>
      <div class="row mb-3">
        <div class="col-md-6">
          <select class="form-select" id="engagement_model">
            <option value="">-- Sélectionner --</option>
            <option value="retainer" <?= ($broker['engagement_model'] ?? '') == 'retainer' ? 'selected' : '' ?>>Retainer</option>
            <option value="success_fee" <?= ($broker['engagement_model'] ?? '') == 'success_fee' ? 'selected' : '' ?>>Success Fee</option>
            <option value="hybrid" <?= ($broker['engagement_model'] ?? '') == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            <option value="equity_co_invest" <?= ($broker['engagement_model'] ?? '') == 'equity_co_invest' ? 'selected' : '' ?>>Equity Co-Invest</option>
          </select>
        </div>
      </div>
      <div class="text-end mt-4">
        <a href="<?= base_url('Brokers') ?>" class="btn btn-secondary me-2">Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Enregistrer</button>
      </div>
    </form>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
document.getElementById('brokerForm').onsubmit = async function(e) {
  e.preventDefault();
  const id = document.getElementById('id').value;
  const data = {
    full_name: document.getElementById('full_name').value,
    firm_name: document.getElementById('firm_name').value,
    email: document.getElementById('email').value,
    mobile_phone: document.getElementById('mobile_phone').value,
    whatsapp: document.getElementById('whatsapp').value,
    jurisdiction_of_incorporation: document.getElementById('jurisdiction').value,
    registration_number: document.getElementById('reg_number').value,
    regulatory_status: document.getElementById('reg_status').value,
    regulatory_authority: document.getElementById('reg_auth').value,
    corporate_website: document.getElementById('website').value,
    engagement_model: document.getElementById('engagement_model').value,
    capacity_investment_broker: document.getElementById('cap_investment_broker').checked?1:0,
    capacity_placement_agent: document.getElementById('cap_placement_agent').checked?1:0,
    capacity_corporate_finance_advisor: document.getElementById('cap_corporate_finance_advisor').checked?1:0,
    capacity_fund_manager: document.getElementById('cap_fund_manager').checked?1:0,
    capacity_family_office_rep: document.getElementById('cap_family_office_rep').checked?1:0,
    capacity_esg_advisor: document.getElementById('cap_esg_advisor').checked?1:0,
    capacity_independent_introducer: document.getElementById('cap_independent_introducer').checked?1:0,
    investor_private_equity: document.getElementById('inv_private_equity').checked?1:0,
    investor_venture_capital: document.getElementById('inv_venture_capital').checked?1:0,
    investor_esg_impact: document.getElementById('inv_esg_impact').checked?1:0,
    investor_dfi: document.getElementById('inv_dfi').checked?1:0,
    investor_institutional: document.getElementById('inv_institutional').checked?1:0,
    investor_hnwi: document.getElementById('inv_hnwi').checked?1:0,
    investor_sovereign: document.getElementById('inv_sovereign').checked?1:0
  };
  const res = id ? await API.post('Brokers/api_update/'+id, data) : await API.post('Brokers/api_create', data);
  if (res.success) { API.simpleAlert('success','Succès',res.message); setTimeout(()=>location.href=BASE_URL+'Brokers',1200); }
  else API.simpleAlert('error','Erreur',res.message);
};
</script>
