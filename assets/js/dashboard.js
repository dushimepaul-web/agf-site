/* Dashboard dynamique — surveillance des maladies (Étape 1)
 * Vanilla JS + Chart.js + Leaflet : sélecteur de maladie, filtres en cascade,
 * KPIs, courbe épidémique, donut statuts, barres provinces, carte à niveaux
 * de risque, qualité de surveillance, vaccination.
 */
(function () {
  'use strict';

  var DASH = window.DASH || {};
  var maladie = DASH.maladie || '';

  var state = {
    province: '',
    commune: '',
    zone: '',
    colline: '',
    statut: '',
    sexe: '',
    tranche: '',
    enceinte: ''
  };

  var els = {};
  var map = null;
  var mapMarkers = [];
  var casChart = null;    // courbe épidémique (Chart.js line)
  var statutChart = null; // donut statuts
  var provChart = null;   // bar provinces

  function $(id) { return document.getElementById(id); }

  function fmt(n) {
    return Number(n || 0).toLocaleString('fr-FR');
  }

  function setOptions(sel, rows) {
    sel.innerHTML = '';
    var all = document.createElement('option');
    all.value = '';
    all.textContent = sel.dataset.placeholder || '— Tous —';
    sel.appendChild(all);
    (rows || []).forEach(function (r) {
      var o = document.createElement('option');
      o.value = r.uuid || r.id;
      o.textContent = r.nom;
      sel.appendChild(o);
    });
  }

  function disableFrom(selectId) {
    var mapId = {
      filtProvince: ['filtCommune', 'filtZone', 'filtColline'],
      filtCommune: ['filtZone', 'filtColline'],
      filtZone: ['filtColline']
    };
    (mapId[selectId] || []).forEach(function (id) {
      var s = $(id);
      s.innerHTML = '';
      s.disabled = true;
      var o = document.createElement('option');
      o.value = '';
      o.textContent = s.dataset.placeholder || '— Tous —';
      s.appendChild(o);
    });
  }

  async function loadChildren(level, parent, selectId) {
    var url = 'api/dashboard/filters?niveau=' + level;
    if (parent) url += '&parent=' + encodeURIComponent(parent);
    var res = await API.get(url);
    if (!res.success) { setOptions($(selectId), []); return; }
    setOptions($(selectId), res.data);
    $(selectId).disabled = false;
  }

  /* Sélecteur de maladie : un changement recharge tout le dashboard (J7-J10) */
  function bindMaladieSelect() {
    var sel = $('filtreMaladie');
    if (!sel) return;
    sel.value = maladie;
    sel.addEventListener('change', function (e) {
      maladie = e.target.value;
      if (window.history && window.history.replaceState) {
        var url = BASE_URL + 'Dashboard' + (maladie ? '/' + maladie : '');
        window.history.replaceState(null, '', url);
      }
      resetAll();
    });
  }

  function bindFilters() {
    els = {
      province: $('filtProvince'),
      commune: $('filtCommune'),
      zone: $('filtZone'),
      colline: $('filtColline'),
      statut: $('filtStatut'),
      sexe: $('filtSexe'),
      tranche: $('filtTranche'),
      enceinte: $('filtEnceinte')
    };

    els.province.addEventListener('change', function (e) {
      disableFrom('filtProvince');
      state.province = e.target.value;
      state.commune = state.zone = state.colline = '';
      if (e.target.value) loadChildren('commune', e.target.value, 'filtCommune');
    });
    els.commune.addEventListener('change', function (e) {
      state.commune = e.target.value;
      state.zone = state.colline = '';
      if (e.target.value) loadChildren('zone', e.target.value, 'filtZone');
    });
    els.zone.addEventListener('change', function (e) {
      state.zone = e.target.value;
      state.colline = '';
      if (e.target.value) loadChildren('colline', e.target.value, 'filtColline');
    });
    els.colline.addEventListener('change', function (e) { state.colline = e.target.value; });

    ['statut', 'sexe', 'tranche', 'enceinte'].forEach(function (k) {
      els[k].addEventListener('change', function (e) { state[k] = e.target.value; });
    });

    $('btnApply').addEventListener('click', chargerDonnees);
    $('btnReset').addEventListener('click', resetAll);
  }

  async function resetAll() {
    state = { province: '', commune: '', zone: '', colline: '',
              statut: '', sexe: '', tranche: '', enceinte: '' };
    await loadChildren('province', '', 'filtProvince');
    disableFrom('filtProvince');
    Object.keys(els).forEach(function (k) {
      var s = els[k];
      if (s.tagName === 'SELECT') { s.value = ''; s.disabled = (k !== 'province'); }
    });
    chargerDonnees();
  }

  function buildURL() {
    var q = 'api/dashboard/data?maladie=' + encodeURIComponent(maladie);
    if (state.province) q += '&province=' + state.province;
    if (state.commune) q += '&commune=' + state.commune;
    if (state.zone) q += '&zone=' + state.zone;
    if (state.colline) q += '&colline=' + state.colline;
    if (state.statut) q += '&statut=' + encodeURIComponent(state.statut);
    if (state.sexe) q += '&sexe=' + encodeURIComponent(state.sexe);
    if (state.tranche) q += '&tranche=' + encodeURIComponent(state.tranche);
    if (state.enceinte) q += '&enceinte=1';
    return q;
  }

  async function chargerDonnees() {
    $('kpiLoading').classList.remove('d-none');
    var res = await API.get(buildURL());
    $('kpiLoading').classList.add('d-none');
    if (!res.success) { API.simpleAlert('error', 'Erreur', res.message); return; }
    renderKPIs(res.data.kpi, res.data.taux_attaque, res.data.population);
    renderCourbe(res.data.curve || []);
    renderStatuts(res.data.statuts || []);
    renderProvinces(res.data.par_province || []);
    renderCarte(res.data.carte || []);
    renderQualite(res.data.qualite || {});
    renderVaccination(res.data.vaccination || {});
    renderCas(res.data.derniers_cas || []);
  }

  function renderKPIs(k, tauxAttaque, population) {
    $('kpiTotal').textContent = fmt(k.total);
    $('kpiConfirme').textContent = fmt(k.confirme);
    $('kpiDecede').textContent = fmt(k.decede);
    $('kpiHospitalises').textContent = fmt(k.hospitalises);
    $('kpiCfr').textContent = Number(k.cfr || 0).toLocaleString('fr-FR') + ' %';
    if (tauxAttaque == null || isNaN(tauxAttaque)) {
      $('kpiTaux').textContent = '—';
      $('kpiTauxSrc').classList.add('d-none');
    } else {
      $('kpiTaux').textContent = Number(tauxAttaque).toLocaleString('fr-FR', { maximumFractionDigits: 2 });
      $('kpiTauxSrc').classList.remove('d-none');
    }
  }

  /* ---------- Courbe épidémique (Chart.js) ---------- */
  function renderCourbe(curves) {
    var series = curves.map(function (c) { return Number(c.total); });
    var cats = curves.map(function (c) { return c.periode || ''; });
    if (!casChart) {
      casChart = new Chart($('casChart'), {
        type: 'line',
        data: {
          labels: cats,
          datasets: [{
            label: 'Cas notifiés',
            data: series,
            borderColor: '#4154f1',
            backgroundColor: 'rgba(65,84,241,0.12)',
            fill: true,
            tension: 0.35,
            pointRadius: 3,
            pointBackgroundColor: '#4154f1'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true, title: { display: true, text: 'Cas' } } }
        }
      });
    } else {
      casChart.data.labels = cats;
      casChart.data.datasets[0].data = series;
      casChart.update();
    }
  }

  /* ---------- Donut statuts (Chart.js) ---------- */
  function renderStatuts(list) {
    var labels = { suspect: 'Suspect', probable: 'Probable', confirme: 'Confirmé', ecarte: 'Écarté' };
    var series = list.map(function (r) { return Number(r.total); });
    var cats = list.map(function (r) { return labels[r.nom] || r.nom || '—'; });
    if (!statutChart) {
      statutChart = new Chart($('statutChart'), {
        type: 'doughnut',
        data: {
          labels: cats,
          datasets: [{
            data: series,
            backgroundColor: ['#ffc107', '#0dcaf0', '#dc3545', '#adb5bd'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    } else {
      statutChart.data.labels = cats;
      statutChart.data.datasets[0].data = series;
      statutChart.update();
    }
  }

  /* ---------- Barres provinces (Chart.js) ---------- */
  function renderProvinces(list) {
    var series = list.map(function (d) { return Number(d.total); });
    var cats = list.map(function (d) { return d.province_name || 'Inconnue'; });
    if (!provChart) {
      provChart = new Chart($('provChart'), {
        type: 'bar',
        data: {
          labels: cats,
          datasets: [{
            label: 'Cas',
            data: series,
            backgroundColor: 'rgba(10,122,10,0.85)',
            borderRadius: 4,
            maxBarThickness: 45
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true, title: { display: true, text: 'Cas' } } }
        }
      });
    } else {
      provChart.data.labels = cats;
      provChart.data.datasets[0].data = series;
      provChart.update();
    }
  }

  /* ---------- Carte Leaflet (niveaux de risque) ---------- */
  function mapInit() {
    if (map) return;
    map = L.map('dashMap').setView([-3.3822, 29.3644], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
  }

  function riskColor(total) {
    if (total === 0) return '#6c757d';
    if (total <= 5) return '#0a7a0a';
    if (total <= 15) return '#ffc107';
    if (total <= 50) return '#fd7e14';
    return '#dc3545';
  }

  function renderCarte(pts) {
    mapInit();
    mapMarkers.forEach(function (m) { map.removeLayer(m); });
    mapMarkers = [];
    pts.forEach(function (p) {
      var lat = parseFloat(p.latitude);
      var lng = parseFloat(p.longitude);
      if (!isFinite(lat) || !isFinite(lng)) return;
      var total = Number(p.total || 0);
      var radius = Math.max(6, Math.min(30, 6 + total * 2));
      var color = riskColor(total);
      var m = L.circleMarker([lat, lng], {
        radius: radius,
        color: color,
        fillColor: color,
        fillOpacity: 0.65
      }).addTo(map);
      m.bindTooltip(API.esc(p.nom), { direction: 'top' });
      m.bindPopup(
        '<strong>' + API.esc(p.nom) + '</strong><br>' +
        'Cas : <b>' + fmt(total) + '</b>' +
        (p.parent_nom ? '<br><small>' + API.esc(p.parent_nom) + '</small>' : '')
      );
      mapMarkers.push(m);
    });
    if (mapMarkers.length) {
      map.fitBounds(mapMarkers.map(function (m) { return m.getLatLng(); }), { padding: [20, 20] });
    }
  }

  /* ---------- Qualité de surveillance ---------- */
  function renderQualite(q) {
    $('qualDelai').textContent = (q.delai_notification == null) ? '—' : fmt(q.delai_notification) + ' jour(s)';
    $('qualSymptomes').textContent = fmt(q.pct_date_symptomes) + ' %';
    $('qualSymptomesBar').style.width = Math.min(100, Number(q.pct_date_symptomes || 0)) + '%';
    $('qualLabo').textContent = fmt(q.pct_tests_labo) + ' %';
    $('qualLaboBar').style.width = Math.min(100, Number(q.pct_tests_labo || 0)) + '%';
    $('qualIssue').textContent = fmt(q.pct_issue_renseignee) + ' %';
    $('qualIssueBar').style.width = Math.min(100, Number(q.pct_issue_renseignee || 0)) + '%';
  }

  /* ---------- Vaccination ---------- */
  function renderVaccination(v) {
    $('vaccPersonnes').textContent = fmt(v.personnes);
    $('vaccDoses').textContent = fmt(v.doses);
    var pct = 0;
    if (Number(v.personnes) > 0 && Number(v.doses) > 0) {
      pct = Math.min(100, Math.round((Number(v.doses) * 100) / Number(v.personnes)));
    }
    $('vaccProgression').style.width = pct + '%';
    $('vaccPct').textContent = pct + ' %';
  }

  function fmtDate(date) {
    if (!date) return '—';
    var d = new Date(date);
    if (isNaN(d.getTime())) return date;
    return d.toLocaleDateString('fr-FR');
  }

  function renderCas(list) {
    var tbody = $('derniersCasBody');
    tbody.innerHTML = '';
    var badges = {
      suspect: '<span class="badge bg-warning text-dark">Suspect</span>',
      probable: '<span class="badge bg-info">Probable</span>',
      confirme: '<span class="badge bg-danger">Confirmé</span>',
      ecarte: '<span class="badge bg-secondary">Écarté</span>'
    };
    if (!list.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucun cas notifié pour le moment.</td></tr>';
      return;
    }
    list.forEach(function (c, i) {
      tbody.insertAdjacentHTML('beforeend',
        '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td>' + API.esc(c.case_code || '—') + '</td>' +
        '<td>' + API.esc(c.province_name || '—') + '</td>' +
        '<td>' + (badges[c.statut_cas] || API.esc(c.statut_cas || '—')) + '</td>' +
        '</tr>');
    });
  }

  async function init() {
    bindMaladieSelect();
    bindFilters();
    await loadChildren('province', '', 'filtProvince');
    chargerDonnees();
  }

  document.addEventListener('DOMContentLoaded', init);
})();