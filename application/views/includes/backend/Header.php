<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= isset($title) ? htmlspecialchars($title) : htmlspecialchars($this->Model->get_setting('nom_app', 'Surveillance des maladies')) ?></title>
  <meta content="<?= htmlspecialchars($this->Model->get_setting('nom_app', 'Surveillance des maladies')) ?>" name="description">
  <meta content="" name="keywords">

  <!-- Open Graph / WhatsApp Preview -->
  <meta property="og:title" content="<?= isset($title) ? htmlspecialchars($title) : htmlspecialchars($this->Model->get_setting('nom_app', 'Surveillance des maladies')) ?>">
  <meta property="og:description" content="Plateforme de surveillance épidémiologique et sanitaire">
  <meta property="og:image" content="<?= base_url($this->Model->get_setting('logo_app', 'assets/img/logo.png')) ?>">
  <meta property="og:type" content="website">

  <link href="<?= base_url($this->Model->get_setting('favicon_app', 'assets/img/favicon.png')) ?>" rel="icon">
  <link href="<?= base_url($this->Model->get_setting('logo_app', 'assets/img/apple-touch-icon.png')) ?>" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">

  <script>
    if (typeof BASE_URL === 'undefined') {
      var BASE_URL = "<?= base_url() ?>";
    }
    if (typeof CSRF_TOKEN === 'undefined') {
      var CSRF_TOKEN = "<?= $this->security->get_csrf_hash() ?>";
    }
  </script>
  <script src="<?= base_url() ?>assets/js/api.js?v=<?= filemtime(FCPATH.'assets/js/api.js') ?>"></script>
</head>

<body>

  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= base_url('Dashboard') ?>" class="logo d-flex align-items-center">
        <img src="<?= base_url($this->Model->get_setting('logo_app', 'assets/img/logo.png')) ?>" alt="">
        <span class="d-none d-lg-block"><?= htmlspecialchars($this->Model->get_setting('nom_app', 'Surveillance des maladies')) ?></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-4"></i>
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= htmlspecialchars($this->session->userdata('nom_complet') ?: 'Utilisateur') ?></span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= htmlspecialchars($this->session->userdata('nom_complet') ?: '') ?></h6>
              <span><?= htmlspecialchars($this->session->userdata('role_libelle') ?: '') ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= base_url('Profile') ?>">
                <i class="bi bi-person"></i>
                <span>Mon profil</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form method="post" action="<?= base_url('Logout') ?>" class="m-0">
                <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">
                <button type="submit" class="dropdown-item d-flex align-items-center w-100 border-0 bg-transparent">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Déconnexion</span>
                </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

  </header>
