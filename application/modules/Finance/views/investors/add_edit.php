<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1><?= $title ?></h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li><li class="breadcrumb-item">Finance</li><li class="breadcrumb-item"><a href="<?= base_url('Investors') ?>">Investisseurs</a></li><li class="breadcrumb-item active"><?= $investor ? 'Modifier' : 'Ajouter' ?></li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-10"><div class="card"><div class="card-body">
    <form id="investorForm">
      <input type="hidden" id="id" value="<?= $investor['id'] ?? '' ?>">
      <h6 class="text-primary mb-3">Informations personnelles</h6>
      <div class="row mb-3">
        <div class="col-md-4"><label class="form-label">Nom complet *</label><input type="text" class="form-control" id="full_name" value="<?= htmlspecialchars($investor['full_name'] ?? '') ?>" required></div>
        <div class="col-md-4"><label class="form-label">Organisation</label><input type="text" class="form-control" id="organization" value="<?= htmlspecialchars($investor['organization'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Poste</label><input type="text" class="form-control" id="position_title" value="<?= htmlspecialchars($investor['position_title'] ?? '') ?>"></div>
      </div>
      <div class="row mb-3">
        <div class="col-md-4"><label class="form-label">Email *</label><input type="email" class="form-control" id="email" value="<?= htmlspecialchars($investor['email'] ?? '') ?>" required></div>
        <div class="col-md-4"><label class="form-label">Téléphone</label><input type="text" class="form-control" id="phone" value="<?= htmlspecialchars($investor['phone'] ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Engagement</label>
          <select class="form-select" id="commitment_range">
            <option value="">-- Sélectionner --</option>
            <option value="<1M" <?= ($investor['commitment_range'] ?? '') == '<1M' ? 'selected' : '' ?>>Moins de 1M</option>
            <option value="1-5M" <?= ($investor['commitment_range'] ?? '') == '1-5M' ? 'selected' : '' ?>>1-5M</option>
            <option value="5-25M" <?= ($investor['commitment_range'] ?? '') == '5-25M' ? 'selected' : '' ?>>5-25M</option>
            <option value="25-100M" <?= ($investor['commitment_range'] ?? '') == '25-100M' ? 'selected' : '' ?>>25-100M</option>
            <option value="100M+" <?= ($investor['commitment_range'] ?? '') == '100M+' ? 'selected' : '' ?>>100M+</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-4"><label class="form-label">Timeline</label>
          <select class="form-select" id="timeline">
            <option value="Exploratory" <?= ($investor['timeline'] ?? '') == 'Exploratory' ? 'selected' : '' ?>>Exploratory</option>
            <option value="1-3 months" <?= ($investor['timeline'] ?? '') == '1-3 months' ? 'selected' : '' ?>>1-3 mois</option>
            <option value="3-6 months" <?= ($investor['timeline'] ?? '') == '3-6 months' ? 'selected' : '' ?>>3-6 mois</option>
            <option value="6-12 months" <?= ($investor['timeline'] ?? '') == '6-12 months' ? 'selected' : '' ?>>6-12 mois</option>
            <option value="12+ months" <?= ($investor['timeline'] ?? '') == '12+ months' ? 'selected' : '' ?>>12+ mois</option>
          </select>
        </div>
        <div class="col-md-8"><label class="form-label">Message stratégique</label><textarea class="form-control" id="strategic_message" rows="2"><?= htmlspecialchars($investor['strategic_message'] ?? '') ?></textarea></div>
      </div>
      <h6 class="text-primary mb-3 mt-4">Types d'investissement</h6>
      <div class="row mb-3">
        <?php foreach(['equity','debt','blended_finance','grant','strategic_partnership','technical_collaboration','offtake_distribution'] as $t): ?>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" id="int_<?= $t ?>" <?= ($investor['interest_'.$t] ?? 0) ? 'checked' : '' ?>><label class="form-check-label" for="int_<?= $t ?>"><?= ucwords(str_replace('_',' ',$t)) ?></label></div></div>
        <?php endforeach; ?>
      </div>
      <h6 class="text-primary mb-3 mt-4">Focus d'investissement</h6>
      <div class="row mb-3">
        <?php foreach(['research_lab','gmp_facility','medicinal_plant','commercialization','full_platform'] as $f): ?>
        <div class="col-md-3"><div class="form-check"><input class="form-check-input" type="checkbox" id="foc_<?= $f ?>" <?= ($investor['focus_'.$f] ?? 0) ? 'checked' : '' ?>><label class="form-check-label" for="foc_<?= $f ?>"><?= ucwords(str_replace('_',' ',$f)) ?></label></div></div>
        <?php endforeach; ?>
      </div>
      <div class="text-end mt-4">
        <a href="<?= base_url('Investors') ?>" class="btn btn-secondary me-2">Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Enregistrer</button>
      </div>
    </form>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
document.getElementById('investorForm').onsubmit = async function(e) {
  e.preventDefault();
  const id = document.getElementById('id').value;
  const data = {
    full_name: document.getElementById('full_name').value,
    organization: document.getElementById('organization').value,
    position_title: document.getElementById('position_title').value,
    email: document.getElementById('email').value,
    phone: document.getElementById('phone').value,
    commitment_range: document.getElementById('commitment_range').value,
    timeline: document.getElementById('timeline').value,
    strategic_message: document.getElementById('strategic_message').value
  };
  ['equity','debt','blended_finance','grant','strategic_partnership','technical_collaboration','offtake_distribution'].forEach(k=>{data['interest_'+k]=document.getElementById('int_'+k).checked?1:0;});
  ['research_lab','gmp_facility','medicinal_plant','commercialization','full_platform'].forEach(k=>{data['focus_'+k]=document.getElementById('foc_'+k).checked?1:0;});
  const res = id ? await API.post('Investors/api_update/'+id, data) : await API.post('Investors/api_create', data);
  if (res.success) { API.simpleAlert('success','Succès',res.message); setTimeout(()=>location.href=BASE_URL+'Investors',1200); }
  else API.simpleAlert('error','Erreur',res.message);
};
</script>
