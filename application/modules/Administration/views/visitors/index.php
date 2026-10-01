<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Journal des visiteurs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Visiteurs</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Aujourd'hui</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-calendar-day"></i></div>
              <div class="ps-3"><span id="statToday" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Cette semaine</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-calendar-week"></i></div>
              <div class="ps-3"><span id="statWeek" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Ce mois</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-calendar-month"></i></div>
              <div class="ps-3"><span id="statMonth" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Visites <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
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
                  <th scope="col">Page</th>
                  <th scope="col">IP</th>
                  <th scope="col">Date</th>
                  <th scope="col">Heure</th>
                  <th scope="col">Device</th>
                  <th scope="col">Référent</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune visite trouvée.
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
let rows = [];
let gt = null;

function renderRow(r, num) {
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td class="fw-semibold text-truncate" style="max-width:300px;" title="' + API.esc(r.page || '') + '">' + API.esc(r.page || '—') + '</td>' +
    '<td><code>' + API.esc(r.ip_address || '—') + '</code></td>' +
    '<td class="text-muted small">' + API.esc(r.visit_date || '—') + '</td>' +
    '<td class="text-muted small">' + API.esc(r.visit_time || '—') + '</td>' +
    '<td><span class="badge bg-light text-dark border">' + API.esc(r.device || '—') + '</span></td>' +
    '<td class="text-muted small text-truncate" style="max-width:200px;" title="' + API.esc(r.referer || '') + '">' + API.esc(r.referer || '—') + '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.page, r.ip_address, r.device, r.referer, r.visit_date].join(' ');
}

async function loadStats() {
  const res = await API.get('Visitors/api_stats');
  if (res.success && res.data) {
    document.getElementById('statToday').textContent = res.data.today;
    document.getElementById('statWeek').textContent = res.data.week;
    document.getElementById('statMonth').textContent = res.data.month;
  }
}

async function loadTable() {
  const res = await API.get('Visitors/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' visites' : ' visite'); }
});

loadStats();
loadTable();
</script>
