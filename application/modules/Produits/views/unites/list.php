<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Unités d'affaires (SBUs)</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Unités d'affaires</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">5 Unités stratégiques <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal()"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher...">
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="dataTable">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Logo</th>
                  <th scope="col">Code</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Slogan</th>
                  <th scope="col">Statut</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune unité trouvée.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
              <span class="text-muted">Chargement...</span>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3" id="tableFooter">
              <span id="tableInfo" class="text-muted small"></span>
              <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0" id="tblPager"></ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Ajouter une unité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return save(event)">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Code <span class="text-danger">*</span></label>
              <input type="text" name="code" class="form-control" required placeholder="ABI_IND">
            </div>
            <div class="col-md-8 mb-3">
              <label class="form-label">Nom <span class="text-danger">*</span></label>
              <input type="text" name="nom" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" placeholder="Généré automatiquement si vide">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Slogan</label>
            <input type="text" name="slogan" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label">Logo</label>
              <input type="file" name="file" class="form-control" accept="image/*" onchange="previewImage(this, 'logoPreview')">
              <input type="hidden" name="logo" id="logoPath">
              <div id="logoPreview" class="mt-2"></div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Ordre</label>
              <input type="number" name="ordre" class="form-control" value="0" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="est_actif" class="form-select">
              <option value="1">Actif</option>
              <option value="0">Inactif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/localisation-table.js"></script>
<script>
let rows = [];
let gt = null;

function renderRow(r, num) {
  const badge = r.est_actif == 1
    ? '<span class="badge bg-success">Actif</span>'
    : '<span class="badge bg-secondary">Inactif</span>';
  const img = r.logo
    ? '<img src="<?= base_url() ?>' + API.esc(r.logo) + '" alt="" style="height:36px;width:auto;border-radius:4px;">'
    : '<span class="text-muted"><i class="bi bi-building"></i></span>';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td>' + img + '</td>' +
    '<td><span class="badge bg-primary">' + API.esc(r.code) + '</span></td>' +
    '<td class="fw-semibold">' + API.esc(r.nom) + '</td>' +
    '<td>' + API.esc(r.slogan || '—') + '</td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier" onclick="openModal(' + r.id + ')"><i class="bi bi-pencil"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(' + r.id + ')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.code, r.nom, r.slogan, r.description].join(' ');
}

async function loadTable() {
  const res = await API.get('Unites/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

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

function openModal(id) {
  document.getElementById('editForm').reset();
  document.getElementById('logoPreview').innerHTML = '';
  document.getElementById('logoPath').value = '';
  document.getElementById('modalTitle').textContent = 'Ajouter une unité';
  document.getElementById('editForm').dataset.id = '';
  if (id) {
    const r = rows.find(x => x.id == id);
    if (r) {
      document.getElementById('modalTitle').textContent = 'Modifier l\'unité';
      document.getElementById('editForm').dataset.id = id;
      document.getElementById('editForm').code.value = r.code || '';
      document.getElementById('editForm').nom.value = r.nom || '';
      document.getElementById('editForm').slug.value = r.slug || '';
      document.getElementById('editForm').description.value = r.description || '';
      document.getElementById('editForm').slogan.value = r.slogan || '';
      document.getElementById('logoPath').value = r.logo || '';
      document.getElementById('editForm').ordre.value = r.ordre || 0;
      document.getElementById('editForm').est_actif.value = r.est_actif || 1;
      if (r.logo) {
        document.getElementById('logoPreview').innerHTML = '<img src="<?= base_url() ?>' + API.esc(r.logo) + '" style="max-height:80px;border-radius:6px;">';
      }
    }
  }
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
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
  const id = f.dataset.id;
  const url = id ? 'Unites/api_update/' + id : 'Unites/api_create';
  const res = await API.post(url, data);
  if (res.success) {
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    await loadTable();
    API.simpleAlert('success', 'Succès', res.message);
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}

async function remove(id) {
  const c = await API.confirm('Supprimer', 'Supprimer cette unité ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Unites/api_delete/' + id, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' unités' : ' unité'); }
});

loadTable();
</script>
