<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Paramètres</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Paramètres</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center pt-3">
              <h5 class="card-title mb-0">Configuration de la plateforme</h5>
              <button type="button" class="btn btn-primary btn-sm" id="saveSettingsBtn"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
            </div>

            <div id="settingsMessage" class="alert d-none"></div>

            <form id="settingsForm">
              <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-general" type="button">Général</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-finances" type="button">Finances</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-email" type="button">Email</button>
                </li>
              </ul>

              <div class="tab-content pt-3">
                <div class="tab-pane fade show active" id="pane-general">
                  <div class="row g-3">
                    <div class="col-12">
                      <label class="form-label fw-semibold">Nom de l'app</label>
                      <input type="text" class="form-control" id="nom_ecole" placeholder="Ex: Ministère de la Santé Publique">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Téléphone</label>
                      <input type="text" class="form-control" id="telephone_ecole" placeholder="+257 00 000 000">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold">Email</label>
                      <input type="email" class="form-control" id="email_ecole" placeholder="contact@surveillance.bi">
                    </div>
                    <div class="col-12">
                      <label class="form-label fw-semibold">Adresse</label>
                      <textarea class="form-control" id="adresse_ecole" rows="3" placeholder="Bujumbura, Burundi"></textarea>
                    </div>
                  </div>
                </div>

               
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Logo de l'organisation</h5>
            <div class="mb-3 d-flex align-items-center justify-content-center bg-light rounded-3 p-3" style="min-height:130px;">
              <img src="<?= base_url($this->Model->get_setting('logo_ecole', 'assets/images/logo.png')) ?>" alt="Logo" class="img-fluid" style="max-height:110px;" id="logoImg">
            </div>
            <small class="text-muted d-block mb-3">JPG, PNG, SVG, WEBP — max 2MB</small>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('logoInput').click()">
              <i class="bi bi-upload me-1"></i> Changer le logo
            </button>
            <input type="file" id="logoInput" class="d-none" accept="image/jpeg,image/png,image/gif,image/svg+xml,image/webp">
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body text-center">
            <h5 class="card-title">Favicon</h5>
            <div class="d-flex align-items-center justify-content-center mb-3">
              <img src="<?= base_url($this->Model->get_setting('favicon_ecole', 'assets/images/favicon.png')) ?>" alt="Favicon" style="width:48px;height:48px;border-radius:8px;" id="faviconImg">
            </div>
            <small class="text-muted d-block mb-3">PNG, ICO, SVG — max 1MB</small>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('faviconInput').click()">
              <i class="bi bi-upload me-1"></i> Changer
            </button>
            <input type="file" id="faviconInput" class="d-none" accept="image/png,image/x-icon,image/svg+xml">
          </div>
        </div>

       
      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true });

async function loadSettings() {
  try {
    const r = await API.parametres.list();
    if (!r.success || !r.data) return;
    const s = r.data;
    if (s.nom_ecole) document.getElementById('nom_ecole').value = s.nom_ecole;
    if (s.telephone_ecole) document.getElementById('telephone_ecole').value = s.telephone_ecole;
    if (s.email_ecole) document.getElementById('email_ecole').value = s.email_ecole;
    if (s.adresse_ecole) document.getElementById('adresse_ecole').value = s.adresse_ecole;
    if (s.devise) document.getElementById('devise').value = s.devise;
    if (s.tva) document.getElementById('tva').value = s.tva;
    if (s.prochain_num_recu) document.getElementById('prochain_num_recu').value = s.prochain_num_recu;
    if (s.email_protocol) document.getElementById('email_protocol').value = s.email_protocol;
    if (s.email_smtp_host) document.getElementById('email_smtp_host').value = s.email_smtp_host;
    if (s.email_smtp_user) document.getElementById('email_smtp_user').value = s.email_smtp_user;
    if (s.email_smtp_pass) document.getElementById('email_smtp_pass').value = s.email_smtp_pass;
    if (s.email_smtp_port) document.getElementById('email_smtp_port').value = s.email_smtp_port;
    if (s.email_smtp_crypto) document.getElementById('email_smtp_crypto').value = s.email_smtp_crypto;
    if (s.email_sendmail_path) document.getElementById('email_sendmail_path').value = s.email_sendmail_path;
    if (s.logo_ecole) document.getElementById('logoImg').src = '<?= base_url() ?>' + s.logo_ecole;
    if (s.favicon_ecole) document.getElementById('faviconImg').src = '<?= base_url() ?>' + s.favicon_ecole;
    if (s.login_img) document.getElementById('loginImg').src = '<?= base_url() ?>' + s.login_img;
  } catch (err) { console.error(err); }
}

