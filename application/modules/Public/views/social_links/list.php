<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<main id="main" class="main">
  <div class="pagetitle"><h1>Social Links</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Home</a></li><li class="breadcrumb-item">Public</li><li class="breadcrumb-item active">Social Links</li></ol></nav>
  </div>
  <section class="section"><div class="col-lg-12"><div class="card"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center pt-3">
      <h5 class="card-title mb-0">Social Links <span id="tableCount" class="badge bg-light text-primary border border-primary fw-normal ms-1"></span></h5>
    </div>
    <div class="table-responsive mt-3">
      <table class="table table-hover align-middle mb-0"><thead class="table-light"><tr>
        <th>#</th><th>Platform</th><th>Label</th><th>URL</th><th>Status</th><th>Actions</th>
      </tr></thead><tbody id="tableBody"></tbody></table>
      <div id="tableEmpty" class="text-center text-muted py-5 d-none"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No links yet.</div>
      <div id="tableLoading" class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2"></span><span class="text-muted">Loading...</span></div>
      <div class="d-flex justify-content-between align-items-center gap-2 pt-3" id="tableFooter"><span id="tableInfo" class="text-muted small"></span><nav><ul class="pagination pagination-sm mb-0" id="tblPager"></ul></nav></div>
    </div>
  </div></div></div></section>
</main>
<?php include VIEWPATH.'includes/backend/Footer.php'; ?>
<script src="<?= base_url() ?>assets/js/localisation-table.js"></script>
<script>
let rows=[], gt=null;
function renderRow(r, num) {
  const badge = r.is_active == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
  return '<tr><td class="text-muted">'+num+'</td><td>'+API.esc(r.platform)+'</td><td>'+API.esc(r.label)+'</td><td><a href="'+API.esc(r.url)+'" target="_blank" class="text-primary">'+API.esc(r.url)+'</a></td><td>'+badge+'</td><td class="text-nowrap"><button class="btn btn-sm btn-outline-primary me-1" onclick="edit('+r.id+')"><i class="bi bi-pencil"></i></button><button class="btn btn-sm btn-outline-danger" onclick="remove('+r.id+')"><i class="bi bi-trash"></i></button></td></tr>';
}
function searchTerms(r){return [r.platform,r.label,r.url].join(' ');}
async function loadTable(){const res=await API.get('SocialLinks/api_list');if(!res.success){API.simpleAlert('error','Erreur',res.message);return;}rows=res.data.data||[];gt.setRows(rows);}
function edit(id){const r=rows.find(x=>x.id==id);if(!r)return;showForm(r);}
function showForm(r=null){
  const title = r ? 'Edit Link' : 'Add Link';
  const html = '<form id="modalForm"><div class="mb-3"><label class="form-label">Platform *</label><select class="form-select" id="m_platform" required><option value="linkedin">LinkedIn</option><option value="youtube">YouTube</option><option value="facebook">Facebook</option><option value="twitter">Twitter</option><option value="instagram">Instagram</option><option value="tiktok">TikTok</option><option value="other">Other</option></select></div><div class="mb-3"><label class="form-label">Label</label><input type="text" class="form-control" id="m_label" value="'+API.esc(r?r.label:'')+'"></div><div class="mb-3"><label class="form-label">URL *</label><input type="url" class="form-control" id="m_url" value="'+API.esc(r?r.url:'')+'" required></div><div class="mb-3"><label class="form-label">Order</label><input type="number" class="form-control" id="m_order" value="'+(r?r.display_order:0)+'"></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" id="m_active"><option value="1"'+(r&&r.is_active==0?'':' selected')+'>Active</option><option value="0"'+(r&&r.is_active==0?' selected')+'>Inactive</option></select></div></form>';
  API.confirmForm(title, html, async function(){
    const data = {platform:document.getElementById('m_platform').value,label:document.getElementById('m_label').value,url:document.getElementById('m_url').value,display_order:parseInt(document.getElementById('m_order').value),is_active:parseInt(document.getElementById('m_active').value)};
    const res = r ? await API.post('SocialLinks/api_update/'+r.id, data) : await API.post('SocialLinks/api_create', data);
    if(res.success){API.simpleAlert('success','Succès',res.message);loadTable();}else API.simpleAlert('error','Erreur',res.message);
  });
  if(r)document.getElementById('m_platform').value=r.platform;
}
async function remove(id){const c=await API.confirm('Supprimer','Supprimer ce lien ?');if(!c.isConfirmed)return;const res=await API.post('SocialLinks/api_delete/'+id,{});if(res.success){await loadTable();API.simpleAlert('success','Succès',res.message);}else API.simpleAlert('error','Erreur',res.message);}
gt=new GeoTable({renderRow,searchTerms,count:t=>t+(t>1?' liens':' lien')});
loadTable();
</script>
