(function () {
  'use strict';

  var APP = window.AMR_APP || {};
  var moduleCode = APP.module || 'OVERVIEW';

  function $(id) { return document.getElementById(id); }

  function renderKpis(cards) {
    $('kpiRow').innerHTML = (cards || []).map(VarEngine.card).join('') ||
      '<div class="col-12"><div class="alert alert-light text-center">Aucun indicateur.</div></div>';
  }

  function chartOf(holder, ch) {
    if (ch.type === 'doughnut') {
      return new Chart(holder, {
        type: 'doughnut',
        data: { labels: ch.labels, datasets: [{ data: ch.data, backgroundColor: ch.colors || ['#198754', '#ffc107', '#dc3545'] }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
      });
    }
    if (ch.type === 'bar') {
      return new Chart(holder, {
        type: 'bar',
        data: { labels: ch.labels, datasets: (ch.series || []).map(function (s) {
          return { label: s.label, data: s.data, backgroundColor: s.color || 'rgba(13,110,253,0.8)', borderRadius: 4 };
        }) },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: (ch.series || []).length > 1 } }, scales: { y: { beginAtZero: true } } }
      });
    }
    return new Chart(holder, {
      type: 'line',
      data: { labels: ch.labels, datasets: (ch.series || []).map(function (s) {
        return { label: s.label, data: s.data, borderColor: s.color || '#0d6efd', backgroundColor: s.color || '#0d6efd', fill: false, tension: 0.35, pointRadius: 3 };
      }) },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: (ch.series || []).length > 1 } }, scales: { y: { beginAtZero: true } } }
    });
  }

  function renderCharts(charts) {
    var cards = (charts || []).map(function (ch) {
      return '<div class="col-lg-6 col-12">' +
        '<div class="card"><div class="card-body">' +
        '<h5 class="card-title">' + API.esc(ch.titre) + '</h5>' +
        '<div style="height:320px; position:relative;"><canvas class="amrChart"></canvas></div>' +
        '</div></div></div>';
    }).join('');
    $('chartsRow').innerHTML = cards || '<div class="col-12"></div>';
    var holders = $('chartsRow').querySelectorAll('.amrChart');
    (charts || []).forEach(function (ch, i) {
      if (holders[i]) chartOf(holders[i], ch);
    });
  }

  function renderTable(t) {
    if (!t || !t.lignes || !t.lignes.length) { $('moduleDetail').innerHTML = ''; return; }
    var head = (t.colonnes || []).map(function (h) { return '<th>' + API.esc(h) + '</th>'; }).join('');
    var rows = t.lignes.map(function (l) {
      return '<tr>' + l.map(function (c) { return '<td>' + API.esc(c) + '</td>'; }).join('') + '</tr>';
    }).join('');
    $('moduleDetail').innerHTML =
      '<div class="row mt-3"><div class="col-12">' +
      '<div class="card"><div class="card-body">' +
      '<h5 class="card-title">' + API.esc(t.titre) + '</h5>' +
      '<div class="table-responsive"><table class="table table-sm table-striped table-hover">' +
      '<thead><tr>' + head + '</tr></thead><tbody>' + rows + '</tbody></table></div>' +
      '</div></div></div></div>';
  }

  async function chargerModule() {
    var res = await API.get('api/amr/module?module=' + encodeURIComponent(moduleCode));
    if (!res.success) {
      API.simpleAlert('error', 'Erreur', res.message);
      if ($('kpiRow')) $('kpiRow').innerHTML = '<div class="col-12"><div class="alert alert-danger">' + API.esc(res.message) + '</div></div>';
      return;
    }
    renderKpis(res.data.cards);
    renderCharts(res.data.charts);
    renderTable(res.data.table);
    VarEngine.loadVariables(moduleCode, 'api/amr');
  }

  function init() {
    VarEngine.bindVariables(moduleCode, 'api/amr', '#0d6efd');
    chargerModule();
  }

  document.addEventListener('DOMContentLoaded', init);
})();