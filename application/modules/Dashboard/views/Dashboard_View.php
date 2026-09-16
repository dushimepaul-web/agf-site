<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<main id="main" class="main">

  <div class="pagetitle">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
      <div class="d-flex align-items-center gap-2">
        <label class="form-label small text-muted mb-0" for="filtreMaladie">Maladie</label>
        <select id="filtreMaladie" class="form-select form-select-sm w-auto" aria-label="Sélectionner une maladie">
          <option value="">Vue d'ensemble (toutes)</option>
          <option value="mpox">Mpox</option>
          <option value="cholera">Choléra</option>
          <option value="rougeole">Rougeole</option>
          <option value="paludisme">Paludisme</option>
        </select>
      </div>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($maladie_nom ?? '') ?></li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">
    <div class="row">

      <!-- ====== Filtres ====== -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-2">Filtres</h5>
            <div class="row g-2 align-items-end">
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Province</label>
                <select id="filtProvince" class="form-select form-select-sm" data-placeholder="Toutes les provinces"></select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Commune</label>
                <select id="filtCommune" class="form-select form-select-sm" data-placeholder="Toutes les communes" disabled></select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Zone</label>
                <select id="filtZone" class="form-select form-select-sm" data-placeholder="Toutes les zones" disabled></select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Colline</label>
                <select id="filtColline" class="form-select form-select-sm" data-placeholder="Toutes les collines" disabled></select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Statut du cas</label>
                <select id="filtStatut" class="form-select form-select-sm">
                  <option value="">Tous</option>
                  <option value="suspect">Suspect</option>
                  <option value="probable">Probable</option>
                  <option value="confirme">Confirmé</option>
                  <option value="ecarte">Écarté</option>
                </select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Sexe</label>
                <select id="filtSexe" class="form-select form-select-sm">
                  <option value="">Tous</option>
                  <option value="M">Masculin</option>
                  <option value="F">Féminin</option>
                </select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Tranche d'âge</label>
                <select id="filtTranche" class="form-select form-select-sm">
                  <option value="">Toutes</option>
                  <option value="0-4">0-4 ans</option>
                  <option value="5-14">5-14 ans</option>
                  <option value="15-49">15-49 ans</option>
                  <option value="50-64">50-64 ans</option>
                  <option value="65+">65+ ans</option>
                </select>
              </div>
              <div class="col-6 col-md-4 col-lg-3">
                <label class="form-label small text-muted mb-1">Grossesse</label>
                <select id="filtEnceinte" class="form-select form-select-sm">
                  <option value="">Toutes</option>
                  <option value="1">Enceinte</option>
                </select>
              </div>
              <div class="col-12 mt-2">
                <button type="button" id="btnApply" class="btn btn-primary btn-sm me-1"><i class="bi bi-funnel me-1"></i>Appliquer</button>
                <button type="button" id="btnReset" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser</button>
                <span id="kpiLoading" class="ms-2 d-none">
                  <span class="spinner-border spinner-border-sm text-primary me-2" role="status" aria-hidden="true"></span>
                  <span class="text-muted small">Chargement des données...</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ====== KPI ====== -->
      <div class="col-xxl-3 col-md-6 col-lg-4">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">Cas notifiés</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-clipboard2-pulse"></i></div>
              <div class="ps-3">
                <h6 id="kpiTotal">0</h6>
                <span class="text-muted small pt-2 ps-1"><?= htmlspecialchars($maladie_nom ?? 'toutes maladies') ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xxl-3 col-md-6 col-lg-4">
        <div class="card info-card revenue-card">
          <div class="card-body">
            <h5 class="card-title">Cas confirmés</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-check2-circle"></i></div>
              <div class="ps-3">
                <h6 id="kpiConfirme">0</h6>
                <span class="text-muted small pt-2 ps-1">statut confirmé</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xxl-3 col-md-6 col-lg-4">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Décès</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-slash-circle"></i></div>
              <div class="ps-3">
                <h6 id="kpiDecede">0</h6>
                <span class="text-muted small pt-2 ps-1">taux de létalité (CFR) : <strong id="kpiCfr">0 %</strong></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xxl-3 col-md-6 col-lg-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Hospitalisés</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-hospital"></i></div>
              <div class="ps-3">
                <h6 id="kpiHospitalises">0</h6>
                <span class="text-muted small pt-2 ps-1">sous traitement</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xxl-3 col-md-6 col-lg-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Taux d'attaque</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-people"></i></div>
              <div class="ps-3">
                <h6 id="kpiTaux">—</h6>
                <span class="text-muted small pt-2 ps-1">cas / 100 000 hab. <span id="kpiTauxSrc" class="d-none">· pop. de référence</span></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ====== Courbe épidémique ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Courbe épidémique (cas par mois)</h5>
            <div style="height:340px; position:relative;"><canvas id="casChart"></canvas></div>
          </div>
        </div>
      </div>

      <!-- ====== Statuts ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Répartition par statut</h5>
            <div style="height:340px; position:relative;"><canvas id="statutChart"></canvas></div>
          </div>
        </div>
      </div>

      <!-- ====== Provinces ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Cas par province</h5>
            <div style="height:340px; position:relative;"><canvas id="provChart"></canvas></div>
          </div>
        </div>
      </div>

      <!-- ====== Carte hotspots ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
              <h5 class="card-title mb-0">Carte des zones touchées</h5>
              <span class="text-muted small">Taille du point = nombre de cas</span>
            </div>
            <div id="dashMap" style="height:350px; z-index:0; border-radius:6px;"></div>
            <div class="d-flex flex-wrap gap-3 mt-2 small text-muted">
              <span><span class="legend-dot d-inline-block rounded-circle me-1" style="background:#6c757d;"></span>0 cas</span>
              <span><span class="legend-dot d-inline-block rounded-circle me-1" style="background:#0a7a0a;"></span>1-5</span>
              <span><span class="legend-dot d-inline-block rounded-circle me-1" style="background:#ffc107;"></span>6-15</span>
              <span><span class="legend-dot d-inline-block rounded-circle me-1" style="background:#fd7e14;"></span>16-50</span>
              <span><span class="legend-dot d-inline-block rounded-circle me-1" style="background:#dc3545;"></span>&gt;50</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ====== Qualité de surveillance ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Qualité de surveillance</h5>
            <div class="mb-3">
              <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Délai moyen de notification</span>
                <span class="text-dark"><strong id="qualDelai">—</strong></span>
              </div>
            </div>
            <div class="mb-1 small text-muted">Date de début des symptômes renseignée</div>
            <div class="progress mb-3" style="height:10px;">
              <div id="qualSymptomesBar" class="progress-bar bg-primary" style="width:0%"></div>
            </div>
            <div class="d-flex justify-content-between small"><span></span><span class="text-dark" id="qualSymptomes">0 %</span></div>
            <div class="mb-1 small text-muted">Cas testés en laboratoire</div>
            <div class="progress mb-3" style="height:10px;">
              <div id="qualLaboBar" class="progress-bar bg-success" style="width:0%"></div>
            </div>
            <div class="d-flex justify-content-between small"><span></span><span class="text-dark" id="qualLabo">0 %</span></div>
            <div class="mb-1 small text-muted">Issue connue (guéri / décédé / perdu de vue)</div>
            <div class="progress" style="height:10px;">
              <div id="qualIssueBar" class="progress-bar bg-warning" style="width:0%"></div>
            </div>
            <div class="d-flex justify-content-between small mt-1"><span></span><span class="text-dark" id="qualIssue">0 %</span></div>
          </div>
        </div>
      </div>

      <!-- ====== Vaccination ====== -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Couverture vaccinale</h5>
            <div class="d-flex justify-content-between small text-muted mb-2">
              <span>Personnes vaccinées : <strong class="text-dark" id="vaccPersonnes">0</strong></span>
              <span>Doses administrées : <strong class="text-dark" id="vaccDoses">0</strong></span>
            </div>
            <div class="progress" style="height:12px;">
              <div id="vaccProgression" class="progress-bar bg-success" style="width:0%"></div>
            </div>
            <div class="text-end text-muted small mt-1"><span id="vaccPct">0</span> de doses par personne</div>
          </div>
        </div>
      </div>

      <!-- ====== Derniers cas ====== -->
      <div class="col-lg-12">
        <div class="card recent-sales overflow-auto">
          <div class="card-body">
            <h5 class="card-title">Derniers cas notifiés</h5>
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Code cas</th>
                  <th scope="col">Province</th>
                  <th scope="col">Statut</th>
                </tr>
              </thead>
              <tbody id="derniersCasBody">
                <tr><td colspan="4" class="text-center text-muted">Chargement...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>window.DASH = { maladie: <?= json_encode((string)($maladie ?? '')) ?>, maladieNom: <?= json_encode((string)($maladie_nom ?? '')) ?> };</script>
<script src="<?= base_url() ?>assets/js/dashboard.js?v=<?= filemtime(FCPATH.'assets/js/dashboard.js') ?>"></script>