function showSettingsMessage(type, text) {
  const msg = document.getElementById('settingsMessage');
  msg.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-danger') + ' d-flex align-items-center';
  msg.innerHTML = '<i class="bi ' + (type === 'success' ? 'bi-check-circle' : 'bi-x-circle') + ' me-2"></i> ' + API.esc(text);
  if (type === 'success') setTimeout(() => msg.className = 'alert d-none', 3000);
}

document.getElementById('saveSettingsBtn').addEventListener('click', () => {
  document.getElementById('settingsForm').dispatchEvent(new Event('submit'));
});

document.getElementById('settingsForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('saveSettingsBtn');
  btn.disabled = true;
  const data = {
    nom_ecole: document.getElementById('nom_ecole').value,
    telephone_ecole: document.getElementById('telephone_ecole').value,
    email_ecole: document.getElementById('email_ecole').value,
    adresse_ecole: document.getElementById('adresse_ecole').value,
    devise: document.getElementById('devise').value,
    tva: document.getElementById('tva').value || '0',
    prochain_num_recu: document.getElementById('prochain_num_recu').value || '1',
    email_protocol: document.getElementById('email_protocol').value,
    email_smtp_host: document.getElementById('email_smtp_host').value,
    email_smtp_user: document.getElementById('email_smtp_user').value,
    email_smtp_pass: document.getElementById('email_smtp_pass').value,
    email_smtp_port: document.getElementById('email_smtp_port').value || '587',
    email_smtp_crypto: document.getElementById('email_smtp_crypto').value,
    email_sendmail_path: document.getElementById('email_sendmail_path').value
  };
  try {
    const r = await API.parametres.update(data);
    if (r.success) showSettingsMessage('success', 'Paramètres enregistrés avec succès');
    else showSettingsMessage('error', r.message || 'Erreur');
  } catch (err) {
    showSettingsMessage('error', 'Erreur de connexion');
  } finally { btn.disabled = false; }
});

document.getElementById('logoInput').addEventListener('change', async function() {
  if (!this.files || !this.files[0]) return;
  const fd = new FormData(); fd.append('logo', this.files[0]);
  try {
    const res = await fetch(API.base_url + 'api/parametres/upload_logo', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const r = await res.json();
    if (r.success) {
      document.getElementById('logoImg').src = API.base_url + r.data.path;
      Toast.fire({ icon: 'success', title: 'Logo mis à jour' });
    } else Swal.fire({ icon: 'error', title: 'Erreur', text: r.message || 'Erreur upload' });
  } catch (e) { Swal.fire({ icon: 'error', title: 'Erreur', text: 'Erreur de connexion' }); }
});

document.getElementById('faviconInput').addEventListener('change', async function() {
  if (!this.files || !this.files[0]) return;
  const fd = new FormData(); fd.append('favicon', this.files[0]);
  try {
    const res = await fetch(API.base_url + 'api/parametres/upload_favicon', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const r = await res.json();
    if (r.success) {
      document.getElementById('faviconImg').src = API.base_url + r.data.path;
      Toast.fire({ icon: 'success', title: 'Favicon mis à jour' });
    } else Swal.fire({ icon: 'error', title: 'Erreur', text: r.message || 'Erreur upload' });
  } catch (e) { Swal.fire({ icon: 'error', title: 'Erreur', text: 'Erreur de connexion' }); }
});

document.getElementById('loginImgInput').addEventListener('change', async function() {
  if (!this.files || !this.files[0]) return;
  const fd = new FormData(); fd.append('login_img', this.files[0]);
  try {
    const res = await fetch(API.base_url + 'api/parametres/upload_login_img', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const r = await res.json();
    if (r.success) {
      document.getElementById('loginImg').src = API.base_url + r.data.path;
      Toast.fire({ icon: 'success', title: 'Image de connexion mise à jour' });
    } else Swal.fire({ icon: 'error', title: 'Erreur', text: r.message || 'Erreur upload' });
  } catch (e) { Swal.fire({ icon: 'error', title: 'Erreur', text: 'Erreur de connexion' }); }
});

(function() { loadSettings(); })();
</script>
