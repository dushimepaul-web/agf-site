<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Sessions utilisateurs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Sessions</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Sessions <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <select id="filterActive" class="form-select form-select-sm" style="width:160px;" onchange="applyFilter()">
                <option value="">Toutes</option>
                <option value="1">Actives</option>
                <option value="0">Terminées</option>
              </select>
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher...">
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
                  <th scope="col">Utilisateur</th>
                  <th scope="col">IP</th>
                  <th scope="col">Navigateur</th>
                  <th scope="col">Connexion</th>
                  <th scope="col">Dernière activité</th>
                  <th scope="col">Statut</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune session trouvée.
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

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/localisation-table.js"></script>
<script>
let allRows = [];
let filteredRows = [];
let gt = null;

function renderRow(r, num) {
  const badge = r.is_active == 1
    ? '<span class="badge bg-success">Active</span>'
    : '<span class="badge bg-secondary">Terminée</span>';
  const user = API.esc((r.prenom || '') + ' ' + (r.nom || '') + ' (' + (r.username || '') + ')');
  const login = r.login_time ? new Date(r.login_time).toLocaleString('fr-FR') : '—';
  const activity = r.last_activity ? new Date(r.last_activity).toLocaleString('fr-FR') : '—';
  const actions = r.is_active == 1
    ? '<button type="button" class="btn btn-sm btn-outline-danger" title="Terminer" onclick="terminate(' + r.id + ')"><i class="bi bi-stop-circle"></i></button>'
    : '<span class="text-muted">—</span>';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td class="fw-semibold">' + user + '</td>' +
    '<td><code>' + API.esc(r.ip_address || '—') + '</code></td>' +
    '<td class="text-muted small">' + API.esc(r.browser || '—') + '</td>' +
    '<td class="text-muted small">' + login + '</td>' +
    '<td class="text-muted small">' + activity + '</td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-end">' + actions + '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.username, r.nom, r.prenom, r.ip_address, r.browser, r.platform].join(' ');
}

async function loadTable() {
  const res = await API.get('Sessions/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  allRows = res.data || [];
  applyFilter();
}

function applyFilter() {
  const active = document.getElementById('filterActive').value;
  filteredRows = active !== '' ? allRows.filter(r => r.is_active == active) : allRows;
  gt.setRows(filteredRows);
}

async function terminate(id) {
  const c = await API.confirm('Terminer', 'Forcer la déconnexion de cette session ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Sessions/api_terminate/' + id, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' sessions' : ' session'); }
});

loadTable();
</script>
