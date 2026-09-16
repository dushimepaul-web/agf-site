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
          <h5 class="card-title mb-3">Médecins disponibles</h5>
          <div class="row g-4">
            <?php foreach ($medecins as $m): ?>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                  <div class="mb-3">
                    <i class="bi bi-person-circle" style="font-size:4rem;color:var(--primary);"></i>
                  </div>
                  <h5 class="card-title">Dr. <?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></h5>
                  <p class="badge bg-primary mb-2"><?= htmlspecialchars($m['specialite']) ?></p>
                  <p class="text-muted small"><?= htmlspecialchars($m['bio'] ?? '') ?></p>
                  <?php if (!empty($m['telephone'])): ?>
                  <p class="small"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($m['telephone']) ?></p>
                  <?php endif; ?>
                  <a href="<?= base_url('Consultation/formulaire/' . $m['id']) ?>" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-calendar-check me-1"></i>Consulter
                  </a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
