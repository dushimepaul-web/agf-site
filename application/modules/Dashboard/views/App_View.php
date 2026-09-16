<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<script>window.<?= $app_meta['js_var'] ?> = { module: <?= json_encode($module_code) ?> };</script>

<main id="main" class="main">

  <div class="pagetitle">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h1><i class="bi <?= $app_meta['icone'] ?> me-2 <?= $app_meta['badge'] ?>"></i><?= $app_meta['label'] ?> — <span id="appModuleNom"><?= htmlspecialchars($module_courant['nom']) ?></span></h1>
      <span class="badge <?= $app_meta['badge'] ?> rounded-pill">Application pilotée par variables</span>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url($app_meta['route']) ?>"><?= $app_meta['label'] ?></a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($module_courant['nom']) ?></li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">
    <div class="row" id="kpiRow"></div>
    <div class="row" id="chartsRow"></div>
    <div id="moduleDetail"></div>
    <div class="row mt-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-sliders me-1"></i>Variables du module</h5>
            <p class="text-muted small mb-3">Ces variables pilotent les seuils et objectifs affichés ci-dessus (configuration de l'application, historique conservé à chaque modification).</p>
            <div id="variablesList" class="d-flex flex-column gap-2"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/variables_engine.js?v=<?= filemtime(FCPATH.'assets/js/variables_engine.js') ?>"></script>
<script src="<?= base_url() ?>assets/js/<?= $app_meta['js'] ?>?v=<?= filemtime(FCPATH.'assets/js/' . $app_meta['js']) ?>"></script>