<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>

<style>
.dash-loading { min-height: 320px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.75rem; }
#liveClock { font-variant-numeric: tabular-nums; }
.badge-soft { font-weight:500; }
.delta-up { color:#198754; } .delta-down { color:#dc3545; } .delta-flat { color:#6c757d; }
.mini-table td, .mini-table th { font-size:.85rem; padding:.5rem .6rem; vertical-align: middle; }
.insight-item { display:flex; gap:.85rem; padding:.6rem .25rem; border-bottom:1px dashed #e9ecef; }
.insight-item:last-child { border-bottom:0; }
.insight-ic { width:34px; height:34px; min-width:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#f6f6fe; color:#4154f1; }
.insight-title { font-weight:600; font-size:.88rem; }
.insight-text { font-size:.82rem; color:#5d6771; }
.apexcharts-tooltip { border:0 !important; box-shadow:0 6px 18px rgba(0,0,0,.12) !important; }
</style>

<main id="main" class="main">

  <div class="pagetitle">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h1><?= htmlspecialchars($title ?? 'Tableau de bord') ?></h1>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="text-muted small"><i class="bi bi-clock me-1"></i><span id="liveClock">--:--:--</span></span>
        <div class="btn-group btn-group-sm" role="group" aria-label="Période">
          <button type="button" class="btn btn-outline-secondary" data-days="7">7 jours</button>
          <button type="button" class="btn btn-success" data-days="30">30 jours</button>
          <button type="button" class="btn btn-outline-secondary" data-days="90">90 jours</button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btnRefresh" title="Rafraîchir"><i class="bi bi-arrow-clockwise"></i></button>
      </div>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Accueil</a></li>
        <li class="breadcrumb-item active">Tableau de bord</li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">

    <div id="dashLoading" class="dash-loading">
      <div class="spinner-border text-success" role="status"></div>
      <div class="text-muted small">Chargement des données...</div>
    </div>

    <div id="dashContent" style="display:none;">
      <div class="row">

        <!-- ====== KPI ====== -->
        <div class="col-12">
          <div class="row g-3" id="kpiRow"></div>
        </div>

        <!-- ====== Courbe de trafic ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="card-title mb-0">Trafic du site (visites par jour)</h5>
                <span class="badge bg-light text-success border badge-soft" id="visitsSummary"></span>
              </div>
              <div style="height:340px; position:relative;"><div id="chartTraffic"></div></div>
            </div>
          </div>
        </div>

        <!-- ====== Répartitions ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Répartitions</h5>
              <div class="row">
                <div class="col-md-4 text-center">
                  <div class="text-muted small mb-1">Consultations</div>
                  <div id="chartConsultStatut" style="height:200px;"></div>
                </div>
                <div class="col-md-4 text-center">
                  <div class="text-muted small mb-1">Paiements</div>
                  <div id="chartPaiement" style="height:200px;"></div>
                </div>
                <div class="col-md-4 text-center">
                  <div class="text-muted small mb-1">Appareils</div>
                  <div id="chartDevices" style="height:200px;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ====== Heures de pointe ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Fréquentation par heure</h5>
              <div style="height:300px; position:relative;"><canvas id="chartHours"></canvas></div>
            </div>
          </div>
        </div>

        <!-- ====== Pages les plus visitées ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pages les plus visitées</h5>
              <div style="height:300px; position:relative;"><div id="chartPages"></div></div>
            </div>
          </div>
        </div>

        <!-- ====== Analyse automatique ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Analyse automatique</h5>
              <div id="insightsList" class="small"></div>
            </div>
          </div>
        </div>

        <!-- ====== Catalogue ====== -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Catalogue produits</h5>
              <div style="height:250px; position:relative;"><div id="chartCatalog"></div></div>
              <div class="small text-muted mt-1 text-center" id="catalogMeta"></div>
            </div>
          </div>
        </div>

        <!-- ====== Dernières consultations ====== -->
        <div class="col-lg-7">
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h5 class="card-title">Dernières consultations</h5>
              <table class="table table-borderless mini-table">
                <thead>
                  <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Patient</th>
                    <th scope="col">Spécialité</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Paiement</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>
                <tbody id="tblConsult">
                  <tr><td colspan="6" class="text-center text-muted">Chargement...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ====== Pipeline investisseurs ====== -->
        <div class="col-lg-5">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pipeline investisseurs &amp; courtiers</h5>
              <div id="leadsList" class="small"></div>
            </div>
          </div>
        </div>

        <!-- ====== Messages ====== -->
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Messages reçus <span class="badge bg-danger" id="unreadBadge">0</span></h5>
              <div id="contactsList" class="small"></div>
            </div>
          </div>
        </div>

        <!-- ====== Journal d'audit ====== -->
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Journal d'audit</h5>
              <div id="logsList" class="small text-muted">Aucune activité journalisée pour le moment.</div>
            </div>
          </div>
        </div>

        <!-- ====== Utilisateurs ====== -->
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Utilisateurs</h5>
              <div id="usersList" class="small"></div>
              <hr class="my-2">
              <div class="d-flex justify-content-around text-center small" id="ecoStats"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>

<script>
(function () {
  'use strict';

  const fmt = new Intl.NumberFormat('fr-FR');
  const fmtMoney = new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
  let currentDays = 30;
  const charts = {};

  /* ---------- horloge ---------- */
  setInterval(function () {
    const el = document.getElementById('liveClock');
    if (el) el.textContent = new Date().toLocaleTimeString('fr-FR');
  }, 1000);

  /* ---------- helpers ---------- */
  function esc(s) { return API.esc ? API.esc(s == null ? '' : String(s)) : String(s == null ? '' : s); }
  function deltaBadge(d) {
    if (d === null || d === undefined) return '';
    const v = parseFloat(d);
    if (v > 0) return '<span class="delta-up fw-semibold"><i class="bi bi-arrow-up-right"></i> ' + v + '%</span>';
    if (v < 0) return '<span class="delta-down fw-semibold"><i class="bi bi-arrow-down-right"></i> ' + Math.abs(v) + '%</span>';
    return '<span class="delta-flat fw-semibold">= 0%</span>';
  }
  function animateValue(el, target, prefix, suffix) {
    prefix = prefix || ''; suffix = suffix || '';
    const dur = 700, t0 = performance.now();
    function frame(t) {
      const p = Math.min(1, (t - t0) / dur);
      const eased = 1 - Math.pow(1 - p, 3);
      el.textContent = prefix + fmt.format(Math.round(target * eased)) + suffix;
      if (p < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  function statutBadge(s) {
    const map = { en_attente: ['bg-warning text-dark', 'En attente'], en_cours: ['bg-info text-dark', 'En cours'], terminee: ['bg-success', 'Terminée'], annulee: ['bg-secondary', 'Annulée'] };
    const m = map[s] || ['bg-secondary', esc(s)];
    return '<span class="badge ' + m[0] + ' badge-soft">' + m[1] + '</span>';
  }
  function paiementBadge(s) {
    const map = { paye: ['bg-success', 'Payé'], en_attente: ['bg-warning text-dark', 'En attente'], annule: ['bg-secondary', 'Annulé'] };
    const m = map[s] || ['bg-secondary', esc(s)];
    return '<span class="badge ' + m[0] + ' badge-soft">' + m[1] + '</span>';
  }
  function destroyChart(id) { if (charts[id]) { try { charts[id].destroy(); } catch (e) {} delete charts[id]; } }
  function noData(el, msg) {
    const node = document.querySelector(el);
    if (!node) return;
    destroyChart(String(el).replace('#', ''));
    node.innerHTML = '<div class="text-muted text-center py-5">' + (msg || 'Aucune donnée sur la période') + '</div>';
  }
  function hasNums(arr) { return Array.isArray(arr) && arr.some(function (v) { return Number(v) > 0; }); }

  /* ---------- palette AGF ---------- */
  const GREEN = '#2e7d32', GREEN2 = '#43a047', GREEN3 = '#66bb6a', GREEN4 = '#a5d6a7';

  /* ---------- KPIs (style info-card NiceAdmin) ---------- */
  function renderKpis(k) {
    const items = [
      { cls: 'sales-card', icon: 'bi-eye', label: 'Visites (' + currentDays + 'j)', value: k.visites.value, delta: k.visites.delta, sub: k.visites.today + " aujourd'hui" },
      { cls: 'revenue-card', icon: 'bi-clipboard2-pulse', label: 'Consultations', value: k.consultations.value, delta: k.consultations.delta, sub: k.consultations.pending + ' en attente · ' + k.consultations.done + ' terminée(s)' },
      { cls: 'customers-card', icon: 'bi-funnel', label: 'Pipeline (Inv. + Courtiers)', value: k.leads.value, delta: k.leads.delta, sub: k.leads.investors + ' investisseurs · ' + k.leads.brokers + ' courtiers' },
      { cls: 'sales-card', icon: 'bi-cash-stack', label: 'Encaissé (' + k.revenus.currency + ')', value: k.revenus.paid, money: true, delta: null, sub: fmtMoney.format(k.revenus.pending) + ' ' + k.revenus.currency + ' en attente' },
      { cls: 'revenue-card', icon: 'bi-envelope', label: 'Messages non lus', value: k.messages.unread, delta: null, sub: k.messages.total + ' au total', alert: k.messages.unread > 0 },
      { cls: 'customers-card', icon: 'bi-people', label: 'Utilisateurs actifs', value: k.utilisateurs.active, delta: null, sub: k.utilisateurs.online + ' en ligne (< 30 min)' }
    ];
    let html = '';
    items.forEach(function (it) {
      const badge = it.alert ? '<span class="badge bg-danger rounded-pill px-2">!</span>' : deltaBadge(it.delta);
      html += '<div class="col-xxl-3 col-md-6 col-lg-4">' +
        '<div class="card info-card ' + it.cls + '">' +
        '<div class="card-body">' +
        '<h5 class="card-title">' + esc(it.label) + '</h5>' +
        '<div class="d-flex align-items-center">' +
        '<div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i class="bi ' + it.icon + '"></i></div>' +
        '<div class="ps-3">' +
        '<h6 class="kpi-value" data-target="' + it.value + '" data-money="' + (it.money ? 1 : 0) + '">0</h6>' +
        '<span class="text-muted small pt-2 ps-1 d-block">' + esc(it.sub) + ' ' + badge + '</span>' +
        '</div></div></div></div></div>';
    });
    document.getElementById('kpiRow').innerHTML = html;
    document.querySelectorAll('.kpi-value').forEach(function (el) {
      animateValue(el, parseFloat(el.dataset.target) || 0, '', '');
    });
  }

  /* ---------- Insights ---------- */
  function renderInsights(list) {
    const icons = { success: 'bi-check-circle-fill text-success', warning: 'bi-exclamation-triangle-fill text-warning', danger: 'bi-x-octagon-fill text-danger', info: 'bi-info-circle-fill text-primary' };
    const wrap = document.getElementById('insightsList');
    if (!list || !list.length) { wrap.innerHTML = '<div class="text-muted">Aucun signal détecté sur la période.</div>'; return; }
    wrap.innerHTML = list.map(function (ins) {
      const cls = icons[ins.type] || icons.info;
      return '<div class="insight-item">' +
        '<div class="insight-ic"><i class="bi ' + (ins.icon ? 'bi-' + String(ins.icon).replace(/^bi-/, '') : 'bi-info-circle') + ' ' + cls.split(' ')[1] + '"></i></div>' +
        '<div><div class="insight-title">' + esc(ins.title) + '</div>' +
        '<div class="insight-text">' + esc(ins.text) + '</div></div></div>';
    }).join('');
  }

  /* ---------- Charts ---------- */
  function renderTraffic(s) {
    destroyChart('traffic');
    const node = document.querySelector('#chartTraffic');
    if (!s.labels || !s.labels.length || !Array.isArray(s.visits)) { noData('#chartTraffic'); document.getElementById('visitsSummary').textContent = ''; return; }
    node.innerHTML = '';
    const labels = s.labels.map(String);
    const visits = s.visits.map(function (v) { return Number(v) || 0; });
    const ma7 = (s.visits_ma7 || []).map(function (v) { return Number(v) || 0; });
    charts.traffic = new ApexCharts(node, {
      chart: { type: 'area', height: 320, toolbar: { show: false }, zoom: { enabled: false }, animations: { enabled: false } },
      series: [
        { name: 'Visites', data: visits },
        { name: 'Moyenne mobile 7j', data: ma7.length === labels.length ? ma7 : visits.map(function () { return 0; }) }
      ],
      colors: [GREEN2, '#1565c0'],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: [2.5, 2] },
      fill: { type: ['gradient', 'solid'], gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: .02 } },
      xaxis: { categories: labels, labels: { style: { fontSize: '10px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
      yaxis: { min: 0, labels: { style: { fontSize: '10px' } } },
      grid: { borderColor: '#f1f3f5' },
      legend: { position: 'top', fontSize: '12px' },
      tooltip: { x: { format: 'dd/MM' } }
    });
    charts.traffic.render();
    const total = visits.reduce(function (a, b) { return a + b; }, 0);
    const avg = visits.length ? (total / visits.length) : 0;
    document.getElementById('visitsSummary').textContent = fmt.format(total) + ' visites · moy. ' + avg.toFixed(1) + '/j';
  }

  function donut(id, labels, colors, data) {
    destroyChart(id);
    const el = document.querySelector('#' + id);
    if (!el) return;
    // Filtre les valeurs invalides (NaN/null) qui cassent les tracés SVG
    const clean = [];
    const cleanLabels = [];
    const cleanColors = [];
    (labels || []).forEach(function (lab, i) {
      const v = Number(data && data[i]);
      if (isFinite(v) && v >= 0) { clean.push(v); cleanLabels.push(lab === '' ? '—' : lab); cleanColors.push((colors && colors[i]) || '#607d8b'); }
    });
    if (!clean.length) { el.innerHTML = '<div class="text-muted text-center py-5" style="height:190px;display:flex;align-items:center;justify-content:center;">Aucune donnée</div>'; return; }
    el.innerHTML = '';
    charts[id] = new ApexCharts(el, {
      chart: { type: 'donut', height: 190, animations: { enabled: false } },
      series: clean,
      labels: cleanLabels,
      colors: cleanColors,
      legend: { show: false },
      dataLabels: { enabled: data.length > 0, formatter: function (v) { return Math.round(v) + '%'; } },
      plotOptions: { pie: { donut: { size: '62%' } } },
      tooltip: { y: { formatter: function (v) { return fmt.format(v) + ' élément(s)'; } } }
    });
    charts[id].render();
  }

  function renderConsultCharts(c) {
    const statutLabels = [], statutData = [], statutColors = [];
    const smap = { en_attente: '#f9a825', en_cours: '#1976d2', terminee: GREEN2, annulee: '#9e9e9e' };
    Object.keys(c.by_statut).forEach(function (k) { statutLabels.push(k.replace('_', ' ')); statutData.push(c.by_statut[k]); statutColors.push(smap[k] || '#607d8b'); });
    donut('chartConsultStatut', statutLabels, statutColors, statutData);

    const pLabels = [], pData = [], pColors = [];
    const pmap = { paye: GREEN2, en_attente: '#f9a825', annule: '#9e9e9e' };
    Object.keys(c.by_paiement).forEach(function (k) { pLabels.push(k.replace('_', ' ')); pData.push(c.by_paiement[k]); pColors.push(pmap[k] || '#607d8b'); });
    donut('chartPaiement', pLabels, pColors, pData);
  }

  function renderDevices(dev) {
    const labels = [], data = [], colors = [];
    const dmap = { Mobile: '#1565c0', Desktop: GREEN2, Other: '#f9a825', Other_Desktop: '#90a4ae' };
    dev.forEach(function (d) { labels.push(d.label); data.push(d.n); colors.push(dmap[d.label] || '#607d8b'); });
    donut('chartDevices', labels, colors, data);
  }

  function renderHours(hours) {
    if (charts.hours) { try { charts.hours.destroy(); } catch (e) {} delete charts.hours; }
    const canvas = document.getElementById('chartHours');
    if (!canvas || typeof Chart === 'undefined') return;
    const clean = (hours || []).map(function (v) { return Number(v) || 0; });
    charts.hours = new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: clean.map(function (_, i) { return i + 'h'; }),
        datasets: [{ label: 'Visites', data: clean, backgroundColor: GREEN3, borderRadius: 3 }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });
  }

  function renderPages(pages) {
    destroyChart('pages');
    const node = document.querySelector('#chartPages');
    if (!pages || !pages.length || !hasNums(pages.map(function (p) { return p.n; }))) { noData('#chartPages'); return; }
    const labels = pages.map(function (p) { return p.label; }).reverse();
    const data = pages.map(function (p) { return Number(p.n) || 0; }).reverse();
    node.innerHTML = '';
    charts.pages = new ApexCharts(node, {
      chart: { type: 'bar', height: 280, toolbar: { show: false }, animations: { enabled: false } },
      series: [{ name: 'Visites', data: data }],
      colors: ['#1976d2'],
      plotOptions: { bar: { horizontal: true, barHeight: '60%' } },
      dataLabels: { enabled: true, style: { fontSize: '10px' } },
      xaxis: { categories: labels, labels: { style: { fontSize: '10px' } } },
      yaxis: { labels: { style: { fontSize: '10px' }, maxWidth: 150 } },
      grid: { borderColor: '#f1f3f5' },
      tooltip: { x: { formatter: function (v) { return fmt.format(v) + ' visites'; } } }
    });
    charts.pages.render();
  }

  function renderCatalog(cat) {
    destroyChart('catalog');
    const node = document.querySelector('#chartCatalog');
    const par = (cat && cat.par_categorie) || [];
    if (!par.length || !hasNums(par.map(function (c) { return c.n; }))) { noData('#chartCatalog'); document.getElementById('catalogMeta').textContent = ''; return; }
    const labels = par.map(function (c) { return c.label; });
    const data = par.map(function (c) { return Number(c.n) || 0; });
    node.innerHTML = '';
    charts.catalog = new ApexCharts(node, {
      chart: { type: 'bar', height: 230, toolbar: { show: false }, animations: { enabled: false } },
      series: [{ name: 'Produits', data: data }],
      colors: [GREEN],
      plotOptions: { bar: { horizontal: false, columnWidth: '55%' } },
      dataLabels: { enabled: false },
      xaxis: { categories: labels, labels: { style: { fontSize: '9px' }, rotate: -30 } },
      yaxis: { min: 0, tickAmount: 4 },
      grid: { borderColor: '#f1f3f5' },
      tooltip: { y: { formatter: function (v) { return v + ' produit(s)'; } } }
    });
    charts.catalog.render();
    document.getElementById('catalogMeta').textContent = cat.actifs + ' actif(s) · ' + cat.certifies + ' certifié(s) · ' + cat.total + ' au total';
  }

  /* ---------- Listes ---------- */
  function renderConsultTable(rows) {
    const tb = document.getElementById('tblConsult');
    if (!rows || !rows.length) { tb.innerHTML = '<tr><td colspan="6" class="text-muted text-center py-3">Aucune consultation</td></tr>'; return; }
    tb.innerHTML = rows.map(function (r) {
      const d = new Date(String(r.created_at).replace(' ', 'T'));
      return '<tr>' +
        '<td class="text-muted">' + esc(r.numero_consultation || '—') + '</td>' +
        '<td>' + esc(r.patient_nom) + ' ' + esc(r.patient_prenom) + '</td>' +
        '<td>' + esc(r.specialite || '—') + '</td>' +
        '<td>' + statutBadge(r.statut) + '</td>' +
        '<td>' + paiementBadge(r.paiement_statut) + '<div class="text-muted" style="font-size:.72rem">' + fmtMoney.format(parseFloat(r.prix_ht) || 0) + ' ' + esc(r.devise || '') + '</div></td>' +
        '<td class="text-nowrap text-muted">' + (isNaN(d) ? esc(r.created_at) : d.toLocaleDateString('fr-FR') + ' ' + d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })) + '</td>' +
      '</tr>';
    }).join('');
  }

  function renderLeads(leads) {
    const el = document.getElementById('leadsList');
    let html = '';
    if (leads.recent_investors && leads.recent_investors.length) {
      html += '<div class="fw-semibold text-muted mb-1" style="font-size:.75rem">INVESTISSEURS RÉCENTS</div>';
      html += leads.recent_investors.map(function (i) {
        const d = new Date(String(i.created_at).replace(' ', 'T'));
        return '<div class="d-flex justify-content-between border-bottom py-1"><div><i class="bi bi-person-badge text-primary me-1"></i>' + esc(i.full_name) +
          '<div class="text-muted" style="font-size:.72rem">' + esc(i.organization || i.email || '') + (i.commitment_range ? ' · ' + esc(i.commitment_range) : '') + '</div></div>' +
          '<div class="text-nowrap text-muted" style="font-size:.72rem">' + (isNaN(d) ? '' : d.toLocaleDateString('fr-FR')) + '</div></div>';
      }).join('');
    }
    if (leads.recent_brokers && leads.recent_brokers.length) {
      html += '<div class="fw-semibold text-muted mt-2 mb-1" style="font-size:.75rem">COURTIERS RÉCENTS</div>';
      html += leads.recent_brokers.map(function (b) {
        const d = new Date(String(b.created_at).replace(' ', 'T'));
        return '<div class="d-flex justify-content-between border-bottom py-1"><div><i class="bi bi-briefcase text-info me-1"></i>' + esc(b.full_name) +
          '<div class="text-muted" style="font-size:.72rem">' + esc(b.firm_name || b.email || '') + (b.engagement_model ? ' · ' + esc(b.engagement_model) : '') + '</div></div>' +
          '<div class="text-nowrap text-muted" style="font-size:.72rem">' + (isNaN(d) ? '' : d.toLocaleDateString('fr-FR')) + '</div></div>';
      }).join('');
    }
    el.innerHTML = html || '<div class="text-muted">Aucun lead enregistré.</div>';
  }

  function renderContacts(rows, unread) {
    document.getElementById('unreadBadge').textContent = unread || 0;
    const el = document.getElementById('contactsList');
    if (!rows || !rows.length) { el.innerHTML = '<div class="text-muted">Aucun message.</div>'; return; }
    el.innerHTML = rows.map(function (r) {
      const d = new Date(String(r.Date_creation).replace(' ', 'T'));
      return '<div class="d-flex justify-content-between border-bottom py-1">' +
        '<div class="pe-2">' + (parseInt(r.is_readed) ? '<i class="bi bi-envelope-open text-muted me-1"></i>' : '<i class="bi bi-envelope-fill text-danger me-1"></i>') +
        '<strong>' + esc(r.FullName) + '</strong> — ' + esc(r.Subject) +
        '<div class="text-muted" style="font-size:.72rem">' + esc(r.Email) + '</div></div>' +
        '<div class="text-nowrap text-muted" style="font-size:.72rem">' + (isNaN(d) ? '' : d.toLocaleDateString('fr-FR')) + '</div></div>';
    }).join('');
  }

  function renderLogs(rows) {
    const el = document.getElementById('logsList');
    if (!rows || !rows.length) return;
    const nmap = { info: 'bg-info', warning: 'bg-warning text-dark', error: 'bg-danger' };
    el.innerHTML = rows.map(function (r) {
      const d = new Date(String(r.created_at).replace(' ', 'T'));
      return '<div class="d-flex justify-content-between border-bottom py-1">' +
        '<div><span class="badge ' + (nmap[r.niveau] || 'bg-secondary') + ' me-1">' + esc(r.niveau) + '</span>' +
        esc(r.action) + '<div class="text-muted" style="font-size:.72rem">' + esc((r.description || '').substring(0, 60)) + (r.email ? ' · ' + esc(r.email) : '') + '</div></div>' +
        '<div class="text-nowrap text-muted" style="font-size:.72rem">' + (isNaN(d) ? '' : d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })) + '</div></div>';
    }).join('');
  }

  function renderUsers(rows, eco) {
    const el = document.getElementById('usersList');
    el.innerHTML = rows.map(function (u) {
      const last = u.derniere_connexion ? new Date(String(u.derniere_connexion).replace(' ', 'T')) : null;
      return '<div class="d-flex justify-content-between border-bottom py-1">' +
        '<div><i class="bi bi-person-circle me-1 ' + (parseInt(u.actif) ? 'text-success' : 'text-secondary') + '"></i>' + esc(u.nom) + ' ' + esc(u.prenom) +
        '<div class="text-muted" style="font-size:.72rem">' + esc(u.email) + '</div></div>' +
        '<div class="text-nowrap text-muted" style="font-size:.72rem">' + (last && !isNaN(last) ? 'vu ' + last.toLocaleDateString('fr-FR') : 'jamais connecté') + '</div></div>';
    }).join('');
    document.getElementById('ecoStats').innerHTML =
      '<div><div class="fw-bold text-success">' + eco.partenaires + '</div><span class="text-muted">Partenaires</span></div>' +
      '<div><div class="fw-bold text-success">' + eco.newsletter + '</div><span class="text-muted">Newsletter</span></div>' +
      '<div><div class="fw-bold text-success">' + eco.faq + '</div><span class="text-muted">FAQ</span></div>';
  }

  /* ---------- Chargement ---------- */
  async function load(days) {
    currentDays = days || currentDays;
    document.querySelectorAll('[data-days]').forEach(function (b) {
      b.classList.toggle('btn-success', parseInt(b.dataset.days) === currentDays);
      b.classList.toggle('btn-outline-secondary', parseInt(b.dataset.days) !== currentDays);
    });
    const res = await API.get('Dashboard/api_data?days=' + currentDays);
    if (!res.success) {
      document.getElementById('dashLoading').innerHTML = '<div class="alert alert-danger">' + esc(res.message || 'Erreur de chargement') + '</div>';
      return;
    }
    const d = res.data;
    document.getElementById('dashLoading').style.display = 'none';
    document.getElementById('dashContent').style.display = '';

    renderKpis(d.kpis);
    renderInsights(d.insights);
    renderTraffic(d.series);
    renderConsultCharts(d.consultations);
    renderDevices(d.devices);
    renderHours(d.hours);
    renderPages(d.pages);
    renderCatalog(d.catalogue);
    renderConsultTable(d.consultations.recent);
    renderLeads(d.leads);
    renderContacts(d.activity.contacts, d.kpis.messages.unread);
    if (d.activity.logs && d.activity.logs.length) renderLogs(d.activity.logs);
    renderUsers(d.activity.users, d.activity);
  }

  document.querySelectorAll('[data-days]').forEach(function (b) {
    b.addEventListener('click', function () { load(parseInt(b.dataset.days)); });
  });
  document.getElementById('btnRefresh').addEventListener('click', function () { load(currentDays); });
  setInterval(function () { load(currentDays); }, 120000);

  load(30);
})();
</script>
