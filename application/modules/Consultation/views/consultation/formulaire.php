<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Formulaire de consultation</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('Consultation') ?>">Consultation</a></li>
        <li class="breadcrumb-item active">Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <!-- Infos médecin -->
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <i class="bi bi-person-circle" style="font-size:5rem;color:var(--primary);"></i>
            <h4 class="mt-3">Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></h4>
            <span class="badge bg-primary"><?= htmlspecialchars($medecin['specialite']) ?></span>
            <p class="text-muted small mt-2"><?= htmlspecialchars($medecin['bio'] ?? '') ?></p>
            <?php if (!empty($medecin['telephone'])): ?>
            <p class="small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($medecin['telephone']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Horaires -->
        <div class="card shadow-sm mt-3">
          <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-clock me-1"></i>Horaires disponibles</h6>
          </div>
          <div class="card-body">
            <?php if (empty($horaires)): ?>
            <p class="text-muted small">Aucun horaire disponible.</p>
            <?php else: ?>
            <?php
            $grouped = $this->Consultation_model->group_horaires_by_day($horaires);
            foreach ($grouped as $jour => $slots): ?>
            <div class="mb-2">
              <strong class="text-capitalize small"><?= $jour ?></strong><br>
              <?php foreach ($slots as $s): ?>
              <span class="badge bg-light text-dark border me-1"><?= substr($s['heure_debut'],0,5) ?> - <?= substr($s['heure_fin'],0,5) ?></span>
              <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Formulaire -->
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clipboard-plus me-1"></i>Informations du patient</h5>
          </div>
          <div class="card-body">
            <form id="consultForm">
              <input type="hidden" name="medecin_id" value="<?= $medecin['id'] ?>">

              <h6 class="text-primary mb-3">Identité</h6>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Nom <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nom" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Prénom <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="prenom" required>
                </div>
              </div>

              <h6 class="text-primary mb-3">Mesures</h6>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Poids (kg)</label>
                  <input type="text" class="form-control" name="poids" placeholder="ex: 70">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Taille (cm)</label>
                  <input type="text" class="form-control" name="taille" placeholder="ex: 175">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Adresse actuelle</label>
                <textarea class="form-control" name="adresse" rows="2" placeholder="Votre adresse..."></textarea>
              </div>

              <h6 class="text-primary mb-3">Symptômes</h6>
              <div class="mb-3">
                <label class="form-label">Décrivez vos symptômes <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description_symptomes" rows="4" required placeholder="Décrivez tous les symptômes que vous ressentez..."></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Depuis combien de temps ?</label>
                <input type="text" class="form-control" name="duree_symptomes" placeholder="ex: 3 jours, 1 semaine...">
              </div>

              <h6 class="text-primary mb-3">Images médicales</h6>
              <div class="mb-3">
                <label class="form-label">Photos / Imagerie médicale (5 max)</label>
                <input type="file" class="form-control" id="medicalFiles" multiple accept="image/*" max="5">
                <small class="text-muted">JPG, PNG, WEBP — 5 Mo max par fichier</small>
                <div id="medicalPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
              </div>

              <h6 class="text-primary mb-3">Preuve de paiement</h6>
              <div class="mb-3">
                <label class="form-label">Image du reçu / preuve de paiement</label>
                <input type="file" class="form-control" id="paiementFile" accept="image/*">
                <div id="paiementPreview" class="mt-2"></div>
              </div>

              <div class="d-flex gap-2 mt-4">
                <a href="<?= base_url('Consultation') ?>" class="btn btn-secondary">Retour</a>
                <button type="submit" class="btn btn-primary flex-grow-1" id="submitBtn">
                  <i class="bi bi-send me-1"></i>Envoyer la consultation
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
const medicalFiles = [];
const MAX_FILES = 5;

