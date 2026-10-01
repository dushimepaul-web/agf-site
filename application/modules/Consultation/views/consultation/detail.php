<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Fiche expert</h1>
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
      <!-- Profil -->
      <div class="col-lg-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center py-4">
            <?php if (!empty($medecin['photo'])): ?>
            <img src="<?= base_url($medecin['photo']) ?>" alt="" class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
            <?php else: ?>
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:100px;height:100px;background:var(--primary);color:#fff;font-size:2.2rem;">
              <?= strtoupper(substr($medecin['prenom'] ?? 'D', 0, 1) . substr($medecin['nom'] ?? 'R', 0, 1)) ?>
            </div>
            <?php endif; ?>

            <h4 class="fw-bold mb-1">Dr. <?= htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']) ?></h4>
            <span class="badge bg-primary mb-3"><?= htmlspecialchars($medecin['specialite']) ?></span>

            <?php if (!empty($medecin['note_moyenne']) && $medecin['note_moyenne'] > 0): ?>
            <div class="mb-3">
              <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="bi bi-star<?= $i <= round($medecin['note_moyenne']) ? '-fill text-warning' : '-fill text-muted' ?>" style="font-size:.9rem;"></i>
              <?php endfor; ?>
              <span class="ms-1 text-muted small">(<?= number_format($medecin['note_moyenne'], 1) ?> — <?= (int)($medecin['nombre_avis'] ?? 0) ?> avis)</span>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['bio'])): ?>
            <p class="text-muted small px-3"><?= htmlspecialchars($medecin['bio']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Infos pratiques -->
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Informations pratiques</h6>
          </div>
          <div class="card-body">
            <?php if (!empty($medecin['numero_licence'])): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-card-heading text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">N° Licence</small>
                <span class="fw-semibold"><?= htmlspecialchars($medecin['numero_licence']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['annees_experience'])): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-briefcase text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">Expérience</small>
                <span class="fw-semibold"><?= (int)$medecin['annees_experience'] ?> ans</span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['honoraires_consultation']) && $medecin['honoraires_consultation'] > 0): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-cash-stack text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">Honoraires</small>
                <span class="fw-semibold"><?= number_format($medecin['honoraires_consultation'], 0) ?> <?= htmlspecialchars($medecin['currency'] ?? 'USD') ?></span>
                <?php if (!empty($medecin['USD_EUR_Equivalent_en_BIF'])): ?>
                <small class="text-muted d-block">≈ <?= number_format($medecin['USD_EUR_Equivalent_en_BIF'], 0) ?> BIF</small>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['telephone'])): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-telephone text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">Téléphone</small>
                <span class="fw-semibold"><?= htmlspecialchars($medecin['telephone']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['email'])): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-envelope text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">Email</small>
                <span class="fw-semibold"><?= htmlspecialchars($medecin['email']) ?></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($medecin['langues_parlees'])): ?>
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-translate text-primary me-2"></i>
              <div>
                <small class="text-muted d-block">Langues</small>
                <span class="fw-semibold"><?= htmlspecialchars($medecin['langues_parlees']) ?></span>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($medecin['diplomes'])): ?>
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold"><i class="bi bi-mortarboard me-2"></i>Diplômes</h6>
          </div>
          <div class="card-body">
            <p class="mb-0 small text-muted"><?= nl2br(htmlspecialchars($medecin['diplomes'])) ?></p>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Colonne droite : Horaires + CTA -->
      <div class="col-lg-8">
        <!-- Horaires -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold"><i class="bi bi-clock me-2"></i>Horaires de disponibilité</h6>
          </div>
          <div class="card-body">
            <?php if (empty($horaires)): ?>
            <div class="text-center py-3">
              <i class="bi bi-clock-history fs-3 text-muted d-block mb-2"></i>
              <p class="text-muted mb-0">Aucun horaire renseigné. Contactez directement l'expert.</p>
            </div>
            <?php else: ?>
            <?php
            $grouped = $this->Consultation_model->group_horaires_by_day($horaires);
            $jourOrder = array_keys(Consultation_model::JOUR_ORDER);
            ?>
            <div class="table-responsive">
              <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:140px;">Jour</th>
                    <th>Créneaux</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($jourOrder as $jour): ?>
                  <?php if (!empty($grouped[$jour])): ?>
                  <tr>
                    <td class="text-capitalize fw-semibold"><?= $jour ?></td>
                    <td>
                      <?php foreach ($grouped[$jour] as $s): ?>
                      <span class="badge bg-success bg-opacity-10 text-success me-1 mb-1">
                        <i class="bi bi-clock me-1"></i><?= substr($s['heure_debut'],0,5) ?> — <?= substr($s['heure_fin'],0,5) ?>
                      </span>
                      <?php endforeach; ?>
                    </td>
                  </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- CTA -->
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-body text-center py-4">
            <h5 class="fw-bold mb-2">Besoin d'une consultation ?</h5>
            <p class="text-muted small mb-4">Prenez rendez-vous avec le Dr. <?= htmlspecialchars($medecin['prenom']) ?> en remplissant le formulaire de consultation.</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
              <a href="<?= base_url('Consultation/formulaire/' . $medecin['id']) ?>" class="btn btn-primary btn-lg px-4">
                <i class="bi bi-calendar-check me-2"></i>Prendre rendez-vous
              </a>
              <?php if (!empty($medecin['telephone'])): ?>
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $medecin['telephone']) ?>" target="_blank" class="btn btn-success btn-lg px-4">
                <i class="bi bi-whatsapp me-2"></i>Contacter via WhatsApp
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Avis (placeholder) -->
        <div class="card shadow-sm border-0 mt-3">
          <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold"><i class="bi bi-chat-left-text me-2"></i>Avis des patients</h6>
          </div>
          <div class="card-body text-center py-4">
            <p class="text-muted mb-0"><i class="bi bi-emoji-neutral fs-3 d-block mb-2"></i>Aucun avis pour le moment.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
