<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des experts</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Consultation</li>
        <li class="breadcrumb-item active">Experts</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des experts <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <div class="d-flex gap-2">
              <a href="<?= base_url('Consultations') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
              <a href="<?= base_url('Consultations/medecin_form') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un expert...">
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
                  <th scope="col">Photo</th>
                  <th scope="col">Nom complet</th>
                  <th scope="col">Spécialité</th>
                  <th scope="col">Licence</th>
                  <th scope="col">Exp.</th>
                  <th scope="col">Note</th>
                  <th scope="col">Statut</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun expert trouvé.
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
  const badge = r.actif == 1
    ? '<span class="badge bg-success">Actif</span>'
    : '<span class="badge bg-secondary">Inactif</span>';
  const img = r.photo
    ? '<img src="<?= base_url() ?>' + API.esc(r.photo) + '" alt="" style="height:32px;width:auto;border-radius:50%;">'
    : '<span class="text-muted"><i class="bi bi-person-circle fs-4"></i></span>';
  const nom = API.esc((r.prenom || '') + ' ' + (r.nom || ''));
  const note = r.note_moyenne ? parseFloat(r.note_moyenne).toFixed(1) + ' <i class="bi bi-star-fill text-warning" style="font-size:.7rem;"></i>' : '—';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td>' + img + '</td>' +
    '<td class="fw-semibold">' + nom + '</td>' +
    '<td><span class="badge bg-light text-dark border">' + API.esc(r.specialite) + '</span></td>' +
    '<td class="text-muted small">' + API.esc(r.numero_licence || '—') + '</td>' +
    '<td class="text-muted">' + (r.annees_experience || 0) + ' ans</td>' +
    '<td>' + note + '</td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<a href="<?= base_url('Consultations/medecin_form/') ?>' + r.uuid + '" class="btn btn-sm btn-outline-primary me-1" title="Modifier"><i class="bi bi-pencil"></i></a>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(\'' + r.uuid + '\')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.nom, r.prenom, r.specialite, r.numero_licence, r.bio, r.diplomes].join(' ');
}

async function loadTable() {
  const res = await API.get('Consultations/api_medecins_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

async function remove(uuid) {
  const c = await API.confirm('Supprimer', 'Supprimer cet expert ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Consultations/api_medecins_delete/' + uuid, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' experts' : ' expert'); }
});

loadTable();
</script>
