<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des témoignages</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Contenu</li>
        <li class="breadcrumb-item active">Témoignages</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des témoignages <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal()"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un témoignage...">
              </div>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted small">
              <span>Lignes :</span>
              <select id="tblPerPage" class="form-select form-select-sm" style="width:90px;">
                <option value="10">10</option>
                <option value="15" selected>15</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="dataTable">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Miniature</th>
                  <th scope="col">Titre</th>
                  <th scope="col">Auteur</th>
                  <th scope="col">Ordre</th>
                  <th scope="col">Statut</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun témoignage trouvé.
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
        <h5 class="modal-title" id="modalTitle">Ajouter un témoignage</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return save(event)">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Titre <span class="text-danger">*</span></label>
              <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Ordre</label>
              <input type="number" name="ordre" class="form-control" value="0" min="0">
            </div>
            <div class="col-md-6">
              <label class="form-label">Auteur</label>
              <input type="text" name="auteur" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Statut</label>
              <select name="est_actif" class="form-select">
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">URL vidéo</label>
              <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Miniature</label>
              <div class="input-group">
                <input type="text" name="miniature" class="form-control" readonly>
                <input type="file" id="fileMiniature" class="d-none" accept="image/*" onchange="uploadFile(this, 'miniature')">
                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileMiniature').click()"><i class="bi bi-upload"></i></button>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
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
  const img = r.miniature
    ? '<img src="<?= base_url() ?>' + API.esc(r.miniature) + '" alt="" style="height:32px;width:auto;border-radius:4px;">'
    : '<span class="text-muted"><i class="bi bi-image"></i></span>';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td>' + img + '</td>' +
    '<td class="fw-semibold">' + API.esc(r.titre) + '</td>' +
    '<td>' + API.esc(r.auteur || '—') + '</td>' +
    '<td>' + API.esc(r.ordre) + '</td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier" onclick="openModal(' + r.id + ')"><i class="bi bi-pencil"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(' + r.id + ')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.titre, r.auteur, r.description].join(' ');
}

async function loadTable() {
  const res = await API.get('Temoignages/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

function openModal(id) {
  document.getElementById('editForm').reset();
  document.getElementById('modalTitle').textContent = 'Ajouter un témoignage';
  document.getElementById('editForm').dataset.id = '';
  if (id) {
    const r = rows.find(x => x.id == id);
    if (r) {
      document.getElementById('modalTitle').textContent = 'Modifier le témoignage';
      document.getElementById('editForm').dataset.id = id;
      document.getElementById('editForm').titre.value = r.titre || '';
      document.getElementById('editForm').auteur.value = r.auteur || '';
      document.getElementById('editForm').video_url.value = r.video_url || '';
      document.getElementById('editForm').miniature.value = r.miniature || '';
      document.getElementById('editForm').description.value = r.description || '';
      document.getElementById('editForm').est_actif.value = r.est_actif || 1;
      document.getElementById('editForm').ordre.value = r.ordre || 0;
    }
  }
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
}

async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const data = {
    titre: f.titre.value, auteur: f.auteur.value, video_url: f.video_url.value,
    miniature: f.miniature.value, description: f.description.value,
    est_actif: f.est_actif.value, ordre: f.ordre.value
  };
  const id = f.dataset.id;
  const res = id ? await API.post('Temoignages/api_update/' + id, data) : await API.post('Temoignages/api_create', data);
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
  const c = await API.confirm('Supprimer', 'Supprimer ce témoignage ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Temoignages/api_delete/' + id, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

async function uploadFile(input, fieldName) {
  const file = input.files[0];
  if (!file) return;
  const fd = new FormData();
  fd.append('file', file);
  fd.append(API.csrf_field_name(), API.csrf_token);
  const res = await API.request('Temoignages/api_upload', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
  if (res.success) {
    document.getElementById('editForm')[fieldName].value = res.data.path;
    API.simpleAlert('success', 'Succès', res.message);
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  input.value = '';
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' témoignages' : ' témoignage'); }
});

loadTable();
</script>
