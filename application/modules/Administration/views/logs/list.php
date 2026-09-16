<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Journaux d'activité</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Logs</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Activité système <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearLogs()"><i class="bi bi-trash me-1"></i>Effacer</button>
          </div>
          <div class="my-3">
            <div class="position-relative" style="max-width:300px;">
              <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
              <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher...">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="dataTable">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Utilisateur</th>
                  <th>Action</th>
                  <th>Description</th>
                  <th>Niveau</th>
                  <th>IP</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun journal trouvé.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
              <span class="text-muted">Chargement...</span>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3" id="tableFooter">
              <span id="tableInfo" class="text-muted small"></span>
              <nav><ul class="pagination pagination-sm mb-0" id="tblPager"></ul></nav>
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
let rows = [];
let gt = null;

function niveauBadge(n) {
  if (n === 'error') return '<span class="badge bg-danger">Erreur</span>';
  if (n === 'warning') return '<span class="badge bg-warning text-dark">Alerte</span>';
  if (n === 'info') return '<span class="badge bg-info">Info</span>';
  return '<span class="badge bg-secondary">' + API.esc(n || '') + '</span>';
}

function renderRow(r, num) {
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td class="text-nowrap">' + API.esc(r.created_at || '') + '</td>' +
    '<td>' + (r.user_id || '—') + '</td>' +
    '<td><span class="badge bg-light text-dark border">' + API.esc(r.action || '') + '</span></td>' +
    '<td>' + API.esc((r.description || '').substring(0, 80)) + '</td>' +
    '<td>' + niveauBadge(r.niveau) + '</td>' +
    '<td><code>' + API.esc(r.ip_address || '') + '</code></td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.action, r.description, r.ip_address, r.url].join(' ');
}

async function loadTable() {
  const res = await API.get('Logs/api_list?limit=100');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data.data || [];
  gt.setRows(rows);
}

async function clearLogs() {
  const c = await API.confirm('Effacer', 'Supprimer tous les journaux ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Logs/api_clear', {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t) { return t + (t > 1 ? ' entrées' : ' entrée'); }
});

loadTable();
</script>
