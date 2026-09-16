<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Gestion des permissions</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Administration</li>
        <li class="breadcrumb-item active">Permissions</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-3">
            <h5 class="card-title mb-0">Droits d'accès par rôle <span id="moduleCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
          </div>

          <div class="d-flex flex-wrap align-items-center gap-2 my-3">
            <div class="flex-grow-1" style="max-width:320px;">
              <label class="form-label fw-semibold mb-1">Rôle</label>
              <select id="roleSelect" class="form-select" onchange="loadPermissions()" aria-label="Sélectionner un rôle">
                <option value="" selected>Sélectionner un rôle</option>
                <?php foreach ($roles as $r): ?>
                  <option value="<?= $r['uuid'] ?>"><?= htmlspecialchars($r['nom']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div id="permsLoading" class="text-center py-4 d-none">
            <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
            <span class="text-muted">Chargement des permissions...</span>
          </div>
          <div id="permsEmpty" class="text-center text-muted py-5">
            <i class="bi bi-person-gear fs-3 d-block mb-2"></i>
            Sélectionnez un rôle pour afficher ses permissions.
          </div>
          <div id="permsContainer"></div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
let allModules = <?= json_encode($modules) ?>;

(function () {
  const el = document.getElementById('moduleCount');
  if (el) el.textContent = allModules.length + (allModules.length > 1 ? ' modules' : ' module');
})();

function htmlspecialchars(s) {
  return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

async function loadPermissions() {
  const idRole = document.getElementById('roleSelect').value;
  const loading = document.getElementById('permsLoading');
  const empty = document.getElementById('permsEmpty');
  const container = document.getElementById('permsContainer');

  container.innerHTML = '';
  if (!idRole) {
    loading.classList.add('d-none');
    empty.classList.remove('d-none');
    return;
  }
  empty.classList.add('d-none');
  loading.classList.remove('d-none');

  const res = await API.get('api/permissions/' + idRole);
  loading.classList.add('d-none');
  if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
  if (!allModules.length) {
    empty.innerHTML = '<i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun module disponible.';
    empty.classList.remove('d-none');
    return;
  }

  const rows = res.data || [];
  const permMap = {};
  rows.forEach(r => { permMap[r.id_module] = r; });

  let html = '<div class="table-responsive">' +
    '<table class="table table-hover align-middle mb-3">' +
      '<thead class="table-light"><tr>' +
        '<th scope="col">Module</th>' +
        '<th scope="col" class="text-center">Lire</th>' +
        '<th scope="col" class="text-center">Éditer les valeurs</th>' +
        '<th scope="col" class="text-center">Gérer les variables</th>' +
      '</tr></thead><tbody>';
  allModules.forEach(m => {
    const p = permMap[m.id_module] || {};
    html += '<tr>' +
      '<td><span class="fw-semibold">' + htmlspecialchars(m.nom) + '</span> <span class="badge bg-light text-dark">' + htmlspecialchars(m.code) + '</span></td>' +
      '<td class="text-center"><input type="checkbox" class="form-check-input perm-cb" data-module="' + m.id_module + '" data-field="peut_lire" ' + (parseInt(p.peut_lire) ? 'checked' : '') + ' aria-label="Lire"></td>' +
      '<td class="text-center"><input type="checkbox" class="form-check-input perm-cb" data-module="' + m.id_module + '" data-field="peut_editer_valeurs" ' + (parseInt(p.peut_editer_valeurs) ? 'checked' : '') + ' aria-label="Éditer les valeurs"></td>' +
      '<td class="text-center"><input type="checkbox" class="form-check-input perm-cb" data-module="' + m.id_module + '" data-field="peut_gerer_variables" ' + (parseInt(p.peut_gerer_variables) ? 'checked' : '') + ' aria-label="Gérer les variables"></td>' +
      '</tr>';
  });
  html += '</tbody></table></div>';
  html += '<button type="button" class="btn btn-primary" onclick="savePermissions()"><i class="bi bi-check2-square me-1"></i>Enregistrer les permissions</button>';
  container.innerHTML = html;
}

async function savePermissions() {
  const idRole = document.getElementById('roleSelect').value;
  if (!idRole) return;
  const cbs = document.querySelectorAll('.perm-cb');
  const perms = {};
  cbs.forEach(cb => {
    const mod = cb.dataset.module;
    if (!perms[mod]) perms[mod] = { id_module: parseInt(mod), peut_lire: 0, peut_editer_valeurs: 0, peut_gerer_variables: 0 };
    perms[mod][cb.dataset.field] = cb.checked ? 1 : 0;
  });
  const res = await API.post('api/permissions/' + idRole + '/update', { permissions: Object.values(perms) });
  if (res.success) API.simpleAlert('success', 'Succès', res.message);
  else API.simpleAlert('error', 'Erreur', res.message);
}
</script>