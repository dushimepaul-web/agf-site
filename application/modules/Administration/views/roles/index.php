<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des rôles</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Rôles</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des rôles <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal()"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un rôle..." aria-label="Rechercher">
              </div>
            </div>
            <div class="d-flex align-items-center gap-2 text-muted small">
              <span>Lignes :</span>
              <select id="tblPerPage" class="form-select form-select-sm" style="width:90px;" aria-label="Nombre de lignes par page">
                <option value="10">10</option>
                <option value="15" selected>15</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="dataTable">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Code</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Description</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun rôle trouvé.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
              <span class="text-muted">Chargement des rôles...</span>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Ajouter un rôle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return save(event)">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
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
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td><span class="badge bg-secondary">' + API.esc(r.code) + '</span></td>' +
    '<td class="fw-semibold">' + API.esc(r.nom) + '</td>' +
    '<td>' + API.esc(r.description || '—') + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier ce rôle" onclick="openModal(\'' + r.uuid + '\')"><i class="bi bi-pencil"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer ce rôle" onclick="remove(\'' + r.uuid + '\')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.code, r.nom, r.description].join(' ');
}

async function loadTable() {
  const res = await API.roles.list();
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

function openModal(id) {
  document.getElementById('editForm').reset();
  document.getElementById('modalTitle').textContent = 'Ajouter un rôle';
  document.getElementById('editForm').dataset.id = '';
  if (id) {
    const r = rows.find(x => x.uuid === id);
    if (r) {
      document.getElementById('modalTitle').textContent = 'Modifier le rôle';
      document.getElementById('editForm').dataset.id = id;
      document.getElementById('editForm').code.value = r.code || '';
      document.getElementById('editForm').nom.value = r.nom || '';
      document.getElementById('editForm').description.value = r.description || '';
    }
  }
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
}

async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const data = { code: f.code.value, nom: f.nom.value, description: f.description.value };
  const id = f.dataset.id;
  const res = id ? await API.roles.update(id, data) : await API.roles.create(data);
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
  const c = await API.confirm('Supprimer', 'Supprimer ce rôle ?');
  if (!c.isConfirmed) return;
  const res = await API.roles.remove(id);
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' rôles' : ' rôle'); }
});

loadTable();
</script>