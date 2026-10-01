<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des patients</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Consultation</li>
        <li class="breadcrumb-item active">Patients</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Patients <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <a href="<?= base_url('Consultations') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour aux consultations</a>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un patient...">
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
                  <th scope="col">Nom complet</th>
                  <th scope="col">WhatsApp</th>
                  <th scope="col">Âge</th>
                  <th scope="col">Consultations</th>
                  <th scope="col">Dernière visite</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun patient trouvé.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
              <span class="text-muted">Chargement des patients...</span>
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

<!-- Modal historique patient -->
<div class="modal fade" id="historyModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyTitle">Historique du patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body" id="historyBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/localisation-table.js"></script>
<script>
let rows = [];
let gt = null;

const statutBadge = {
  en_attente: '<span class="badge bg-warning text-dark">En attente</span>',
  en_cours: '<span class="badge bg-info">En cours</span>',
  terminee: '<span class="badge bg-success">Terminée</span>',
  annulee: '<span class="badge bg-secondary">Annulée</span>'
};

function renderRow(r, num) {
  const fullname = API.esc((r.patient_prenom || '') + ' ' + (r.patient_nom || ''));
  const phone = r.whatsapppatient || '';
  const phoneHtml = phone
    ? '<a href="https://wa.me/' + API.esc(phone.replace(/[^0-9]/g, '')) + '" target="_blank" class="text-success text-decoration-none"><i class="bi bi-whatsapp me-1"></i>' + API.esc(phone) + '</a>'
    : '<span class="text-muted">—</span>';
  const age = r.age ? r.age + ' ans' : '—';
  const nb = '<span class="badge bg-primary">' + r.nb_consultations + '</span>';
  const lastDate = r.derniere_consultation ? new Date(r.derniere_consultation).toLocaleDateString('fr-FR') : '—';

  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td class="fw-semibold">' + fullname + '</td>' +
    '<td>' + phoneHtml + '</td>' +
    '<td>' + age + '</td>' +
    '<td class="text-center">' + nb + '</td>' +
    '<td class="text-muted small">' + lastDate + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-info me-1" title="Voir historique" onclick="showHistory(\'' + API.esc(r.patient_nom) + '\',\'' + API.esc(r.patient_prenom) + '\',\'' + API.esc(phone) + '\')"><i class="bi bi-clock-history"></i></button>' +
      (phone ? '<a href="https://wa.me/' + API.esc(phone.replace(/[^0-9]/g, '')) + '" target="_blank" class="btn btn-sm btn-outline-success" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>' : '') +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.patient_nom, r.patient_prenom, r.whatsapppatient, r.nb_consultations].join(' ');
}

async function loadTable() {
  const res = await API.get('Patients/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

async function showHistory(nom, prenom, whatsapp) {
  document.getElementById('historyTitle').textContent = 'Historique — ' + prenom + ' ' + nom;
  document.getElementById('historyBody').innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2"></span>Chargement...</div>';
  bootstrap.Modal.getOrCreateInstance(document.getElementById('historyModal')).show();

  const res = await API.get('Patients/api_detail?nom=' + encodeURIComponent(nom) + '&prenom=' + encodeURIComponent(prenom) + '&whatsapp=' + encodeURIComponent(whatsapp));
  if (!res.success) { document.getElementById('historyBody').innerHTML = '<p class="text-danger">' + res.message + '</p>'; return; }

  const consultations = res.data || [];
  if (consultations.length === 0) {
    document.getElementById('historyBody').innerHTML = '<p class="text-muted text-center py-3">Aucune consultation trouvée.</p>';
    return;
  }

  let html = '<div class="table-responsive"><table class="table table-sm align-middle"><thead class="table-light"><tr>' +
    '<th>Date</th><th>N° Consultation</th><th>Expert</th><th>Symptômes</th><th>Statut</th><th>Paiement</th>' +
    '</tr></thead><tbody>';

  consultations.forEach(c => {
    const date = c.created_at ? new Date(c.created_at).toLocaleString('fr-FR') : '—';
    const num = c.numero_consultation ? '<code>' + API.esc(c.numero_consultation) + '</code>' : '—';
    const symptoms = c.description_symptomes ? API.esc(c.description_symptomes.substring(0, 80)) + (c.description_symptomes.length > 80 ? '...' : '') : '—';
    html += '<tr>' +
      '<td class="small">' + date + '</td>' +
      '<td>' + num + '</td>' +
      '<td>' + API.esc(c.medecin_nom || '—') + '</td>' +
      '<td class="small">' + symptoms + '</td>' +
      '<td>' + (statutBadge[c.statut] || c.statut) + '</td>' +
      '<td>' + API.esc(c.paiement_statut || '—') + '</td>' +
    '</tr>';
  });

  html += '</tbody></table></div>';
  document.getElementById('historyBody').innerHTML = html;
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' patients' : ' patient'); }
});

loadTable();
</script>
