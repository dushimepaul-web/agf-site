<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des consultations</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Consultation</li>
        <li class="breadcrumb-item active">Consultations</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <!-- Stats rapides -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6">
        <div class="card info-card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-muted">En attente</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
              <div class="ps-3"><span id="statAttente" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card info-card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-muted">En cours</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info"><i class="bi bi-chat-dots"></i></div>
              <div class="ps-3"><span id="statCours" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card info-card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-muted">Terminées</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
              <div class="ps-3"><span id="statTerminees" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card info-card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-muted">Total</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"><i class="bi bi-clipboard-data"></i></div>
              <div class="ps-3"><span id="statTotal" class="fs-4 fw-bold">0</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des consultations <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <a href="<?= base_url('Consultations/medecins') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-people me-1"></i>Gérer les experts</a>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <select id="filterStatut" class="form-select form-select-sm" style="width:180px;" onchange="loadTable()">
                <option value="">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="en_cours">En cours</option>
                <option value="terminee">Terminée</option>
                <option value="annulee">Annulée</option>
              </select>
              <select id="filterPaiement" class="form-select form-select-sm" style="width:180px;" onchange="loadTable()">
                <option value="">Tous les paiements</option>
                <option value="en_attente">Paiement en attente</option>
                <option value="paye">Payé</option>
                <option value="rembourse">Remboursé</option>
                <option value="annule">Annulé</option>
              </select>
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher patient, expert, n°...">
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
                  <th scope="col">N° Consultation</th>
                  <th scope="col">Patient</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Pays</th>
                  <th scope="col">Expert</th>
                  <th scope="col">Date</th>
                  <th scope="col">Statut</th>
                  <th scope="col">Paiement</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune consultation trouvée.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span>
              <span class="text-muted">Chargement des consultations...</span>
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

<!-- Modal Détail -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailTitle">Détail de la consultation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body" id="detailBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Édition -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier la consultation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return saveEdit(event)">
        <div class="modal-body">
          <input type="hidden" name="uuid" id="editUuid">
          <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" id="editStatut" class="form-select">
              <option value="en_attente">En attente</option>
              <option value="en_cours">En cours</option>
              <option value="terminee">Terminée</option>
              <option value="annulee">Annulée</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Diagnostic</label>
            <textarea name="diagnostic" id="editDiagnostic" class="form-control" rows="3" placeholder="Diagnostic du médecin..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Traitement</label>
            <textarea name="traitement" id="editTraitement" class="form-control" rows="3" placeholder="Traitement prescrit..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Ordonnances</label>
            <textarea name="ordonnances" id="editOrdonnances" class="form-control" rows="2" placeholder="Détails de l'ordonnance..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Notes médecin</label>
            <textarea name="notes_medecin" id="editNotes" class="form-control" rows="2" placeholder="Notes internes..."></textarea>
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
let allRows = [];
let filteredRows = [];
let gt = null;

const statutBadge = {
  en_attente: '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>En attente</span>',
  en_cours: '<span class="badge bg-info"><i class="bi bi-chat-dots me-1"></i>En cours</span>',
  terminee: '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terminée</span>',
  annulee: '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulée</span>'
};

const paiementBadge = {
  en_attente: '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass me-1"></i>En attente</span>',
  paye: '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Payé</span>',
  rembourse: '<span class="badge bg-info"><i class="bi bi-arrow-return-left me-1"></i>Remboursé</span>',
  annule: '<span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Annulé</span>'
};

