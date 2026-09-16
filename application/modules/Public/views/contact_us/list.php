<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1>Messages de contact</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li><li class="breadcrumb-item">Public</li><li class="breadcrumb-item active">Contact</li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-12"><div class="card"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center pt-3">
      <h5 class="card-title mb-0">Messages <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
    </div>
    <div class="table-responsive mt-3">
      <table class="table table-hover align-middle mb-0"><thead class="table-light"><tr>
        <th>#</th><th>Date</th><th>Nom</th><th>Email</th><th>Sujet</th><th>Lu</th><th>Actions</th>
      </tr></thead><tbody id="tableBody"></tbody></table>
      <div id="tableEmpty" class="text-center text-muted py-5 d-none"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun message.</div>
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
  const lu = r.is_readed == 1 ? '<i class="bi bi-envelope-open text-success"></i>' : '<i class="bi bi-envelope-fill text-primary"></i>';
  return '<tr><td class="text-muted">'+num+'</td><td class="text-nowrap">'+API.esc(r.Date_creation||'')+'</td><td>'+API.esc(r.FullName)+'</td><td><a href="mailto:'+API.esc(r.Email)+'">'+API.esc(r.Email)+'</a></td><td>'+API.esc(r.Subject)+'</td><td>'+lu+'</td><td class="text-nowrap"><button class="btn btn-sm btn-outline-primary me-1" onclick="viewMsg('+r.IdContact+')"><i class="bi bi-eye"></i></button><button class="btn btn-sm btn-outline-danger" onclick="remove('+r.IdContact+')"><i class="bi bi-trash"></i></button></td></tr>';
}
function searchTerms(r){return [r.FullName,r.Email,r.Subject,r.Message].join(' ');}
async function loadTable(){const res=await API.get('ContactUs/api_list');if(!res.success){API.simpleAlert('error','Erreur',res.message);return;}rows=res.data.data||[];gt.setRows(rows);}
function viewMsg(id){const r=rows.find(x=>x.IdContact==id);if(r){API.simpleAlert('info',r.FullName+' - '+r.Subject,r.Message);API.post('ContactUs/api_read/'+r.IdContact,{});}}
async function remove(id){const c=await API.confirm('Supprimer','Supprimer ce message ?');if(!c.isConfirmed)return;const res=await API.post('ContactUs/api_delete/'+id,{});if(res.success){await loadTable();API.simpleAlert('success','Succès',res.message);}else API.simpleAlert('error','Erreur',res.message);}
gt=new GeoTable({renderRow,searchTerms,count:t=>t+(t>1?' messages':' message')});
loadTable();
</script>
