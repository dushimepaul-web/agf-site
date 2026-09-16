<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1><?= $title ?></h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li><li class="breadcrumb-item">Public</li><li class="breadcrumb-item"><a href="<?= base_url('Partenaires') ?>">Partenaires</a></li><li class="breadcrumb-item active"><?= $partenaire ? 'Modifier' : 'Ajouter' ?></li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form id="partnerForm">
      <input type="hidden" id="id" value="<?= $partenaire['id'] ?? '' ?>">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Nom <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="nom" value="<?= htmlspecialchars($partenaire['nom'] ?? '') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Type <span class="text-danger">*</span></label>
          <select class="form-select" id="type_partenaire" required>
            <option value="institutionnel" <?= ($partenaire['type_partenaire'] ?? '') == 'institutionnel' ? 'selected' : '' ?>>Institutionnel</option>
            <option value="prive" <?= ($partenaire['type_partenaire'] ?? '') == 'prive' ? 'selected' : '' ?>>Privé</option>
            <option value="ong" <?= ($partenaire['type_partenaire'] ?? '') == 'ong' ? 'selected' : '' ?>>ONG</option>
            <option value="gouvernemental" <?= ($partenaire['type_partenaire'] ?? '') == 'gouvernemental' ? 'selected' : '' ?>>Gouvernemental</option>
            <option value="technique" <?= ($partenaire['type_partenaire'] ?? '') == 'technique' ? 'selected' : '' ?>>Technique</option>
          </select>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Niveau de partenariat</label>
          <select class="form-select" id="niveau_partenariat">
            <option value="stratégique" <?= ($partenaire['niveau_partenariat'] ?? '') == 'stratégique' ? 'selected' : '' ?>>Stratégique</option>
            <option value="commercial" <?= ($partenaire['niveau_partenariat'] ?? '') == 'commercial' ? 'selected' : '' ?>>Commercial</option>
            <option value="technique" <?= ($partenaire['niveau_partenariat'] ?? '') == 'technique' ? 'selected' : '' ?>>Technique</option>
            <option value="financier" <?= ($partenaire['niveau_partenariat'] ?? '') == 'financier' ? 'selected' : '' ?>>Financier</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Pays</label>
          <input type="text" class="form-control" id="pays" value="<?= htmlspecialchars($partenaire['pays'] ?? '') ?>">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" id="description" rows="3"><?= htmlspecialchars($partenaire['description'] ?? '') ?></textarea>
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Logo URL</label>
          <input type="text" class="form-control" id="logo_url" value="<?= htmlspecialchars($partenaire['logo_url'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Site web</label>
          <input type="url" class="form-control" id="site_web" value="<?= htmlspecialchars($partenaire['site_web'] ?? '') ?>">
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Date de début</label>
          <input type="date" class="form-control" id="date_debut" value="<?= $partenaire['date_debut'] ?? '' ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Statut</label>
          <select class="form-select" id="est_actif">
            <option value="1" <?= ($partenaire['est_actif'] ?? 1) == 1 ? 'selected' : '' ?>>Actif</option>
            <option value="0" <?= ($partenaire['est_actif'] ?? 0) == 0 ? 'selected' : '' ?>>Inactif</option>
          </select>
        </div>
      </div>
      <div class="text-end mt-4">
        <a href="<?= base_url('Partenaires') ?>" class="btn btn-secondary me-2">Annuler</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Enregistrer</button>
      </div>
    </form>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
document.getElementById('partnerForm').onsubmit = async function(e) {
  e.preventDefault();
  const id = document.getElementById('id').value;
  const data = {
    nom: document.getElementById('nom').value,
    type_partenaire: document.getElementById('type_partenaire').value,
    niveau_partenariat: document.getElementById('niveau_partenariat').value,
    pays: document.getElementById('pays').value,
    description: document.getElementById('description').value,
    logo_url: document.getElementById('logo_url').value,
    site_web: document.getElementById('site_web').value,
    date_debut: document.getElementById('date_debut').value,
    est_actif: parseInt(document.getElementById('est_actif').value)
  };
  const res = id ? await API.post('Partenaires/api_update/'+id, data) : await API.post('Partenaires/api_create', data);
  if (res.success) { API.simpleAlert('success','Succès',res.message); setTimeout(()=>location.href=BASE_URL+'Partenaires',1200); }
  else API.simpleAlert('error','Erreur',res.message);
};
</script>
