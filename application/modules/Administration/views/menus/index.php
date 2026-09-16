<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des menus</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Menus</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des menus <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal()"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un menu..." aria-label="Rechercher">
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
                  <th scope="col">Libellé</th>
                  <th scope="col" class="d-none d-md-table-cell">Route</th>
                  <th scope="col" class="text-center">Ordre</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun menu trouvé.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
              <span class="text-muted">Chargement des menus...</span>
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
        <h5 class="modal-title" id="modalTitle">Ajouter un menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return save(event)">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Libellé <span class="text-danger">*</span></label>
            <input type="text" name="libelle" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Route (URL)</label>
            <input type="text" name="route" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Icône</label>
            <input type="text" name="icon" class="form-control" placeholder="ex : bi bi-gear">
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label">Ordre</label>
              <input type="number" name="ordre" class="form-control" value="0">
            </div>
            <div class="col-6">
              <label class="form-label">Menu parent</label>
              <select name="parent_id" class="form-select">
                <option value="">Aucun (menu racine)</option>
                <?php foreach ($menus as $m): ?>
                  <option value="<?= $m['id_menu'] ?>"><?= htmlspecialchars($m['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
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
  const route = r.route || '';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td><span class="badge bg-secondary">' + API.esc(r.code) + '</span></td>' +
    '<td class="fw-semibold">' + API.esc(r.libelle || '—') + '</td>' +
    '<td class="d-none d-md-table-cell text-muted font-monospace">' + (route ? API.esc(route) : '—') + '</td>' +
    '<td class="text-center"><span class="badge bg-light border text-dark">' + (r.ordre ?? 0) + '</span></td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier ce menu" onclick="openModal(\'' + r.uuid + '\')"><i class="bi bi-pencil"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer ce menu" onclick="remove(\'' + r.uuid + '\')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.code, r.libelle, r.route].join(' ');
}

async function loadTable() {
  const res = await API.menus.list();
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

function openModal(uuid) {
  document.getElementById('editForm').reset();
  document.getElementById('modalTitle').textContent = 'Ajouter un menu';
  document.getElementById('editForm').dataset.id = '';
  if (uuid) {
    const r = rows.find(x => x.uuid === uuid);
    if (r) {
      document.getElementById('modalTitle').textContent = 'Modifier le menu';
      document.getElementById('editForm').dataset.id = uuid;
      document.getElementById('editForm').code.value = r.code || '';
      document.getElementById('editForm').libelle.value = r.libelle || '';
      document.getElementById('editForm').route.value = r.route || '';
      document.getElementById('editForm').icon.value = r.icon || '';
      document.getElementById('editForm').ordre.value = r.ordre ?? 0;
      document.getElementById('editForm').parent_id.value = r.parent_id || '';
    }
  }
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
}

async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const data = {
    code: f.code.value,
    libelle: f.libelle.value,
    route: f.route.value,
    icon: f.icon.value,
    ordre: f.ordre.value,
    parent_id: f.parent_id.value
  };
  const id = f.dataset.id;
  const res = id ? await API.menus.update(id, data) : await API.menus.create(data);
  if (res.success) {
    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    await loadTable();
    API.simpleAlert('success', 'Succès', res.message);
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}

async function remove(uuid) {
  const c = await API.confirm('Supprimer', 'Supprimer ce menu ?');
  if (!c.isConfirmed) return;
  const res = await API.menus.remove(uuid);
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' menus' : ' menu'); }
});

loadTable();
</script>