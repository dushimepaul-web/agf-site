<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Consultation en ligne</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Consultation</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
              <h5 class="card-title mb-1">Nos experts disponibles</h5>
              <p class="text-muted small mb-0">Choisissez un expert et prenez rendez-vous en quelques clics</p>
            </div>
            <div class="position-relative" style="max-width:280px;">
              <i class="bi bi-search position-absolute start-0 top-50 translate-middle ms-3 text-muted"></i>
              <input type="text" id="searchMedecin" class="form-control ps-5" placeholder="Rechercher un expert..." oninput="filterMedecins()">
            </div>
          </div>

          <div class="row g-4" id="medecinsList">
            <?php foreach ($medecins as $m): ?>
            <div class="col-md-6 col-lg-4 medecin-card" data-search="<?= strtolower(htmlspecialchars(($m['prenom'] ?? '') . ' ' . ($m['nom'] ?? '') . ' ' . ($m['specialite'] ?? '') . ' ' . ($m['bio'] ?? ''))) ?>">
              <div class="card h-100 shadow-sm border-0 hover-shadow">
                <div class="card-body d-flex flex-column">
                  <div class="d-flex align-items-center mb-3">
                    <?php if (!empty($m['photo'])): ?>
                    <img src="<?= base_url($m['photo']) ?>" alt="" class="rounded-circle me-3" style="width:60px;height:60px;object-fit:cover;">
                    <?php else: ?>
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px;height:60px;background:var(--primary);color:#fff;font-size:1.5rem;">
                      <?= strtoupper(substr($m['prenom'] ?? 'D', 0, 1) . substr($m['nom'] ?? 'R', 0, 1)) ?>
                    </div>
                    <?php endif; ?>
                    <div>
                      <h6 class="mb-0 fw-bold">Dr. <?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></h6>
                      <span class="badge bg-primary bg-opacity-10 text-primary"><?= htmlspecialchars($m['specialite']) ?></span>
                    </div>
                  </div>

                  <?php if (!empty($m['bio'])): ?>
                  <p class="text-muted small flex-grow-1"><?= htmlspecialchars(mb_strimwidth($m['bio'], 0, 120, '...')) ?></p>
                  <?php else: ?>
                  <p class="text-muted small flex-grow-1">Expert en <?= htmlspecialchars($m['specialite']) ?></p>
                  <?php endif; ?>

                  <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php if (!empty($m['numero_licence'])): ?>
                    <span class="badge bg-light text-dark border"><i class="bi bi-card-heading me-1"></i><?= htmlspecialchars($m['numero_licence']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($m['annees_experience'])): ?>
                    <span class="badge bg-light text-dark border"><i class="bi bi-briefcase me-1"></i><?= (int)$m['annees_experience'] ?> ans</span>
                    <?php endif; ?>
                    <?php if (!empty($m['honoraires_consultation']) && $m['honoraires_consultation'] > 0): ?>
                    <span class="badge bg-light text-dark border"><i class="bi bi-cash me-1"></i><?= number_format($m['honoraires_consultation'], 0) ?> <?= htmlspecialchars($m['currency'] ?? 'USD') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($m['note_moyenne']) && $m['note_moyenne'] > 0): ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i><?= number_format($m['note_moyenne'], 1) ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="d-flex gap-2">
                    <a href="<?= base_url('Consultation/detail/' . $m['id']) ?>" class="btn btn-outline-primary btn-sm flex-grow-1">
                      <i class="bi bi-person-lines-fill me-1"></i>Voir profil
                    </a>
                    <a href="<?= base_url('Consultation/formulaire/' . $m['id']) ?>" class="btn btn-primary btn-sm flex-grow-1">
                      <i class="bi bi-calendar-check me-1"></i>Consulter
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <?php if (empty($medecins)): ?>
          <div class="text-center py-5">
            <i class="bi bi-person-x fs-1 text-muted d-block mb-3"></i>
            <h6 class="text-muted">Aucun expert disponible pour le moment</h6>
            <p class="text-muted small">Veuillez revenir plus tard.</p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<style>
.hover-shadow { transition: box-shadow .2s, transform .2s; }
.hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important; transform: translateY(-2px); }
</style>

<script>
function filterMedecins() {
  const q = document.getElementById('searchMedecin').value.toLowerCase();
  document.querySelectorAll('.medecin-card').forEach(card => {
    const text = card.dataset.search || '';
    card.style.display = text.includes(q) ? '' : 'none';
  });
}
</script>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
