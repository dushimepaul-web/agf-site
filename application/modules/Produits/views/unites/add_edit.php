<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1><?= $unite ? 'Modifier l\'unité' : 'Ajouter une unité' ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Unites') ?>">Unités d'affaires</a></li>
        <li class="breadcrumb-item active"><?= $unite ? 'Modifier' : 'Ajouter' ?></li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <form id="editForm" onsubmit="return save(event)">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Code <span class="text-danger">*</span></label>
                  <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($unite['code'] ?? '') ?>" required>
                </div>
                <div class="col-md-8 mb-3">
                  <label class="form-label">Nom <span class="text-danger">*</span></label>
                  <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($unite['nom'] ?? '') ?>" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($unite['slug'] ?? '') ?>" placeholder="Généré automatiquement si vide">
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($unite['description'] ?? '') ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Slogan</label>
                <input type="text" name="slogan" class="form-control" value="<?= htmlspecialchars($unite['slogan'] ?? '') ?>">
              </div>
              <div class="row">
                <div class="col-md-8 mb-3">
                  <label class="form-label">Logo</label>
                  <input type="file" name="file" class="form-control" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                  <input type="hidden" name="logo" id="logoPath" value="<?= htmlspecialchars($unite['logo'] ?? '') ?>">
                  <div id="logoPreview" class="mt-2">
                    <?php if (!empty($unite['logo'])): ?>
                    <img src="<?= base_url($unite['logo']) ?>" style="max-height:80px;border-radius:6px;">
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Ordre</label>
                  <input type="number" name="ordre" class="form-control" value="<?= (int)($unite['ordre'] ?? 0) ?>" min="0">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Statut</label>
                <select name="est_actif" class="form-select">
                  <option value="1" <?= ($unite['est_actif'] ?? 1) == 1 ? 'selected' : '' ?>>Actif</option>
                  <option value="0" <?= ($unite['est_actif'] ?? 1) == 0 ? 'selected' : '' ?>>Inactif</option>
                </select>
              </div>
              <div class="d-flex gap-2">
                <a href="<?= base_url('Unites') ?>" class="btn btn-secondary">Retour</a>
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
function previewImage(input, previewId) {
  const preview = document.getElementById(previewId);
  preview.innerHTML = '';
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:80px;border-radius:6px;">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

async function uploadFile(file) {
  const fd = new FormData();
  fd.append('file', file);
  fd.append(CSRF_TOKEN_NAME, CSRF_TOKEN);
  const res = await fetch(BASE_URL + 'Unites/api_upload', {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: fd
  });
  return await res.json();
}

async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const fileInput = f.querySelector('input[name="file"]');

  if (fileInput.files && fileInput.files[0]) {
    const uploadRes = await uploadFile(fileInput.files[0]);
    if (!uploadRes.success) {
      API.simpleAlert('error', 'Erreur upload', uploadRes.message);
      return false;
    }
    document.getElementById('logoPath').value = uploadRes.data.path;
  }

  const data = {
    code: f.code.value, nom: f.nom.value, slug: f.slug.value,
    description: f.description.value, slogan: f.slogan.value,
    logo: document.getElementById('logoPath').value,
    ordre: parseInt(f.ordre.value) || 0,
    est_actif: parseInt(f.est_actif.value) || 0
  };
  const id = <?= json_encode($unite['id'] ?? null) ?>;
  const url = id ? 'Unites/api_update/' + id : 'Unites/api_create';
  const res = await API.post(url, data);
  if (res.success) {
    API.simpleAlert('success', 'Succès', res.message).then(() => {
      window.location.href = '<?= base_url('Unites') ?>';
    });
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}
</script>
