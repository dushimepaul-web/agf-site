
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Créer un compte — <?= htmlspecialchars($nom_ecole ?? 'Surveillance des maladies') ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="<?= base_url()?>assets/img/favicon.png" rel="icon">
  <link href="<?= base_url()?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

              <div class="card mb-3 w-100">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center fs-4">Créer un compte</h5>
                    <p class="text-center small">Saisissez vos informations personnelles</p>
                  </div>

<?php if ($this->session->flashdata('sms')) { echo $this->session->flashdata('sms'); } ?>
                  <form class="row g-3 needs-validation" novalidate action="<?= base_url('Admin/do_register') ?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="col-12">
                      <label for="yourName" class="form-label">Nom complet</label>
                      <input type="text" name="nom_complet" class="form-control" id="yourName" required>
                      <div class="invalid-feedback">Veuillez saisir votre nom complet.</div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" id="yourEmail" required>
                      <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Mot de passe</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required minlength="6">
                      <div class="invalid-feedback">Veuillez saisir votre mot de passe (6 caractères minimum).</div>
                    </div>

                    <div class="col-12">
                      <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                      <input type="password" name="confirm_password" class="form-control" id="confirmPassword" required>
                      <div class="invalid-feedback">Veuillez confirmer votre mot de passe.</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Créer le compte</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0 text-center">Déjà un compte ? <a href="<?= base_url('Admin') ?>">Se connecter</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Plateforme de surveillance des maladies — Burundi
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