function renderRow(r, num) {
  const patient = API.esc((r.patient_prenom || '') + ' ' + (r.patient_nom || ''));
  const numCons = r.numero_consultation ? '<code class="text-primary">' + API.esc(r.numero_consultation) + '</code>' : '<span class="text-muted">—</span>';
  const date = r.created_at ? new Date(r.created_at).toLocaleDateString('fr-FR') : '—';
  const time = r.created_at ? new Date(r.created_at).toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'}) : '';

  const phone = r.whatsapppatient || '';
  const contactHtml = phone
    ? '<a href="https://wa.me/' + API.esc(phone.replace(/[^0-9]/g, '')) + '" target="_blank" class="text-success text-decoration-none" title="Contacter via WhatsApp"><i class="bi bi-whatsapp me-1"></i>' + API.esc(phone) + '</a>'
    : '<span class="text-muted">—</span>';

  const pays = r.country_id ? '<span class="badge bg-light text-dark border">#' + r.country_id + '</span>' : '<span class="text-muted">—</span>';

  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td>' + numCons + '</td>' +
    '<td>' +
      '<div class="fw-semibold">' + patient + '</div>' +
      (r.age ? '<small class="text-muted">' + r.age + ' ans</small>' : '') +
    '</td>' +
    '<td>' + contactHtml + '</td>' +
    '<td>' + pays + '</td>' +
    '<td>' + API.esc(r.medecin_nom || '—') + '</td>' +
    '<td><div class="text-muted small">' + date + '</div><div class="text-muted small">' + time + '</div></td>' +
    '<td>' + (statutBadge[r.statut] || r.statut) + '</td>' +
    '<td>' + (paiementBadge[r.paiement_statut] || '<span class="text-muted">' + API.esc(r.paiement_statut || '—') + '</span>') + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-info me-1" title="Voir détails" onclick="showDetail(\'' + r.uuid + '\')"><i class="bi bi-eye"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier" onclick="openEdit(\'' + r.uuid + '\')"><i class="bi bi-pencil"></i></button>' +
      (phone ? '<a href="https://wa.me/' + API.esc(phone.replace(/[^0-9]/g, '')) + '" target="_blank" class="btn btn-sm btn-outline-success me-1" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>' : '') +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(\'' + r.uuid + '\')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.patient_nom, r.patient_prenom, r.medecin_nom, r.numero_consultation, r.whatsapppatient, r.description_symptomes, r.statut].join(' ');
}

function updateStats() {
  const attente = allRows.filter(r => r.statut === 'en_attente').length;
  const cours = allRows.filter(r => r.statut === 'en_cours').length;
  const terminees = allRows.filter(r => r.statut === 'terminee').length;
  document.getElementById('statAttente').textContent = attente;
  document.getElementById('statCours').textContent = cours;
  document.getElementById('statTerminees').textContent = terminees;
  document.getElementById('statTotal').textContent = allRows.length;
}

async function loadTable() {
  const res = await API.get('Consultations/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  allRows = res.data || [];
  updateStats();
  applyFilter();
}

function applyFilter() {
  const statut = document.getElementById('filterStatut').value;
  const paiement = document.getElementById('filterPaiement').value;
  filteredRows = allRows;
  if (statut) filteredRows = filteredRows.filter(r => r.statut === statut);
  if (paiement) filteredRows = filteredRows.filter(r => r.paiement_statut === paiement);
  gt.setRows(filteredRows);
}

async function showDetail(uuid) {
  const res = await API.get('Consultations/api_get/' + uuid);
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  const r = res.data;
  const medias = (r.medias || []).map(m =>
    '<a href="<?= base_url() ?>' + API.esc(m.fichier_url) + '" target="_blank" class="me-2 mb-2 d-inline-block">' +
    '<img src="<?= base_url() ?>' + API.esc(m.fichier_url) + '" style="height:80px;border-radius:6px;border:1px solid #dee2e6;">' +
    '</a>'
  ).join('');

  const phone = r.whatsapppatient || '';

  document.getElementById('detailTitle').textContent = 'Consultation ' + (r.numero_consultation || '');
  document.getElementById('detailBody').innerHTML =
    '<div class="row g-4">' +
      '<div class="col-md-6 col-lg-4">' +
        '<div class="border rounded p-3 h-100">' +
          '<h6 class="text-primary mb-3"><i class="bi bi-person me-2"></i>Patient</h6>' +
          '<div class="mb-2"><strong>Nom :</strong> ' + API.esc((r.patient_prenom || '') + ' ' + (r.patient_nom || '')) + '</div>' +
          (r.age ? '<div class="mb-2"><strong>Âge :</strong> ' + r.age + ' ans</div>' : '') +
          (r.patient_poids || r.poids ? '<div class="mb-2"><strong>Poids :</strong> ' + API.esc(r.patient_poids || r.poids) + ' kg</div>' : '') +
          (r.patient_taille || r.taille ? '<div class="mb-2"><strong>Taille :</strong> ' + API.esc(r.patient_taille || r.taille) + ' cm</div>' : '') +
          (r.patient_adresse ? '<div class="mb-2"><strong>Adresse :</strong> ' + API.esc(r.patient_adresse) + '</div>' : '') +
          (phone ? '<div class="mb-2"><strong>WhatsApp :</strong> <a href="https://wa.me/' + API.esc(phone.replace(/[^0-9]/g, '')) + '" target="_blank" class="text-success"><i class="bi bi-whatsapp me-1"></i>' + API.esc(phone) + '</a></div>' : '') +
        '</div>' +
      '</div>' +
      '<div class="col-md-6 col-lg-4">' +
        '<div class="border rounded p-3 h-100">' +
          '<h6 class="text-primary mb-3"><i class="bi bi-heart-pulse me-2"></i>Consultation</h6>' +
          '<div class="mb-2"><strong>Expert :</strong> ' + API.esc(r.medecin_nom || '—') + '</div>' +
          '<div class="mb-2"><strong>Spécialité :</strong> ' + API.esc(r.specialite || '—') + '</div>' +
          '<div class="mb-2"><strong>Statut :</strong> ' + (statutBadge[r.statut] || r.statut) + '</div>' +
          '<div class="mb-2"><strong>Date :</strong> ' + (r.created_at ? new Date(r.created_at).toLocaleString('fr-FR') : '—') + '</div>' +
          '<div class="mb-2"><strong>Type :</strong> ' + API.esc(r.type || 'whatsapp') + '</div>' +
          (r.consultation_precedente === 'yes' ? '<div class="mb-2"><strong>Consultation précédente :</strong> Oui</div>' : '') +
        '</div>' +
      '</div>' +
      '<div class="col-md-6 col-lg-4">' +
        '<div class="border rounded p-3 h-100">' +
          '<h6 class="text-primary mb-3"><i class="bi bi-cash-stack me-2"></i>Paiement</h6>' +
          '<div class="mb-2"><strong>Statut :</strong> ' + (paiementBadge[r.paiement_statut] || r.paiement_statut || '—') + '</div>' +
          '<div class="mb-2"><strong>Honoraires :</strong> ' + API.esc(r.honoraires_consultation || '0') + ' ' + API.esc(r.devise || 'USD') + '</div>' +
          (r.prix_ht ? '<div class="mb-2"><strong>Prix HT :</strong> ' + r.prix_ht + '</div>' : '') +
          (r.tva ? '<div class="mb-2"><strong>TVA :</strong> ' + r.tva + '</div>' : '') +
          (r.mode_paiement ? '<div class="mb-2"><strong>Mode :</strong> ' + API.esc(r.mode_paiement) + '</div>' : '') +
        '</div>' +
      '</div>' +
      '<div class="col-12">' +
        '<div class="border rounded p-3">' +
          '<h6 class="text-primary mb-3"><i class="bi bi-clipboard-pulse me-2"></i>Symptômes</h6>' +
          '<p class="mb-1">' + API.esc(r.description_symptomes || '') + '</p>' +
          (r.duree_symptomes ? '<small class="text-muted"><strong>Durée :</strong> ' + API.esc(r.duree_symptomes) + '</small>' : '') +
        '</div>' +
      '</div>' +
      (r.diagnostic ? '<div class="col-md-6"><div class="border rounded p-3 h-100"><h6 class="text-success mb-2"><i class="bi bi-check2-square me-2"></i>Diagnostic</h6><p class="mb-0">' + API.esc(r.diagnostic) + '</p></div></div>' : '') +
      (r.traitement ? '<div class="col-md-6"><div class="border rounded p-3 h-100"><h6 class="text-info mb-2"><i class="bi bi-capsule me-2"></i>Traitement</h6><p class="mb-0">' + API.esc(r.traitement) + '</p></div></div>' : '') +
      (r.ordonnances ? '<div class="col-md-6"><div class="border rounded p-3 h-100"><h6 class="text-warning mb-2"><i class="bi bi-file-medical me-2"></i>Ordonnances</h6><p class="mb-0">' + API.esc(r.ordonnances) + '</p></div></div>' : '') +
      (r.notes_medecin ? '<div class="col-md-6"><div class="border rounded p-3 h-100"><h6 class="text-secondary mb-2"><i class="bi bi-journal-text me-2"></i>Notes médecin</h6><p class="mb-0">' + API.esc(r.notes_medecin) + '</p></div></div>' : '') +
      (medias ? '<div class="col-12"><h6 class="text-primary mb-2"><i class="bi bi-images me-2"></i>Médias joints</h6>' + medias + '</div>' : '') +
    '</div>';
  bootstrap.Modal.getOrCreateInstance(document.getElementById('detailModal')).show();
}

function openEdit(uuid) {
  const r = allRows.find(x => x.uuid === uuid);
  if (!r) return;
  document.getElementById('editUuid').value = uuid;
  document.getElementById('editStatut').value = r.statut || 'en_attente';
  document.getElementById('editDiagnostic').value = r.diagnostic || '';
  document.getElementById('editTraitement').value = r.traitement || '';
  document.getElementById('editOrdonnances').value = r.ordonnances || '';
  document.getElementById('editNotes').value = r.notes_medecin || '';
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
}

async function saveEdit(e) {
  e.preventDefault();
  const uuid = document.getElementById('editUuid').value;
  const data = {
    statut: document.getElementById('editStatut').value,
    diagnostic: document.getElementById('editDiagnostic').value,
    traitement: document.getElementById('editTraitement').value,
    ordonnances: document.getElementById('editOrdonnances').value,
    notes_medecin: document.getElementById('editNotes').value
  };
  const res = await API.post('Consultations/api_update/' + uuid, data);
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
  const c = await API.confirm('Supprimer', 'Supprimer cette consultation ? Cette action est irréversible.');
  if (!c.isConfirmed) return;
  const res = await API.post('Consultations/api_delete/' + uuid, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' consultations' : ' consultation'); }
});

loadTable();
</script>