document.getElementById('medicalFiles').addEventListener('change', function(e) {
  const preview = document.getElementById('medicalPreview');
  const files = Array.from(e.target.files);
  
  if (medicalFiles.length + files.length > MAX_FILES) {
    API.simpleAlert('error', 'Limite', 'Maximum ' + MAX_FILES + ' images autorisées');
    return;
  }
  
  files.forEach(file => {
    if (file.size > 5 * 1024 * 1024) {
      API.simpleAlert('error', 'Erreur', file.name + ' dépasse 5 Mo');
      return;
    }
    const idx = medicalFiles.length;
    medicalFiles.push(file);
    const reader = new FileReader();
    reader.onload = function(ev) {
      const div = document.createElement('div');
      div.style.cssText = 'position:relative;width:80px;height:80px;';
      div.innerHTML = '<img src="' + ev.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">' +
        '<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="padding:0;width:20px;height:20px;font-size:10px;" data-idx="' + idx + '">&times;</button>';
      div.querySelector('button').addEventListener('click', function() {
        const i = parseInt(this.dataset.idx);
        medicalFiles.splice(i, 1);
        this.parentElement.remove();
      });
      preview.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
  this.value = '';
});

document.getElementById('paiementFile').addEventListener('change', function(e) {
  const preview = document.getElementById('paiementPreview');
  preview.innerHTML = '';
  if (e.target.files[0]) {
    const reader = new FileReader();
    reader.onload = function(ev) {
      preview.innerHTML = '<img src="' + ev.target.result + '" style="max-height:120px;border-radius:6px;">';
    };
    reader.readAsDataURL(e.target.files[0]);
  }
});

document.getElementById('consultForm').onsubmit = async function(e) {
  e.preventDefault();
  const f = this;
  const submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi en cours...';

  // 1. Soumettre les données
  const data = {
    medecin_id: parseInt(f.medecin_id.value),
    nom: f.nom.value.trim(),
    prenom: f.prenom.value.trim(),
    poids: f.poids.value.trim(),
    taille: f.taille.value.trim(),
    adresse: f.adresse.value.trim(),
    description_symptomes: f.description_symptomes.value.trim(),
    duree_symptomes: f.duree_symptomes.value.trim()
  };

  const res = await API.post('Consultation/api_submit', data);
  if (!res.success) {
    API.simpleAlert('error', 'Erreur', res.message);
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="bi bi-send me-1"></i>Envoyer la consultation';
    return;
  }

  const consultationId = res.data.id;

  // 2. Upload images médicales
  for (let i = 0; i < medicalFiles.length; i++) {
    const fd = new FormData();
    fd.append('file', medicalFiles[i]);
    fd.append(CSRF_TOKEN_NAME, CSRF_TOKEN);
    const upRes = await fetch(BASE_URL + 'Consultation/api_upload_media', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    }).then(r => r.json());
    
    if (upRes.success) {
      await API.post('Consultation/api_add_media', {
        consultation_id: consultationId,
        fichier_url: upRes.data.path,
        type: 'medical',
        ordre: i
      });
    }
  }

  // 3. Upload preuve paiement
  const paiementFile = document.getElementById('paiementFile').files[0];
  if (paiementFile) {
    const fd = new FormData();
    fd.append('file', paiementFile);
    fd.append(CSRF_TOKEN_NAME, CSRF_TOKEN);
    const upRes = await fetch(BASE_URL + 'Consultation/api_upload_media', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    }).then(r => r.json());
    
    if (upRes.success) {
      await API.post('Consultation/api_add_media', {
        consultation_id: consultationId,
        fichier_url: upRes.data.path,
        type: 'paiement',
        ordre: 0
      });
    }
  }

  // 4. Ouvrir WhatsApp
  const waRes = await API.get('Consultation/api_whatsapp/' + consultationId);
  if (waRes.success && waRes.data.url) {
    window.open(waRes.data.url, '_blank');
  }

  API.simpleAlert('success', 'Succès', 'Consultation envoyée avec succès !').then(() => {
    window.location.href = BASE_URL + 'Consultation';
  });
};
</script>
