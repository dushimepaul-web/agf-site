<?php include VIEWPATH.'includes/frontend/Header.php'; ?>

<style>
.ud-hero{position:relative;background-size:cover!important;background-position:center!important;background-repeat:no-repeat!important;padding:140px 0 80px;display:flex;align-items:center;justify-content:center;text-align:center;z-index:1}
.ud-hero::before{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(11,28,57,.75);z-index:-1}
.ud-hero-content{position:relative;z-index:1}
.ud-hero-title{font-family:'Yantramanav',sans-serif;font-size:56px!important;font-weight:800!important;color:#fff!important;margin-bottom:10px;line-height:1.1;text-shadow:0 2px 10px rgba(0,0,0,.3)}
.ud-hero-title,.ud-hero-title span,.ud-hero-content h1,.ud-hero-content h1 span,div.ud-hero .ud-hero-title,div.ud-hero .ud-hero-content h1{color:#fff!important}
h1.ud-hero-title{color:#fff!important}
.ud-hero-slogan{color:rgba(255,255,255,.85)!important;font-size:18px;font-style:italic;margin-bottom:25px}
.ud-hero-btn{display:inline-flex;align-items:center;gap:8px;background:#dcbb07;color:#fff!important;padding:14px 28px;border-radius:50px 50px 50px 0;font-weight:600;font-size:14px;text-decoration:none;transition:all .4s ease;border:none;cursor:pointer;margin:0 6px 8px}
.ud-hero-btn.alt{background:#B8902F}
.ud-hero-btn:hover{background:#116E63;color:#fff!important;transform:translateY(-3px);box-shadow:0 10px 25px rgba(17,110,99,.4)}
.agf-main{font-family:'Roboto',sans-serif!important;padding-top:0!important;margin-top:0!important}
.agf-main h1,.agf-main h2,.agf-main h3,.agf-main h4{font-family:'Yantramanav',sans-serif!important;color:#19232B!important;font-weight:600;line-height:1.2}
.agf-main p{color:#757F95;line-height:1.8}
.agf-sh{margin-bottom:50px;position:relative;z-index:1}
.agf-sh h2{font-weight:800;text-transform:capitalize;font-size:48px;color:#19232B!important;margin-bottom:0}
.agf-sh h2 span{color:#dcbb07!important}
.agf-sh p{margin-top:15px}

/* Wizard */
.broker-wizard{padding:60px 0 80px;background:#F2F3F5}
.broker-wizard-box{max-width:860px;margin:0 auto}
.wizard-progress{display:flex;align-items:center;justify-content:center;margin-bottom:40px;position:relative;padding:0 10px}
.wizard-step{display:flex;flex-direction:column;align-items:center;position:relative;z-index:2;flex:0 0 auto}
.wizard-step-dot{width:44px;height:44px;border-radius:50%;background:#e2e8f0;border:3px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#94a3b8;transition:all .4s ease;position:relative}
.wizard-step.active .wizard-step-dot{background:#116E63;border-color:#116E63;color:#fff;box-shadow:0 0 0 4px rgba(17,110,99,.2)}
.wizard-step.done .wizard-step-dot{background:#116E63;border-color:#116E63;color:#fff}
.wizard-step.done .wizard-step-dot::after{content:"\f00c";font-family:"Font Awesome 6 Free";font-weight:900;font-size:16px}
.wizard-step-dot span{display:block}
.wizard-step.done .wizard-step-dot span{display:none}
.wizard-step-label{font-size:11px;font-weight:600;color:#94a3b8;margin-top:8px;white-space:nowrap;transition:color .3s}
.wizard-step.active .wizard-step-label,.wizard-step.done .wizard-step-label{color:#19232B}
.wizard-line{flex:1;height:3px;background:#e2e8f0;position:relative;z-index:1;margin:0 -4px;margin-top:-28px}
.wizard-line.done{background:#116E63}

.wizard-card{background:#fff;border-radius:16px;box-shadow:0 5px 30px rgba(0,0,0,.06);padding:40px;margin-bottom:0;border:1px solid #e8ecf1;display:none}
.wizard-card.active{display:block;animation:wizardFadeIn .4s ease}
@keyframes wizardFadeIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.wizard-card h3{font-size:20px;font-weight:700;color:#19232B!important;margin-bottom:6px;display:flex;align-items:center;gap:10px}
.wizard-card h3 i{color:#116E63;font-size:22px}
.wizard-card .step-desc{font-size:14px;color:#757F95;margin-bottom:28px;padding-bottom:16px;border-bottom:2px solid #f1f5f9}

.form-row{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-bottom:18px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column}
.form-group label{font-size:13px;font-weight:600;color:#19232B;margin-bottom:5px}
.form-group label .req{color:#dc2626;font-weight:700;margin-left:2px}
.step-desc .req{color:#dc2626;font-weight:700}
.form-group input[type="text"],.form-group input[type="email"],.form-group input[type="tel"],.form-group input[type="url"],.form-group select,.form-group textarea{padding:11px 14px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;color:#19232B;background:#f8fafc;transition:all .3s ease;outline:none;width:100%}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#116E63;background:#fff;box-shadow:0 0 0 3px rgba(17,110,99,.1)}
.form-group textarea{min-height:90px;resize:vertical}
.form-group small{font-size:12px;color:#757F95;margin-top:3px}
.form-group.error input,.form-group.error select,.form-group.error textarea{border-color:#dc2626;background:#fef2f2}
.form-group .field-error{font-size:12px;color:#dc2626;margin-top:4px;display:none}
.form-group.error .field-error{display:block}

.chk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.chk-item{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:all .3s ease}
.chk-item:hover{border-color:#116E63;background:#f0fdf9}
.chk-item input[type="checkbox"]{width:18px;height:18px;accent-color:#116E63;cursor:pointer;flex-shrink:0}
.chk-item label{font-size:13px;font-weight:500;color:#19232B;cursor:pointer;margin:0}

.cfm-grid{display:grid;gap:12px}
.cfm-item{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:#f0fdf9;border:2px solid #d1fae5;border-radius:10px;transition:border-color .3s}
.cfm-item:hover{border-color:#116E63}
.cfm-item input[type="checkbox"]{width:18px;height:18px;accent-color:#116E63;cursor:pointer;margin-top:2px;flex-shrink:0}
.cfm-item label{font-size:13px;font-weight:500;color:#19232B;cursor:pointer;margin:0;line-height:1.5}
.cfm-item.error{border-color:#dc2626;background:#fef2f2}

.wizard-nav{display:flex;justify-content:space-between;align-items:center;margin-top:28px;padding-top:20px;border-top:2px solid #f1f5f9}
.wizard-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:50px;font-weight:600;font-size:14px;cursor:pointer;transition:all .3s ease;border:none;font-family:'Inter',sans-serif}
.wizard-btn-back{background:#f1f5f9;color:#64748b}
.wizard-btn-back:hover{background:#e2e8f0;color:#19232B}
.wizard-btn-next{background:#116E63;color:#fff}
.wizard-btn-next:hover{background:#0e5e54;transform:translateY(-2px);box-shadow:0 6px 18px rgba(17,110,99,.3)}
.wizard-btn-submit{background:#dcbb07;color:#fff}
.wizard-btn-submit:hover{background:#c4a506;transform:translateY(-2px);box-shadow:0 6px 18px rgba(220,187,7,.3)}
.wizard-btn-submit.loading{opacity:.7;pointer-events:none}
.wizard-btn-submit.loading i{animation:agfSpin 1s linear infinite}
@keyframes agfSpin{to{transform:rotate(360deg)}}
.wizard-btn i{font-size:14px}

.agf-toast-wrap{position:fixed!important;top:24px!important;right:24px!important;left:auto!important;bottom:auto!important;z-index:999999!important;display:flex!important;flex-direction:column;gap:12px;pointer-events:none}
.agf-toast{pointer-events:auto;min-width:300px;max-width:420px;padding:16px 20px;border-radius:12px;color:#fff!important;font-weight:600;font-size:14px;display:flex!important;align-items:center;gap:12px;box-shadow:0 10px 30px rgba(0,0,0,.25);opacity:0;visibility:hidden;transition:all .4s ease;font-family:'Inter',sans-serif}
.agf-toast.show{opacity:1!important;visibility:visible!important;transform:translateX(0)!important}
.agf-toast.hide{opacity:0!important;visibility:hidden!important;transform:translateX(120%)!important}
.agf-toast-success{background:#116E63}
.agf-toast-error{background:#dc2626}
.agf-toast-icon{font-size:20px;flex-shrink:0}
.agf-toast-msg{flex:1;line-height:1.4}
.agf-toast-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:18px;cursor:pointer;padding:0;flex-shrink:0;transition:color .2s}
.agf-toast-close:hover{color:#fff}

@media(max-width:768px){
  .wizard-progress{flex-wrap:wrap;gap:4px}
  .wizard-line{display:none}
  .wizard-step-label{font-size:9px}
  .wizard-step-dot{width:36px;height:36px;font-size:13px}
  .wizard-card{padding:24px 18px}
  .form-row{grid-template-columns:1fr}
  .chk-grid{grid-template-columns:1fr 1fr}
  .wizard-nav{flex-direction:column-reverse;gap:12px}
  .wizard-btn{width:100%;justify-content:center}
  .agf-sh h2{font-size:32px}
  .agf-toast{min-width:auto;max-width:calc(100vw - 48px)}
  .agf-toast-wrap{left:24px;right:24px}
}
@media(max-width:480px){
  .chk-grid{grid-template-columns:1fr}
  .wizard-step-label{display:none}
  .wizard-step-dot{width:32px;height:32px;font-size:12px}
}
/* ============ BREADCRUMB TABS : CREDIT SUMMARY / INVESTOR FORM ============ */
.cs-tabs{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;max-width:860px;margin:36px auto 0;padding:0 20px}
.cs-tabs a{display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;text-decoration:none;padding:11px 22px;border-radius:50px 50px 50px 0;border:2px solid #116E63;color:#116E63;background:#fff;transition:all .3s ease}
.cs-tabs a.on{background:#116E63;color:#fff}
.cs-tabs a:hover{background:#dcbb07;border-color:#dcbb07;color:#fff}
.cs-intro{max-width:860px;margin:0 auto;padding:24px 20px 0;text-align:center}
.cs-intro p{color:#757F95;line-height:1.8;text-align:center}
.cs-intro a{display:inline-flex;align-items:center;gap:8px;background:#B8902F;color:#fff!important;padding:13px 28px;border-radius:50px 50px 50px 0;font-weight:700;font-size:14px;text-decoration:none;transition:all .3s ease;margin-top:6px}
.cs-intro a:hover{background:#116E63;transform:translateY(-2px)}
@media(max-width:720px){ .cs-tabs a{font-size:13px;padding:9px 16px} }
</style><style>
.ud-hero{position:relative;background-size:cover!important;background-position:center!important;background-repeat:no-repeat!important;padding:140px 0 80px;display:flex;align-items:center;justify-content:center;text-align:center;z-index:1}
.ud-hero::before{content:"";position:absolute;left:0;top:0;width:100%;height:100%;background:rgba(11,28,57,.75);z-index:-1}
.ud-hero-content{position:relative;z-index:1}
.ud-hero-title{font-family:'Yantramanav',sans-serif;font-size:56px!important;font-weight:800!important;color:#fff!important;margin-bottom:10px;line-height:1.1;text-shadow:0 2px 10px rgba(0,0,0,.3)}
.ud-hero-title,.ud-hero-title span,.ud-hero-content h1,.ud-hero-content h1 span,div.ud-hero .ud-hero-title,div.ud-hero .ud-hero-content h1{color:#fff!important}
h1.ud-hero-title{color:#fff!important}
.ud-hero-slogan{color:rgba(255,255,255,.85)!important;font-size:18px;font-style:italic;margin-bottom:25px}
.ud-hero-btn{display:inline-flex;align-items:center;gap:8px;background:#dcbb07;color:#fff!important;padding:14px 28px;border-radius:50px 50px 50px 0;font-weight:600;font-size:14px;text-decoration:none;transition:all .4s ease;border:none;cursor:pointer;margin:0 6px 8px}
.ud-hero-btn.alt{background:#B8902F}
.ud-hero-btn:hover{background:#116E63;color:#fff!important;transform:translateY(-3px);box-shadow:0 10px 25px rgba(17,110,99,.4)}
.agf-main{font-family:'Roboto',sans-serif!important;padding-top:0!important;margin-top:0!important}
.agf-main h1,.agf-main h2,.agf-main h3,.agf-main h4{font-family:'Yantramanav',sans-serif!important;color:#19232B!important;font-weight:600;line-height:1.2}
.agf-main p{color:#757F95;line-height:1.8}
.agf-sh{margin-bottom:50px;position:relative;z-index:1}
.agf-sh h2{font-weight:800;text-transform:capitalize;font-size:48px;color:#19232B!important;margin-bottom:0}
.agf-sh h2 span{color:#dcbb07!important}
.agf-sh p{margin-top:15px}

/* Wizard */
.broker-wizard{padding:60px 0 80px;background:#F2F3F5}
.broker-wizard-box{max-width:860px;margin:0 auto}
.wizard-progress{display:flex;align-items:center;justify-content:center;margin-bottom:40px;position:relative;padding:0 10px}
.wizard-step{display:flex;flex-direction:column;align-items:center;position:relative;z-index:2;flex:0 0 auto}
.wizard-step-dot{width:44px;height:44px;border-radius:50%;background:#e2e8f0;border:3px solid #e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#94a3b8;transition:all .4s ease;position:relative}
.wizard-step.active .wizard-step-dot{background:#116E63;border-color:#116E63;color:#fff;box-shadow:0 0 0 4px rgba(17,110,99,.2)}
.wizard-step.done .wizard-step-dot{background:#116E63;border-color:#116E63;color:#fff}
.wizard-step.done .wizard-step-dot::after{content:"\f00c";font-family:"Font Awesome 6 Free";font-weight:900;font-size:16px}
.wizard-step-dot span{display:block}
.wizard-step.done .wizard-step-dot span{display:none}
.wizard-step-label{font-size:11px;font-weight:600;color:#94a3b8;margin-top:8px;white-space:nowrap;transition:color .3s}
.wizard-step.active .wizard-step-label,.wizard-step.done .wizard-step-label{color:#19232B}
.wizard-line{flex:1;height:3px;background:#e2e8f0;position:relative;z-index:1;margin:0 -4px;margin-top:-28px}
.wizard-line.done{background:#116E63}

.wizard-card{background:#fff;border-radius:16px;box-shadow:0 5px 30px rgba(0,0,0,.06);padding:40px;margin-bottom:0;border:1px solid #e8ecf1;display:none}
.wizard-card.active{display:block;animation:wizardFadeIn .4s ease}
@keyframes wizardFadeIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.wizard-card h3{font-size:20px;font-weight:700;color:#19232B!important;margin-bottom:6px;display:flex;align-items:center;gap:10px}
.wizard-card h3 i{color:#116E63;font-size:22px}
.wizard-card .step-desc{font-size:14px;color:#757F95;margin-bottom:28px;padding-bottom:16px;border-bottom:2px solid #f1f5f9}

.form-row{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-bottom:18px}
.form-row.single{grid-template-columns:1fr}
.form-group{display:flex;flex-direction:column}
.form-group label{font-size:13px;font-weight:600;color:#19232B;margin-bottom:5px}
.form-group label .req{color:#dc2626;font-weight:700;margin-left:2px}
.step-desc .req{color:#dc2626;font-weight:700}
.form-group input[type="text"],.form-group input[type="email"],.form-group input[type="tel"],.form-group input[type="url"],.form-group select,.form-group textarea{padding:11px 14px;border:2px solid #e2e8f0;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;color:#19232B;background:#f8fafc;transition:all .3s ease;outline:none;width:100%}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#116E63;background:#fff;box-shadow:0 0 0 3px rgba(17,110,99,.1)}
.form-group textarea{min-height:90px;resize:vertical}
.form-group small{font-size:12px;color:#757F95;margin-top:3px}
.form-group.error input,.form-group.error select,.form-group.error textarea{border-color:#dc2626;background:#fef2f2}
.form-group .field-error{font-size:12px;color:#dc2626;margin-top:4px;display:none}
.form-group.error .field-error{display:block}

.chk-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.chk-item{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:all .3s ease}
.chk-item:hover{border-color:#116E63;background:#f0fdf9}
.chk-item input[type="checkbox"]{width:18px;height:18px;accent-color:#116E63;cursor:pointer;flex-shrink:0}
.chk-item label{font-size:13px;font-weight:500;color:#19232B;cursor:pointer;margin:0}

.cfm-grid{display:grid;gap:12px}
.cfm-item{display:flex;align-items:flex-start;gap:10px;padding:14px 16px;background:#f0fdf9;border:2px solid #d1fae5;border-radius:10px;transition:border-color .3s}
.cfm-item:hover{border-color:#116E63}
.cfm-item input[type="checkbox"]{width:18px;height:18px;accent-color:#116E63;cursor:pointer;margin-top:2px;flex-shrink:0}
.cfm-item label{font-size:13px;font-weight:500;color:#19232B;cursor:pointer;margin:0;line-height:1.5}
.cfm-item.error{border-color:#dc2626;background:#fef2f2}

.wizard-nav{display:flex;justify-content:space-between;align-items:center;margin-top:28px;padding-top:20px;border-top:2px solid #f1f5f9}
.wizard-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:50px;font-weight:600;font-size:14px;cursor:pointer;transition:all .3s ease;border:none;font-family:'Inter',sans-serif}
.wizard-btn-back{background:#f1f5f9;color:#64748b}
.wizard-btn-back:hover{background:#e2e8f0;color:#19232B}
.wizard-btn-next{background:#116E63;color:#fff}
.wizard-btn-next:hover{background:#0e5e54;transform:translateY(-2px);box-shadow:0 6px 18px rgba(17,110,99,.3)}
.wizard-btn-submit{background:#dcbb07;color:#fff}
.wizard-btn-submit:hover{background:#c4a506;transform:translateY(-2px);box-shadow:0 6px 18px rgba(220,187,7,.3)}
.wizard-btn-submit.loading{opacity:.7;pointer-events:none}
.wizard-btn-submit.loading i{animation:agfSpin 1s linear infinite}
@keyframes agfSpin{to{transform:rotate(360deg)}}
.wizard-btn i{font-size:14px}

.agf-toast-wrap{position:fixed!important;top:24px!important;right:24px!important;left:auto!important;bottom:auto!important;z-index:999999!important;display:flex!important;flex-direction:column;gap:12px;pointer-events:none}
.agf-toast{pointer-events:auto;min-width:300px;max-width:420px;padding:16px 20px;border-radius:12px;color:#fff!important;font-weight:600;font-size:14px;display:flex!important;align-items:center;gap:12px;box-shadow:0 10px 30px rgba(0,0,0,.25);opacity:0;visibility:hidden;transition:all .4s ease;font-family:'Inter',sans-serif}
.agf-toast.show{opacity:1!important;visibility:visible!important;transform:translateX(0)!important}
.agf-toast.hide{opacity:0!important;visibility:hidden!important;transform:translateX(120%)!important}
.agf-toast-success{background:#116E63}
.agf-toast-error{background:#dc2626}
.agf-toast-icon{font-size:20px;flex-shrink:0}
.agf-toast-msg{flex:1;line-height:1.4}
.agf-toast-close{background:none;border:none;color:rgba(255,255,255,.7);font-size:18px;cursor:pointer;padding:0;flex-shrink:0;transition:color .2s}
.agf-toast-close:hover{color:#fff}

@media(max-width:768px){
  .wizard-progress{flex-wrap:wrap;gap:4px}
  .wizard-line{display:none}
  .wizard-step-label{font-size:9px}
  .wizard-step-dot{width:36px;height:36px;font-size:13px}
  .wizard-card{padding:24px 18px}
  .form-row{grid-template-columns:1fr}
  .chk-grid{grid-template-columns:1fr 1fr}
  .wizard-nav{flex-direction:column-reverse;gap:12px}
  .wizard-btn{width:100%;justify-content:center}
  .agf-sh h2{font-size:32px}
  .agf-toast{min-width:auto;max-width:calc(100vw - 48px)}
  .agf-toast-wrap{left:24px;right:24px}
}
@media(max-width:480px){
  .chk-grid{grid-template-columns:1fr}
  .wizard-step-label{display:none}
  .wizard-step-dot{width:32px;height:32px;font-size:12px}
}
</style><script>
/* CSRF fetch patch */
(function(){
  var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  if(!CSRF_HASH || window.__csrfFetchPatched) return;
  window.__csrfFetchPatched = true;
  var origFetch = window.fetch;
  window.fetch = function(url, opts){
    opts = opts || {};
    if((opts.method || 'GET').toUpperCase() === 'POST'){
      if(!opts.headers) opts.headers = {};
      if(typeof opts.headers.set === 'function' && !opts.headers.has(CSRF_NAME)){
        opts.headers.set(CSRF_NAME, CSRF_HASH);
      } else if(typeof opts.headers === 'object'){
        opts.headers[CSRF_NAME] = CSRF_HASH;
      }
    }
    return origFetch.call(this, url, opts);
  };
})();

(function(){
  var current = 1;
  var wrap = document.getElementById('agfToastWrap');

  window.agfToast = function(msg, type){
    type = type || 'success';
    var t = document.createElement('div');
    t.className = 'agf-toast agf-toast-' + type;
    var icon = type === 'success' ? '<i class="fas fa-check-circle agf-toast-icon"></i>' : '<i class="fas fa-exclamation-triangle agf-toast-icon"></i>';
    t.innerHTML = icon + '<span class="agf-toast-msg">' + escHtml(msg) + '</span><button class="agf-toast-close">&times;</button>';
    wrap.appendChild(t);
    requestAnimationFrame(function(){ t.classList.add('show'); });
    t.querySelector('.agf-toast-close').onclick = function(){ dismiss(t); };
    setTimeout(function(){ dismiss(t); }, 5000);
  };

  function dismiss(el){ el.classList.remove('show'); el.classList.add('hide'); setTimeout(function(){ el.remove(); }, 400); }
  function escHtml(s){ var d = document.createElement('div'); d.appendChild(document.createTextNode(s)); return d.innerHTML; }

  /* Country autocomplete */
  var countryInput = document.getElementById('countryInput');
  var countryId    = document.getElementById('countryId');
  var dropdown     = document.getElementById('countryDropdown');
  var searchTimer  = null;

  fetch('<?= base_url("api/detect-country") ?>')
    .then(function(r){ return r.json(); })
    .then(function(d){ if(d && d.id && d.pays){ countryInput.value = d.pays; countryId.value = d.id; } }).catch(function(){});

  countryInput.addEventListener('input', function(){
    var q = this.value.trim();
    countryId.value = '';
    if(q.length < 1){ dropdown.style.display = 'none'; return; }
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function(){
      fetch('<?= base_url("api/pays/search") ?>?q=' + encodeURIComponent(q))
        .then(function(r){ return r.json(); })
        .then(function(list){
          if(!list.length){ dropdown.style.display = 'none'; return; }
          var html = '';
          list.forEach(function(c){
            var flag = c.ISO_3166_1_2_Letter_Code ? '<span style="font-size:16px;margin-right:6px">' + isoToFlag(c.ISO_3166_1_2_Letter_Code) + '</span>' : '';
            html += '<div class="country-dd-item" data-id="' + c.id + '" data-name="' + escHtml(c.pays) + '" style="padding:10px 14px;cursor:pointer;display:flex;align-items:center;border-bottom:1px solid #f1f5f9;font-size:14px;transition:background .2s">' + flag + escHtml(c.pays) + '</div>';
          });
          dropdown.innerHTML = html; dropdown.style.display = 'block';
          dropdown.querySelectorAll('.country-dd-item').forEach(function(el){
            el.addEventListener('mouseenter', function(){ this.style.background = '#f0fdf9'; });
            el.addEventListener('mouseleave', function(){ this.style.background = '#fff'; });
            el.addEventListener('click', function(){ countryInput.value = this.dataset.name; countryId.value = this.dataset.id; dropdown.style.display = 'none'; });
          });
        }).catch(function(){ dropdown.style.display = 'none'; });
    }, 250);
  });

  document.addEventListener('click', function(e){ if(!countryInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none'; });
  function isoToFlag(code){ if(!code || code.length !== 2) return ''; var a = 0x1F1E6 - 65 + code.charCodeAt(0); var b = 0x1F1E6 - 65 + code.charCodeAt(1); return String.fromCodePoint(a) + String.fromCodePoint(b); }

  /* Validate */
  function validateStep(step){
    var card = document.querySelector('[data-card="' + step + '"]');
    var valid = true;
    card.querySelectorAll('.form-group').forEach(function(g){ g.classList.remove('error'); });
    card.querySelectorAll('.cfm-item').forEach(function(c){ c.classList.remove('error'); });
    card.querySelectorAll('[data-required="1"]').forEach(function(el){
      var group = el.closest('.form-group') || el.closest('.cfm-item');
      if(!group) return;
      var val = el.type === 'checkbox' ? el.checked : el.value.trim();
      if(!val){ group.classList.add('error'); valid = false; }
    });
    var emailEl = card.querySelector('input[name="email"]');
    if(emailEl && emailEl.value.trim()){ if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value.trim())){ emailEl.closest('.form-group').classList.add('error'); valid = false; } }
    if(step === 3){ var any = false; card.querySelectorAll('input[name^="interest_"]').forEach(function(c){ if(c.checked) any = true; }); if(!any) valid = false; }
    if(step === 4){ var any2 = false; card.querySelectorAll('input[name^="focus_"]').forEach(function(c){ if(c.checked) any2 = true; }); if(!any2) valid = false; }
    if(!valid) agfToast('Please fill in all required fields.', 'error');
    return valid;
  }

  function updateProgress(step){
    document.querySelectorAll('.wizard-step').forEach(function(s){ var n = parseInt(s.dataset.step); s.classList.remove('active','done'); if(n < step) s.classList.add('done'); else if(n === step) s.classList.add('active'); });
    document.querySelectorAll('.wizard-line').forEach(function(l){ var n = parseInt(l.dataset.line); l.classList.toggle('done', n < step); });
  }

  window.wizardNext = function(step){
    if(!validateStep(step)) return;
    document.querySelector('[data-card="' + step + '"]').classList.remove('active');
    document.querySelector('[data-card="' + (step+1) + '"]').classList.add('active');
    current = step + 1; updateProgress(current);
    window.scrollTo({top: document.querySelector('.wizard-progress').offsetTop - 100, behavior:'smooth'});
  };

  window.wizardPrev = function(step){
    document.querySelector('[data-card="' + step + '"]').classList.remove('active');
    document.querySelector('[data-card="' + (step-1) + '"]').classList.add('active');
    current = step - 1; updateProgress(current);
    window.scrollTo({top: document.querySelector('.wizard-progress').offsetTop - 100, behavior:'smooth'});
  };

  window.submitForm = function(){
    if(!validateStep(6)) return;
    var btn = document.getElementById('submitBtn');
    btn.classList.add('loading'); btn.innerHTML = '<i class="fas fa-spinner"></i> Submitting...';
    var fd = new FormData(document.getElementById('investorForm'));
    fetch('<?= base_url("investor/store") ?>', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:fd })
    .then(function(r){ return r.json(); })
    .then(function(res){
      agfToast(res.message, res.status);
      if(res.status === 'success'){
        document.getElementById('investorForm').reset();
        countryInput.value = ''; countryId.value = '';
        current = 1;
        document.querySelectorAll('.wizard-card').forEach(function(c){ c.classList.remove('active'); });
        document.querySelector('[data-card="1"]').classList.add('active');
        updateProgress(1);
      }
    })
    .catch(function(){ agfToast('Network error. Please try again.', 'error'); })
    .finally(function(){ btn.classList.remove('loading'); btn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration'; });
  };

  document.getElementById('investorForm').addEventListener('input', function(e){ var g = e.target.closest('.form-group'); if(g) g.classList.remove('error'); });
  document.getElementById('investorForm').addEventListener('change', function(e){ if(e.target.type === 'checkbox'){ var w = e.target.closest('.cfm-item'); if(w) w.classList.remove('error'); } });
})();
</script>

<section class="ud-hero" style="background-image:url('<?= base_url('attachments/Parametres/slide_helo.jpg') ?>')">
  <div class="container ud-hero-content">
    <h1 class="ud-hero-title">Become an Investor</h1>
    <p class="ud-hero-slogan">Partner with A.G.F and invest in Africa's agro-industrial transformation</p>
    <a href="<?= base_url() ?>" class="ud-hero-btn"><i class="fas fa-home"></i> Back to Home</a>
    <a href="<?= base_url('credit-summary') ?>" class="ud-hero-btn alt"><i class="fas fa-file-invoice-dollar"></i> Executive Credit Summary</a>
  </div>
</section>

<main class="agf-main">

<!-- ============ TABS : SEPARATION MEMO CREDIT / FORMULAIRE INVESTISSEUR ============ -->
<div class="cs-tabs">
  <a href="<?= base_url('credit-summary') ?>"><i class="fas fa-file-invoice-dollar"></i> Executive Credit Summary</a>
  <a href="<?= base_url('investor') ?>" class="on"><i class="fas fa-user-plus"></i> Investor Registration</a>
</div>

<div class="cs-intro">
  <p>The full Senior Secured Development Financing Memorandum credit terms &mdash; Executive Credit Summary, Reconciled Allocation of Funds, Capital Deployment &amp; Disbursement Control, Debt Repayment Structure, Lender Security &amp; Credit Protection, Financial Model &amp; Downside Control and Lender Action Request &mdash; are presented on a dedicated page, separate from the registration form.</p>
  <a href="<?= base_url('credit-summary') ?>"><i class="fas fa-file-invoice-dollar"></i> Open Executive Credit Summary</a>
</div>

<section class="broker-wizard">
  <div class="container broker-wizard-box">

    <div class="agf-sh text-center">
      <h2>Investor <span>Registration</span></h2>
      <p>Complete each step to register as an A.G.F investor.</p>
    </div>

    <div class="wizard-progress" id="wizardProgress">
      <div class="wizard-step active" data-step="1"><div class="wizard-step-dot"><span>1</span></div><div class="wizard-step-label">Personal</div></div>
      <div class="wizard-line" data-line="1"></div>
      <div class="wizard-step" data-step="2"><div class="wizard-step-dot"><span>2</span></div><div class="wizard-step-label">Professional</div></div>
      <div class="wizard-line" data-line="2"></div>
      <div class="wizard-step" data-step="3"><div class="wizard-step-dot"><span>3</span></div><div class="wizard-step-label">Interests</div></div>
      <div class="wizard-line" data-line="3"></div>
      <div class="wizard-step" data-step="4"><div class="wizard-step-dot"><span>4</span></div><div class="wizard-step-label">Focus</div></div>
      <div class="wizard-line" data-line="4"></div>
      <div class="wizard-step" data-step="5"><div class="wizard-step-dot"><span>5</span></div><div class="wizard-step-label">Message</div></div>
      <div class="wizard-line" data-line="5"></div>
      <div class="wizard-step" data-step="6"><div class="wizard-step-dot"><span>6</span></div><div class="wizard-step-label">Confirm</div></div>
    </div>

    <form id="investorForm">
    <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

      <!-- STEP 1: Personal -->
      <div class="wizard-card active" data-card="1">
        <h3><i class="fas fa-user"></i> Personal Information</h3>
        <p class="step-desc">Your contact details.</p>
        <div class="form-row">
          <div class="form-group">
            <label>Full Name <span class="req">*</span></label>
            <input type="text" name="full_name" data-required="1" placeholder="e.g. John Doe" maxlength="150">
            <div class="field-error">Full name is required.</div>
          </div>
          <div class="form-group">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" name="email" data-required="1" placeholder="e.g. john@example.com" maxlength="150">
            <div class="field-error">Valid email is required.</div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Phone <span class="req">*</span></label>
            <input type="tel" name="phone" data-required="1" placeholder="e.g. +260 97 123 4567" maxlength="50">
            <div class="field-error">Phone is required.</div>
          </div>
          <div class="form-group" style="position:relative">
            <label>Country <span class="req">*</span></label>
            <input type="text" id="countryInput" placeholder="Start typing a country name..." autocomplete="off" style="appearance:auto;-webkit-appearance:auto;-moz-appearance:auto;cursor:text">
            <input type="hidden" name="id_pays" id="countryId" data-required="1">
            <div id="countryDropdown" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:2px solid #116E63;border-radius:0 0 10px 10px;max-height:220px;overflow-y:auto;z-index:100;box-shadow:0 8px 24px rgba(0,0,0,.12)"></div>
            <div class="field-error">Country is required.</div>
          </div>
        </div>
        <div class="wizard-nav">
          <div></div>
          <button type="button" class="wizard-btn wizard-btn-next" onclick="wizardNext(1)">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- STEP 2: Professional -->
      <div class="wizard-card" data-card="2">
        <h3><i class="fas fa-building"></i> Professional Information</h3>
        <p class="step-desc">Your organization and investment capacity.</p>
        <div class="form-row">
          <div class="form-group">
            <label>Organization <span class="req">*</span></label>
            <input type="text" name="organization" data-required="1" placeholder="e.g. ABC Capital" maxlength="150">
            <div class="field-error">Organization is required.</div>
          </div>
          <div class="form-group">
            <label>Position / Title <span class="req">*</span></label>
            <input type="text" name="position_title" data-required="1" placeholder="e.g. Managing Director" maxlength="150">
            <div class="field-error">Position is required.</div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Commitment Range <span class="req">*</span></label>
            <select name="commitment_range" data-required="1" autocomplete="off" style="appearance:auto;-webkit-appearance:auto;-moz-appearance:auto;cursor:pointer;background-image:none">
              <option value="">-- Select --</option>
              <option value="Below 250K">Below $250K</option>
              <option value="250K-1M">$250K - $1M</option>
              <option value="1M-5M">$1M - $5M</option>
              <option value="5M+">$5M+</option>
              <option value="To be discussed">To be discussed</option>
            </select>
            <div class="field-error">Commitment range is required.</div>
          </div>
          <div class="form-group">
            <label>Timeline</label>
            <select name="timeline" autocomplete="off" style="appearance:auto;-webkit-appearance:auto;-moz-appearance:auto;cursor:pointer;background-image:none">
              <option value="Exploratory">Exploratory</option>
              <option value="Immediate">Immediate</option>
              <option value="3-6 months">3-6 months</option>
              <option value="6-12 months">6-12 months</option>
            </select>
          </div>
        </div>
        <div class="wizard-nav">
          <button type="button" class="wizard-btn wizard-btn-back" onclick="wizardPrev(2)"><i class="fas fa-arrow-left"></i> Back</button>
          <button type="button" class="wizard-btn wizard-btn-next" onclick="wizardNext(2)">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- STEP 3: Investment Interests -->
      <div class="wizard-card" data-card="3">
        <h3><i class="fas fa-hand-holding-usd"></i> Investment Interests</h3>
        <p class="step-desc">Select at least one. <span class="req">*</span></p>
        <div class="chk-grid">
          <div class="chk-item"><input type="checkbox" id="int1" name="interest_equity" value="1"><label for="int1">Equity</label></div>
          <div class="chk-item"><input type="checkbox" id="int2" name="interest_debt" value="1"><label for="int2">Debt</label></div>
          <div class="chk-item"><input type="checkbox" id="int3" name="interest_blended_finance" value="1"><label for="int3">Blended Finance</label></div>
          <div class="chk-item"><input type="checkbox" id="int4" name="interest_grant" value="1"><label for="int4">Grant</label></div>
          <div class="chk-item"><input type="checkbox" id="int5" name="interest_strategic_partnership" value="1"><label for="int5">Strategic Partnership</label></div>
          <div class="chk-item"><input type="checkbox" id="int6" name="interest_technical_collaboration" value="1"><label for="int6">Technical Collaboration</label></div>
          <div class="chk-item"><input type="checkbox" id="int7" name="interest_offtake_distribution" value="1"><label for="int7">Offtake / Distribution</label></div>
        </div>
        <div class="form-row single" style="margin-top:18px">
          <div class="form-group">
            <label>Other Interest</label>
            <input type="text" name="interest_other" placeholder="Specify other interest..." maxlength="255">
          </div>
        </div>
        <div class="wizard-nav">
          <button type="button" class="wizard-btn wizard-btn-back" onclick="wizardPrev(3)"><i class="fas fa-arrow-left"></i> Back</button>
          <button type="button" class="wizard-btn wizard-btn-next" onclick="wizardNext(3)">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- STEP 4: Focus Areas -->
      <div class="wizard-card" data-card="4">
        <h3><i class="fas fa-bullseye"></i> Focus Areas</h3>
        <p class="step-desc">Select at least one. <span class="req">*</span></p>
        <div class="chk-grid">
          <div class="chk-item"><input type="checkbox" id="foc1" name="focus_research_lab" value="1"><label for="foc1">Research Lab (SERIQA)</label></div>
          <div class="chk-item"><input type="checkbox" id="foc2" name="focus_gmp_facility" value="1"><label for="foc2">GMP Facility</label></div>
          <div class="chk-item"><input type="checkbox" id="foc3" name="focus_botanical_plant" value="1"><label for="foc3">Functional Plant</label></div>
          <div class="chk-item"><input type="checkbox" id="foc4" name="focus_commercialization" value="1"><label for="foc4">Commercialization</label></div>
          <div class="chk-item"><input type="checkbox" id="foc5" name="focus_full_platform" value="1"><label for="foc5">Full Platform</label></div>
        </div>
        <div class="wizard-nav">
          <button type="button" class="wizard-btn wizard-btn-back" onclick="wizardPrev(4)"><i class="fas fa-arrow-left"></i> Back</button>
          <button type="button" class="wizard-btn wizard-btn-next" onclick="wizardNext(4)">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- STEP 5: Strategic Message -->
      <div class="wizard-card" data-card="5">
        <h3><i class="fas fa-comment-dots"></i> Strategic Message</h3>
        <p class="step-desc">Share any additional information about your investment vision (optional).</p>
        <div class="form-row single">
          <div class="form-group">
            <label>Your Message</label>
            <textarea name="strategic_message" placeholder="Tell us about your investment goals, expectations, or how you'd like to collaborate with A.G.F..." rows="6"></textarea>
          </div>
        </div>
        <div class="wizard-nav">
          <button type="button" class="wizard-btn wizard-btn-back" onclick="wizardPrev(5)"><i class="fas fa-arrow-left"></i> Back</button>
          <button type="button" class="wizard-btn wizard-btn-next" onclick="wizardNext(5)">Next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- STEP 6: Confirm -->
      <div class="wizard-card" data-card="6">
        <h3><i class="fas fa-check-double"></i> Confirm &amp; Submit</h3>
        <p class="step-desc">Both confirmations are required. <span class="req">*</span></p>
        <div class="cfm-grid">
          <div class="cfm-item" id="cfm1Wrap">
            <input type="checkbox" id="cfm1" name="agree_contact" value="1" data-required="1">
            <label for="cfm1">I agree to be contacted by A.G.F regarding this registration and potential investment opportunities.</label>
          </div>
          <div class="cfm-item" id="cfm2Wrap">
            <input type="checkbox" id="cfm2" name="non_binding_confirmation" value="1" data-required="1">
            <label for="cfm2">I understand that this registration is non-binding and does not constitute a commitment to invest.</label>
          </div>
        </div>
        <div class="wizard-nav">
          <button type="button" class="wizard-btn wizard-btn-back" onclick="wizardPrev(6)"><i class="fas fa-arrow-left"></i> Back</button>
          <button type="button" class="wizard-btn wizard-btn-submit" id="submitBtn" onclick="submitForm()"><i class="fas fa-paper-plane"></i> Submit Registration</button>
        </div>
      </div>

    </form>
  </div>
</section>
</main>

<!-- Toast -->
<div class="agf-toast-wrap" id="agfToastWrap"></div>

<script>
/* CSRF fetch patch */
(function(){
  var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
  var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
  if(!CSRF_HASH || window.__csrfFetchPatched) return;
  window.__csrfFetchPatched = true;
  var origFetch = window.fetch;
  window.fetch = function(url, opts){
    opts = opts || {};
    if((opts.method || 'GET').toUpperCase() === 'POST'){
      if(!opts.headers) opts.headers = {};
      if(typeof opts.headers.set === 'function' && !opts.headers.has(CSRF_NAME)){
        opts.headers.set(CSRF_NAME, CSRF_HASH);
      } else if(typeof opts.headers === 'object'){
        opts.headers[CSRF_NAME] = CSRF_HASH;
      }
    }
    return origFetch.call(this, url, opts);
  };
})();

(function(){
  var current = 1;
  var wrap = document.getElementById('agfToastWrap');

  window.agfToast = function(msg, type){
    type = type || 'success';
    var t = document.createElement('div');
    t.className = 'agf-toast agf-toast-' + type;
    var icon = type === 'success' ? '<i class="fas fa-check-circle agf-toast-icon"></i>' : '<i class="fas fa-exclamation-triangle agf-toast-icon"></i>';
    t.innerHTML = icon + '<span class="agf-toast-msg">' + escHtml(msg) + '</span><button class="agf-toast-close">&times;</button>';
    wrap.appendChild(t);
    requestAnimationFrame(function(){ t.classList.add('show'); });
    t.querySelector('.agf-toast-close').onclick = function(){ dismiss(t); };
    setTimeout(function(){ dismiss(t); }, 5000);
  };

  function dismiss(el){ el.classList.remove('show'); el.classList.add('hide'); setTimeout(function(){ el.remove(); }, 400); }
  function escHtml(s){ var d = document.createElement('div'); d.appendChild(document.createTextNode(s)); return d.innerHTML; }

  /* Country autocomplete */
  var countryInput = document.getElementById('countryInput');
  var countryId    = document.getElementById('countryId');
  var dropdown     = document.getElementById('countryDropdown');
  var searchTimer  = null;

  fetch('<?= base_url("api/detect-country") ?>')
    .then(function(r){ return r.json(); })
    .then(function(d){ if(d && d.id && d.pays){ countryInput.value = d.pays; countryId.value = d.id; } }).catch(function(){});

  countryInput.addEventListener('input', function(){
    var q = this.value.trim();
    countryId.value = '';
    if(q.length < 1){ dropdown.style.display = 'none'; return; }
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function(){
      fetch('<?= base_url("api/pays/search") ?>?q=' + encodeURIComponent(q))
        .then(function(r){ return r.json(); })
        .then(function(list){
          if(!list.length){ dropdown.style.display = 'none'; return; }
          var html = '';
          list.forEach(function(c){
            var flag = c.ISO_3166_1_2_Letter_Code ? '<span style="font-size:16px;margin-right:6px">' + isoToFlag(c.ISO_3166_1_2_Letter_Code) + '</span>' : '';
            html += '<div class="country-dd-item" data-id="' + c.id + '" data-name="' + escHtml(c.pays) + '" style="padding:10px 14px;cursor:pointer;display:flex;align-items:center;border-bottom:1px solid #f1f5f9;font-size:14px;transition:background .2s">' + flag + escHtml(c.pays) + '</div>';
          });
          dropdown.innerHTML = html; dropdown.style.display = 'block';
          dropdown.querySelectorAll('.country-dd-item').forEach(function(el){
            el.addEventListener('mouseenter', function(){ this.style.background = '#f0fdf9'; });
            el.addEventListener('mouseleave', function(){ this.style.background = '#fff'; });
            el.addEventListener('click', function(){ countryInput.value = this.dataset.name; countryId.value = this.dataset.id; dropdown.style.display = 'none'; });
          });
        }).catch(function(){ dropdown.style.display = 'none'; });
    }, 250);
  });

  document.addEventListener('click', function(e){ if(!countryInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = 'none'; });
  function isoToFlag(code){ if(!code || code.length !== 2) return ''; var a = 0x1F1E6 - 65 + code.charCodeAt(0); var b = 0x1F1E6 - 65 + code.charCodeAt(1); return String.fromCodePoint(a) + String.fromCodePoint(b); }

  /* Validate */
  function validateStep(step){
    var card = document.querySelector('[data-card="' + step + '"]');
    var valid = true;
    card.querySelectorAll('.form-group').forEach(function(g){ g.classList.remove('error'); });
    card.querySelectorAll('.cfm-item').forEach(function(c){ c.classList.remove('error'); });
    card.querySelectorAll('[data-required="1"]').forEach(function(el){
      var group = el.closest('.form-group') || el.closest('.cfm-item');
      if(!group) return;
      var val = el.type === 'checkbox' ? el.checked : el.value.trim();
      if(!val){ group.classList.add('error'); valid = false; }
    });
    var emailEl = card.querySelector('input[name="email"]');
    if(emailEl && emailEl.value.trim()){ if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value.trim())){ emailEl.closest('.form-group').classList.add('error'); valid = false; } }
    if(step === 3){ var any = false; card.querySelectorAll('input[name^="interest_"]').forEach(function(c){ if(c.checked) any = true; }); if(!any) valid = false; }
    if(step === 4){ var any2 = false; card.querySelectorAll('input[name^="focus_"]').forEach(function(c){ if(c.checked) any2 = true; }); if(!any2) valid = false; }
    if(!valid) agfToast('Please fill in all required fields.', 'error');
    return valid;
  }

  function updateProgress(step){
    document.querySelectorAll('.wizard-step').forEach(function(s){ var n = parseInt(s.dataset.step); s.classList.remove('active','done'); if(n < step) s.classList.add('done'); else if(n === step) s.classList.add('active'); });
    document.querySelectorAll('.wizard-line').forEach(function(l){ var n = parseInt(l.dataset.line); l.classList.toggle('done', n < step); });
  }

  window.wizardNext = function(step){
    if(!validateStep(step)) return;
    document.querySelector('[data-card="' + step + '"]').classList.remove('active');
    document.querySelector('[data-card="' + (step+1) + '"]').classList.add('active');
    current = step + 1; updateProgress(current);
    window.scrollTo({top: document.querySelector('.wizard-progress').offsetTop - 100, behavior:'smooth'});
  };

  window.wizardPrev = function(step){
    document.querySelector('[data-card="' + step + '"]').classList.remove('active');
    document.querySelector('[data-card="' + (step-1) + '"]').classList.add('active');
    current = step - 1; updateProgress(current);
    window.scrollTo({top: document.querySelector('.wizard-progress').offsetTop - 100, behavior:'smooth'});
  };

  window.submitForm = function(){
    if(!validateStep(6)) return;
    var btn = document.getElementById('submitBtn');
    btn.classList.add('loading'); btn.innerHTML = '<i class="fas fa-spinner"></i> Submitting...';
    var fd = new FormData(document.getElementById('investorForm'));
    fetch('<?= base_url("investor/store") ?>', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:fd })
    .then(function(r){ return r.json(); })
    .then(function(res){
      agfToast(res.message, res.status);
      if(res.status === 'success'){
        document.getElementById('investorForm').reset();
        countryInput.value = ''; countryId.value = '';
        current = 1;
        document.querySelectorAll('.wizard-card').forEach(function(c){ c.classList.remove('active'); });
        document.querySelector('[data-card="1"]').classList.add('active');
        updateProgress(1);
      }
    })
    .catch(function(){ agfToast('Network error. Please try again.', 'error'); })
    .finally(function(){ btn.classList.remove('loading'); btn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration'; });
  };

  document.getElementById('investorForm').addEventListener('input', function(e){ var g = e.target.closest('.form-group'); if(g) g.classList.remove('error'); });
  document.getElementById('investorForm').addEventListener('change', function(e){ if(e.target.type === 'checkbox'){ var w = e.target.closest('.cfm-item'); if(w) w.classList.remove('error'); } });
})();
</script>

<?php include VIEWPATH.'includes/frontend/Footer.php'; ?>
