<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des utilisateurs</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Utilisateurs</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Liste des utilisateurs <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal()"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un utilisateur..." aria-label="Rechercher">
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
                  <th scope="col">Nom complet</th>
                  <th scope="col">Username</th>
                  <th scope="col">Email</th>
                  <th scope="col">Rôle</th>
                  <th scope="col">Statut</th>
                  <th scope="col">Dernière connexion</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun utilisateur trouvé.
            </div>
            <div id="tableLoading" class="text-center py-4">
              <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
              <span class="text-muted">Chargement des utilisateurs...</span>
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
        <h5 class="modal-title" id="modalTitle">Ajouter un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="editForm" onsubmit="return save(event)">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nom <span class="text-danger">*</span></label>
              <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input type="text" name="prenom" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone</label>
              <input type="text" name="telephone" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Rôle <span class="text-danger">*</span></label>
              <select name="role_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mot de passe <span class="text-danger" id="pwdRequired">*</span></label>
              <input type="password" name="password" class="form-control" minlength="6">
              <small class="text-muted">Min. 6 caractères. Laisser vide pour conserver.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Statut</label>
              <select name="actif" class="form-select">
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
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
  const badge = r.actif == 1
    ? '<span class="badge bg-success">Actif</span>'
    : '<span class="badge bg-secondary">Inactif</span>';
  const fullname = API.esc((r.prenom || '') + ' ' + (r.nom || ''));
  const lastLogin = r.derniere_connexion ? new Date(r.derniere_connexion).toLocaleString('fr-FR') : '—';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td class="fw-semibold">' + fullname + '</td>' +
    '<td><span class="badge bg-secondary">' + API.esc(r.username) + '</span></td>' +
    '<td>' + API.esc(r.email) + '</td>' +
    '<td><span class="badge bg-light text-dark border">' + API.esc(r.role_nom || '') + '</span></td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-muted small">' + lastLogin + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<button type="button" class="btn btn-sm btn-outline-primary me-1" title="Modifier" onclick="openModal(\'' + r.uuid + '\')"><i class="bi bi-pencil"></i></button>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(\'' + r.uuid + '\')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.username, r.email, r.nom, r.prenom, r.role_nom].join(' ');
}

async function loadTable() {
  const res = await API.get('Users/api_list');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

function openModal(id) {
  document.getElementById('editForm').reset();
  document.getElementById('modalTitle').textContent = 'Ajouter un utilisateur';
  document.getElementById('editForm').dataset.id = '';
  document.getElementById('pwdRequired').textContent = '*';
  if (id) {
    const r = rows.find(x => x.uuid === id);
    if (r) {
      document.getElementById('modalTitle').textContent = 'Modifier l\'utilisateur';
      document.getElementById('editForm').dataset.id = id;
      document.getElementById('pwdRequired').textContent = '';
      document.getElementById('editForm').nom.value = r.nom || '';
      document.getElementById('editForm').prenom.value = r.prenom || '';
      document.getElementById('editForm').username.value = r.username || '';
      document.getElementById('editForm').email.value = r.email || '';
      document.getElementById('editForm').telephone.value = r.telephone || '';
      document.getElementById('editForm').role_id.value = r.role_id || '';
      document.getElementById('editForm').actif.value = r.actif || 1;
    }
  }
  bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
}

async function save(e) {
  e.preventDefault();
  const f = document.getElementById('editForm');
  const data = {
    nom: f.nom.value, prenom: f.prenom.value, username: f.username.value,
    email: f.email.value, telephone: f.telephone.value, role_id: f.role_id.value,
    actif: f.actif.value
  };
  if (f.password.value) data.password = f.password.value;
  const id = f.dataset.id;
  const res = id ? await API.post('Users/api_update/' + id, data) : await API.post('Users/api_create', data);
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
  const c = await API.confirm('Supprimer', 'Supprimer cet utilisateur ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Users/api_delete/' + id, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' utilisateurs' : ' utilisateur'); }
});

loadTable();
</script>
