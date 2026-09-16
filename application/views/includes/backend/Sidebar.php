<aside id="sidebar" class="sidebar">

  <div class="d-flex align-items-center justify-content-center px-3 py-3 border-bottom">
    <a href="<?= base_url('Dashboard') ?>" class="logo d-flex align-items-center w-auto">
      <img src="<?= base_url($this->Model->get_setting('logo_app', 'assets/img/logo.png')) ?>" alt="Logo" style="max-height:38px;">
      <span class="ms-2 fw-semibold" style="font-size:13px;"><?= htmlspecialchars($this->Model->get_setting('nom_app', 'Surveillance des maladies')) ?></span>
    </a>
  </div>

  <?php
  // Sidebar pilotée par la table `menus` (Administration > Menus).
  // Repli automatique sur la structure statique si aucun menu n'est enregistré.
  // Les sections Administration / Paramètres ne sont visibles que par les
  // administrateurs (les permissions sont de toute façon vérifiées côté serveur).
  $is_admin = ((int) $this->session->userdata('id_role') === 1);
  $menus_query = $this->db->where('parent_id', null)
      ->order_by('ordre', 'ASC')->get('menus');
  $menus_parents = ($menus_query !== false) ? $menus_query->result_array() : array();
  if ($is_admin) {
      // Bloc vide: l'admin voit tout.
  } else {
      $menus_parents = array_values(array_filter($menus_parents, function ($m) {
          $code = strtoupper($m['code'] ?? '');
          return !in_array($code, array('ADMINISTRATION', 'PARAMETRES'), TRUE);
      }));
  }
  $menus_children = array();
  if (!empty($menus_parents)) {
      $parents_ids = array_column($menus_parents, 'id_menu');
      if (!empty($parents_ids)) {
          $this->db->where_in('parent_id', $parents_ids);
          $this->db->order_by('parent_id', 'ASC')->order_by('ordre', 'ASC');
          $children_query = $this->db->get('menus');
          $menus_children = ($children_query !== false) ? $children_query->result_array() : array();
      }
  }
  $logout_btn = '<form method="post" action="' . base_url('Logout') . '" class="m-0 p-0">'
      . '<input type="hidden" name="csrf_token" value="' . $this->security->get_csrf_hash() . '">'
      . '<button type="submit" class="nav-link w-100 border-0 bg-transparent text-start ps-4"><i class="bi bi-box-arrow-right"></i><span>Déconnexion</span></button>'
      . '</form>';
  ?>

  <?php if (empty($menus_parents)) : ?>

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('Dashboard') ?>">
        <i class="bi bi-grid"></i><span>Dashboard</span>
      </a>
    </li>

    <?php if ($is_admin) : ?>
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#administration-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-shield-lock"></i><span>Administration</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="administration-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="<?= base_url('Menus') ?>"><i class="bi bi-circle"></i><span>Menus</span></a></li>
        <li><a href="<?= base_url('Roles') ?>"><i class="bi bi-circle"></i><span>Rôles</span></a></li>
      </ul>
    </li>
    <?php endif; ?>

    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('Profile') ?>">
        <i class="bi bi-person"></i>
        <span>Mon profil</span>
      </a>
    </li>

    <li class="nav-item">
      <?= $logout_btn ?>
    </li>

  </ul>

  <?php else : ?>

  <?php
  // Regroupement des enfants par menu parent (id stable même après édition).
  $children_by_parent = array();
  foreach ($menus_children as $child) {
      $children_by_parent[$child['parent_id']][] = $child;
  }
  $i = 0;
  ?>

  <ul class="sidebar-nav" id="sidebar-nav">

    <?php foreach ($menus_parents as $parent) : $i++; ?>
    <?php
      $children = $children_by_parent[$parent['id_menu']] ?? array();
      $slug = preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($parent['code']));
      $icon = !empty($parent['icon']) ? $parent['icon'] : 'bi-folder';
    ?>
    <?php if (empty($children)) : ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= base_url($parent['route']) ?>">
        <i class="bi <?= htmlspecialchars($icon) ?>"></i>
        <span><?= htmlspecialchars($parent['libelle']) ?></span>
      </a>
    </li>
    <?php else : ?>
    <li class="nav-item">
      <a class="nav-link<?= $i > 1 ? ' collapsed' : '' ?>" data-bs-target="#<?= $slug ?>-nav" data-bs-toggle="collapse" href="#">
        <i class="bi <?= htmlspecialchars($icon) ?>"></i><span><?= htmlspecialchars($parent['libelle']) ?></span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="<?= $slug ?>-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <?php foreach ($children as $child) : ?>
        <li><a href="<?= base_url($child['route']) ?>"><i class="bi bi-circle"></i><span><?= htmlspecialchars($child['libelle']) ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>

    <li class="nav-item">
      <a class="nav-link" href="<?= base_url('Profile') ?>">
        <i class="bi bi-person"></i>
        <span>Mon profil</span>
      </a>
    </li>

    <li class="nav-item">
      <?= $logout_btn ?>
    </li>

  </ul>

  <?php endif; ?>

</aside>
