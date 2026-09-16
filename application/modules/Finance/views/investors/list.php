<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1>Investisseurs</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li><li class="breadcrumb-item">Finance</li><li class="breadcrumb-item active">Investisseurs</li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-12"><div class="card"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center pt-3">
      <h5 class="card-title mb-0">Investisseurs <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
      <a href="<?= base_url('Investors/add_edit') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajouter</a>
    </div>
    <div class="table-responsive mt-3">
      <table class="table table-hover align-middle mb-0"><thead class="table-light"><tr>
        <th>#</th><th>Nom</th><th>Organisation</th><th>Email</th><th>Engagement</th><th>Timeline</th><th>Actions</th>
      </tr></thead><tbody id="tableBody"></tbody></table>
      <div id="tableEmpty" class="text-center text-muted py-5 d-none"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun investisseur.</div>
      <div id="tableLoading" class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2"></span><span class="text-muted">Chargement...</span></div>
      <div class="d-flex justify-content-between align-items-center gap-2 pt-3" id="tableFooter"><span id="tableInfo" class="text-muted small"></span><nav><ul class="pagination pagination-sm mb-0" id="tblPager"></ul></nav></div>
    </div>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/localisation-table.js"></script>
<script>
let rows=[], gt=null;
function renderRow(r, num) {
  return '<tr><td class="text-muted">'+num+'</td><td>'+API.esc(r.full_name)+'</td><td>'+API.esc(r.organization||'')+'</td><td><a href="mailto:'+API.esc(r.email)+'">'+API.esc(r.email)+'</a></td><td>'+API.esc(r.commitment_range||'')+'</td><td>'+API.esc(r.timeline||'')+'</td><td class="text-nowrap"><a href="'+BASE_URL+'Investors/add_edit/'+r.id+'" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a><button class="btn btn-sm btn-outline-danger" onclick="remove('+r.id+')"><i class="bi bi-trash"></i></button></td></tr>';
}
function searchTerms(r){return [r.full_name,r.organization,r.email,r.strategic_message].join(' ');}
async function loadTable(){const res=await API.get('Investors/api_list');if(!res.success){API.simpleAlert('error','Erreur',res.message);return;}rows=res.data.data||[];gt.setRows(rows);}
async function remove(id){const c=await API.confirm('Supprimer','Supprimer cet investisseur ?');if(!c.isConfirmed)return;const res=await API.post('Investors/api_delete/'+id,{});if(res.success){await loadTable();API.simpleAlert('success','Succès',res.message);}else API.simpleAlert('error','Erreur',res.message);}
gt=new GeoTable({renderRow,searchTerms,count:t=>t+(t>1?' investisseurs':' investisseur')});
loadTable();
</script>
