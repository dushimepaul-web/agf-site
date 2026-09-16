<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1><?= $title ?></h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Home</a></li><li class="breadcrumb-item">Public</li><li class="breadcrumb-item"><a href="<?= base_url('Faq') ?>">FAQ</a></li><li class="breadcrumb-item active"><?= $faq ? 'Edit' : 'Add' ?></li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form id="faqForm">
      <input type="hidden" id="id" value="<?= $faq['id'] ?? '' ?>">
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-select" id="categorie" required>
          <option value="General" <?= ($faq['categorie'] ?? '') == 'General' ? 'selected' : '' ?>>General</option>
          <option value="Products" <?= ($faq['categorie'] ?? '') == 'Products' ? 'selected' : '' ?>>Products</option>
          <option value="Investment" <?= ($faq['categorie'] ?? '') == 'Investment' ? 'selected' : '' ?>>Investment</option>
          <option value="Logistics" <?= ($faq['categorie'] ?? '') == 'Logistics' ? 'selected' : '' ?>>Logistics</option>
          <option value="Other" <?= ($faq['categorie'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Question</label>
        <input type="text" class="form-control" id="question" value="<?= htmlspecialchars($faq['question'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Answer</label>
        <textarea class="form-control" id="reponse" rows="6" required><?= htmlspecialchars($faq['reponse'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-select" id="est_publiee">
          <option value="1" <?= ($faq['est_publiee'] ?? 1) == 1 ? 'selected' : '' ?>>Published</option>
          <option value="0" <?= ($faq['est_publiee'] ?? 0) == 0 ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>
      <div class="text-end mt-4">
        <a href="<?= base_url('Faq') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save</button>
      </div>
    </form>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script>
document.getElementById('faqForm').onsubmit = async function(e) {
  e.preventDefault();
  const id = document.getElementById('id').value;
  const data = {
    categorie: document.getElementById('categorie').value,
    question: document.getElementById('question').value,
    reponse: document.getElementById('reponse').value,
    est_publiee: parseInt(document.getElementById('est_publiee').value)
  };
  const res = id ? await API.post('Faq/api_update/'+id, data) : await API.post('Faq/api_create', data);
  if (res.success) { API.simpleAlert('success','Success',res.message); setTimeout(()=>location.href=BASE_URL+'Faq',1200); }
  else API.simpleAlert('error','Error',res.message);
};
</script>
