<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1><?= $produit ? 'Modifier le produit' : 'Ajouter un produit' ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Produits') ?>">Produits</a></li>
        <li class="breadcrumb-item active"><?= $produit ? 'Modifier' : 'Ajouter' ?></li>
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
                <div class="col-md-8 mb-3">
                  <label class="form-label">Nom <span class="text-danger">*</span></label>
                  <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($produit['nom'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                  <select name="categorie_id" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($produit['categorie_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($produit['slug'] ?? '') ?>" placeholder="Généré automatiquement si vide">
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($produit['description'] ?? '') ?></textarea>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Image du produit</label>
                  <input type="file" name="file" class="form-control" accept="image/*" onchange="previewImage(this, 'imgPreview')">
                  <input type="hidden" name="image" id="imagePath" value="<?= htmlspecialchars($produit['image'] ?? '') ?>">
                  <div id="imgPreview" class="mt-2">
                    <?php if (!empty($produit['image'])): ?>
                    <img src="<?= base_url($produit['image']) ?>" style="max-height:120px;border-radius:6px;">
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Conditionnement</label>
                  <input type="text" name="conditionnement" class="form-control" value="<?= htmlspecialchars($produit['conditionnement'] ?? '') ?>" placeholder="Bouteille 250g">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Ordre</label>
                  <input type="number" name="ordre" class="form-control" value="<?= (int)($produit['ordre'] ?? 0) ?>" min="0">
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Certifié</label>
                  <select name="est_certifie" class="form-select">
                    <option value="0" <?= ($produit['est_certifie'] ?? 0) == 0 ? 'selected' : '' ?>>Non</option>
                    <option value="1" <?= ($produit['est_certifie'] ?? 0) == 1 ? 'selected' : '' ?>>Oui</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Statut</label>
                  <select name="est_actif" class="form-select">
                    <option value="1" <?= ($produit['est_actif'] ?? 1) == 1 ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= ($produit['est_actif'] ?? 1) == 0 ? 'selected' : '' ?>>Inactif</option>
                  </select>
                </div>
              </div>
              <div class="d-flex gap-2">
                <a href="<?= base_url('Produits') ?>" class="btn btn-secondary">Retour</a>
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
      preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:120px;border-radius:6px;">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

async function uploadFile(file) {
  const fd = new FormData();
  fd.append('file', file);
  fd.append(CSRF_TOKEN_NAME, CSRF_TOKEN);
  const res = await fetch(BASE_URL + 'Produits/api_upload', {
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
    document.getElementById('imagePath').value = uploadRes.data.path;
  }

  const data = {
    nom: f.nom.value, categorie_id: parseInt(f.categorie_id.value) || 0,
    slug: f.slug.value, description: f.description.value,
    image: document.getElementById('imagePath').value,
    conditionnement: f.conditionnement.value,
    ordre: parseInt(f.ordre.value) || 0,
    est_certifie: parseInt(f.est_certifie.value) || 0,
    est_actif: parseInt(f.est_actif.value) || 0
  };
  const id = <?= json_encode($produit['id'] ?? null) ?>;
  const url = id ? 'Produits/api_update/' + id : 'Produits/api_create';
  const res = await API.post(url, data);
  if (res.success) {
    API.simpleAlert('success', 'Succès', res.message).then(() => {
      window.location.href = '<?= base_url('Produits') ?>';
    });
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}
</script>
