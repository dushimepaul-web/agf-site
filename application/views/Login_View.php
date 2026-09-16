
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Connexion — <?= htmlspecialchars($nom_ecole ?? 'Surveillance des maladies') ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Open Graph / WhatsApp Preview -->
  <meta property="og:title" content="Connexion — <?= htmlspecialchars($nom_ecole ?? 'Surveillance des maladies') ?>">
  <meta property="og:description" content="Plateforme de surveillance épidémiologique et sanitaire">
  <meta property="og:image" content="<?= base_url($logo ?? 'assets/img/logo.png') ?>">
  <meta property="og:type" content="website">

  <link href="<?= base_url($logo ?? 'assets/img/favicon.png') ?>" rel="icon">
  <link href="<?= base_url($logo ?? 'assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="<?= base_url()?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url()?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url()?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?= base_url()?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">

  <link href="<?= base_url()?>assets/css/style.css" rel="stylesheet">
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="<?= base_url('Admin') ?>" class="logo d-flex align-items-center w-auto">
                  <img src="<?= base_url($logo ?? 'assets/images/logo.png') ?>" alt="Logo">
                  <span class="d-none d-lg-block"><?= htmlspecialchars($nom_ecole ?? 'Surveillance des maladies') ?></span>
                </a>
              </div>

              <?php if (!empty($login_img)): ?>
              <div class="mb-3 text-center">
                <img src="<?= base_url($login_img) ?>" alt="Image de connexion" class="img-fluid rounded-3" style="max-height:120px; object-fit:cover;">
              </div>
              <?php endif; ?>

              <div class="card mb-3 w-100">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center fs-4">Connexion à votre compte</h5>
                    <p class="text-center small">Saisissez votre email et votre mot de passe</p>
                  </div>

<?php if ($this->session->flashdata('sms')) { echo $this->session->flashdata('sms'); } ?>
                  <form class="row g-3 needs-validation" novalidate action="<?= base_url('Admin/do_login') ?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" id="yourUsername" required autofocus>
                        <div class="invalid-feedback">Veuillez saisir votre email.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Mot de passe</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Veuillez saisir votre mot de passe !</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Se connecter</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0 text-center">Pas encore de compte ? <a href="<?= base_url('Admin/register') ?>">Créer un compte</a></p>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="<?= base_url()?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
