<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1><?= $categorie ? 'Modifier la catégorie' : 'Ajouter une catégorie' ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Produits') ?>">Produits</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Categories') ?>">Catégories</a></li>
        <li class="breadcrumb-item active"><?= $categorie ? 'Modifier' : 'Ajouter' ?></li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <form id="editForm" onsubmit="return save(event)">
              <div class="mb-3">
                <label class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($categorie['nom'] ?? '') ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($categorie['slug'] ?? '') ?>" placeholder="Généré automatiquement si vide">
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($categorie['description'] ?? '') ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Ordre</label>
                  <input type="number" name="ordre" class="form-control" value="<?= (int)($categorie['ordre'] ?? 0) ?>" min="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Statut</label>
                  <select name="est_actif" class="form-select">
                    <option value="1" <?= ($categorie['est_actif'] ?? 1) == 1 ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= ($categorie['est_actif'] ?? 1) == 0 ? 'selected' : '' ?>>Inactif</option>
                  </select>
                </div>
              </div>
              <div class="d-flex gap-2">
                <a href="<?= base_url('Categories') ?>" class="btn btn-secondary">Retour</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const data = {
    nom: f.nom.value, slug: f.slug.value, description: f.description.value,
    ordre: parseInt(f.ordre.value) || 0, est_actif: parseInt(f.est_actif.value) || 0
  };
  const id = <?= json_encode($categorie['id'] ?? null) ?>;
  const url = id ? 'Categories/api_update/' + id : 'Categories/api_create';
  const res = await API.post(url, data);
  if (res.success) {
    API.simpleAlert('success', 'Succès', res.message).then(() => {
      window.location.href = '<?= base_url('Categories') ?>';
    });
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}
</script>
