<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des produits</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Produits</li>
        <li class="breadcrumb-item active">Liste</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Produits <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
            <div class="d-flex gap-2">
              <a href="<?= base_url('Categories') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-tags me-1"></i>Catégories</a>
              <a href="<?= base_url('Produits/add_edit') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
              <select id="filterCategorie" class="form-select form-select-sm" style="width:200px;" onchange="loadTable()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="position-relative flex-grow-1" style="max-width:300px;">
                <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-2 text-muted" style="font-size:.85rem;"></i>
                <input type="text" id="tableSearch" class="form-control form-control-sm ps-4 w-100" placeholder="Rechercher un produit...">
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
                  <th scope="col">Image</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Catégorie</th>
                  <th scope="col">Conditionnement</th>
                  <th scope="col">Certifié</th>
                  <th scope="col">Statut</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody"></tbody>
            </table>
            <div id="tableEmpty" class="text-center text-muted py-5 d-none">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucun produit trouvé.
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
  const badge = r.est_actif == 1
    ? '<span class="badge bg-success">Actif</span>'
    : '<span class="badge bg-secondary">Inactif</span>';
  const cert = r.est_certifie == 1
    ? '<i class="bi bi-check-circle-fill text-success"></i>'
    : '<i class="bi bi-dash text-muted"></i>';
  const img = r.image
    ? '<img src="<?= base_url() ?>' + API.esc(r.image) + '" alt="" style="height:32px;width:auto;border-radius:4px;">'
    : '<span class="text-muted"><i class="bi bi-image"></i></span>';
  return '<tr>' +
    '<td class="text-muted">' + num + '</td>' +
    '<td>' + img + '</td>' +
    '<td class="fw-semibold">' + API.esc(r.nom) + '</td>' +
    '<td><span class="badge bg-light text-dark border">' + API.esc(r.categorie_nom || '') + '</span></td>' +
    '<td>' + API.esc(r.conditionnement || '—') + '</td>' +
    '<td class="text-center">' + cert + '</td>' +
    '<td>' + badge + '</td>' +
    '<td class="text-end text-nowrap">' +
      '<a href="<?= base_url("Produits/add_edit") ?>/' + r.id + '" class="btn btn-sm btn-outline-primary me-1" title="Modifier"><i class="bi bi-pencil"></i></a>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="remove(' + r.id + ')"><i class="bi bi-trash"></i></button>' +
    '</td>' +
  '</tr>';
}

function searchTerms(r) {
  return [r.nom, r.categorie_nom, r.conditionnement, r.description].join(' ');
}

async function loadTable() {
  const cat = document.getElementById('filterCategorie').value;
  const url = cat ? 'Produits/api_list?categorie_id=' + cat : 'Produits/api_list';
  const res = await API.get(url);
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  rows = res.data || [];
  gt.setRows(rows);
}

async function remove(id) {
  const c = await API.confirm('Supprimer', 'Supprimer ce produit ?');
  if (!c.isConfirmed) return;
  const res = await API.post('Produits/api_delete/' + id, {});
  if (res.success) { await loadTable(); API.simpleAlert('success', 'Succès', res.message); }
  else API.simpleAlert('error', 'Erreur', res.message);
}

gt = new GeoTable({
  renderRow: renderRow,
  searchTerms: searchTerms,
  count: function (t, a) { return t + (t > 1 ? ' produits' : ' produit'); }
});

loadTable();
</script>
