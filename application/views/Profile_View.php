<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Mon Profil</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Mon Profil</li>
      </ol>
    </nav>
  </div>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <div class="w-120-px h-120-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center mx-auto" id="profileAvatar">
              <i class="bi bi-person-fill text-primary" style="font-size:64px;"></i>
            </div>
            <h2 id="displayName" class="mt-3"><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></h2>
            <h6 class="text-muted" id="displayEmail"><?= htmlspecialchars($user['email'] ?? '') ?></h6>
            <span class="badge bg-primary mt-2"><?= htmlspecialchars($this->session->userdata('role_libelle') ?? $group_name ?? '') ?></span>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileEdit" type="button">Informations personnelles</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#passwordChange" type="button">Changer le mot de passe</button>
              </li>
            </ul>

            <div class="tab-content pt-3">
              <div class="tab-pane fade show active" id="profileEdit">
                <form id="profileForm">
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Nom</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" class="form-control" id="nom" value="<?= htmlspecialchars($user['nom']) ?>">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Prénom</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" class="form-control" id="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Nom d'utilisateur</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade" id="passwordChange">
                <form id="passwordForm">
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Mot de passe actuel</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="password" class="form-control" id="current_password" required>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Nouveau mot de passe</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="password" class="form-control" id="new_password" required minlength="6">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Confirmer le mot de passe</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="password" class="form-control" id="confirm_password" required minlength="6">
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
function showMessage(success, text) {
  API.simpleAlert(success ? 'success' : 'error', success ? 'Succès' : 'Erreur', text);
}

document.getElementById('profileForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('button[type="submit"]');
  btn.disabled = true;
  const data = {
    nom: document.getElementById('nom').value,
    prenom: document.getElementById('prenom').value,
    email: document.getElementById('email').value
  };
  try {
    const r = await API.jsonRequest('api/profile/update', { method: 'POST', body: JSON.stringify(data), headers: { 'Content-Type': 'application/json' } });
    if (r.success) {
      document.getElementById('displayName').textContent = (data.nom + ' ' + data.prenom).trim();
      document.getElementById('displayEmail').textContent = data.email;
      showMessage(true, r.message || 'Profil mis à jour');
    } else {
      showMessage(false, r.message || 'Erreur');
    }
  } catch (err) {
    showMessage(false, 'Erreur de connexion');
  }
  btn.disabled = false;
});

document.getElementById('passwordForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('button[type="submit"]');
  btn.disabled = true;
  const newPass = document.getElementById('new_password').value;
  const confirmPass = document.getElementById('confirm_password').value;
  if (newPass !== confirmPass) {
    showMessage(false, 'Les mots de passe ne correspondent pas');
    btn.disabled = false;
    return;
  }
  const data = {
    current_password: document.getElementById('current_password').value,
    new_password: newPass,
    confirm_password: confirmPass
  };
  try {
    const r = await API.jsonRequest('api/profile/change_password', { method: 'POST', body: JSON.stringify(data), headers: { 'Content-Type': 'application/json' } });
    if (r.success) {
      this.reset();
      showMessage(true, r.message || 'Mot de passe changé');
    } else {
      showMessage(false, r.message || 'Erreur');
    }
  } catch (err) {
    showMessage(false, 'Erreur de connexion');
  }
  btn.disabled = false;
});
</script>
