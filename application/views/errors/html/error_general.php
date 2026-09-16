<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Erreur générale</title>
<link href="<?= config_item('base_url') . 'assets/vendor/bootstrap/css/bootstrap.min.css' ?>" rel="stylesheet">
<link href="<?= config_item('base_url') . 'assets/vendor/remixicon/remixicon.css' ?>" rel="stylesheet">
<link href="<?= config_item('base_url') . 'assets/css/style.css' ?>" rel="stylesheet">
</head>
<body>
<div class="min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center px-4" style="max-width: 800px;">
      <h1 class="display-1 fw-bold text-danger">500</h1>
      <h4 class="mb-3">Erreur interne</h4>
      <p class="text-secondary-light mb-4">Une erreur s'est produite lors du traitement de la requête.</p>
      <?php if (!empty($heading)): ?>
        <h5 class="text-dark fw-bold mb-2"><?php echo $heading; ?></h5>
      <?php endif; ?>
      <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-start p-3 mb-4 shadow-sm" style="max-height: 300px; overflow-y: auto;">
          <?php echo is_array($message) ? implode('<br>', $message) : $message; ?>
        </div>
      <?php endif; ?>
      <a href="<?= config_item('base_url') . 'Admin' ?>" class="btn btn-primary">
        <i class="ri-home-4-line me-1"></i> Retour à l'accueil
      </a>
    </div>
</div>
</body>
</html>
