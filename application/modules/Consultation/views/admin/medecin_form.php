<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1><?= $title ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item">Consultation</li>
        <li class="breadcrumb-item"><a href="<?= base_url('Consultations/medecins') ?>">Experts</a></li>
        <li class="breadcrumb-item active"><?= $medecin ? 'Modifier' : 'Ajouter' ?></li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <form id="editForm" onsubmit="return save(event)">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                  <select name="user_id" class="form-select" required id="selUser">
                    <option value="">-- Sélectionner un utilisateur --</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Spécialité <span class="text-danger">*</span></label>
                  <input type="text" name="specialite" class="form-control" required value="<?= $medecin['specialite'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">N° Licence</label>
                  <input type="text" name="numero_licence" class="form-control" value="<?= $medecin['numero_licence'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Années d'expérience</label>
                  <input type="number" name="annees_experience" class="form-control" value="<?= $medecin['annees_experience'] ?? 0 ?>" min="0">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Honoraires consultation (USD)</label>
                  <input type="number" name="honoraires_consultation" class="form-control" value="<?= $medecin['honoraires_consultation'] ?? 0 ?>" step="0.01" min="0">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Statut</label>
                  <select name="actif" class="form-select">
                    <option value="1" <?= ($medecin['actif'] ?? 1) == 1 ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= ($medecin['actif'] ?? 1) == 0 ? 'selected' : '' ?>>Inactif</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Bio</label>
                  <textarea name="bio" class="form-control" rows="3"><?= $medecin['bio'] ?? '' ?></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label">Diplômes</label>
                  <textarea name="diplomes" class="form-control" rows="2"><?= $medecin['diplomes'] ?? '' ?></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label">Langues parlées</label>
                  <input type="text" name="langues_parlees" class="form-control" placeholder="ex: Français, Lingala, Anglais" value="<?= $medecin['langues_parlees'] ?? '' ?>">
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?= base_url('Consultations/medecins') ?>" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary" id="btnSave">
                  <span class="spinner-border spinner-border-sm d-none me-1" id="btnSpinner"></span>
                  Enregistrer
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
const editUuid = <?= $medecin ? json_encode($medecin['uuid']) : 'null' ?>;
const selectedUserId = <?= $medecin ? (int)$medecin['user_id'] : '0' ?>;

async function loadUsers() {
  const res = await API.get('Users/api_list');
  if (!res.success || !res.data) return;
  const sel = document.getElementById('selUser');
  res.data.forEach(u => {
    const opt = document.createElement('option');
    opt.value = u.id;
    opt.textContent = u.prenom + ' ' + u.nom + ' (' + u.email + ')';
    if (selectedUserId && parseInt(u.id) === selectedUserId) {
      opt.selected = true;
    }
    sel.appendChild(opt);
  });
}

async function save(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSave');
  const spinner = document.getElementById('btnSpinner');
  btn.disabled = true;
  spinner.classList.remove('d-none');

  const f = document.getElementById('editForm');
  const data = {
    user_id: f.user_id.value, specialite: f.specialite.value, numero_licence: f.numero_licence.value,
    annees_experience: f.annees_experience.value, honoraires_consultation: f.honoraires_consultation.value,
    bio: f.bio.value, diplomes: f.diplomes.value, langues_parlees: f.langues_parlees.value,
    actif: f.actif.value
  };

  const res = editUuid
    ? await API.post('Consultations/api_medecins_update/' + editUuid, data)
    : await API.post('Consultations/api_medecins_create', data);

  btn.disabled = false;
  spinner.classList.add('d-none');

  if (res.success) {
    API.simpleAlert('success', 'Succès', res.message);
    setTimeout(() => { window.location.href = '<?= base_url('Consultations/medecins') ?>'; }, 1500);
  } else {
    API.simpleAlert('error', 'Erreur', res.message);
  }
  return false;
}

loadUsers();
</script>
