(function () {
  'use strict';

  var APP = window.EBOLA_APP || {};
  var moduleCode = APP.module || 'DETECTION_DELAY';
  var currentData = null;

  function $(id) { return document.getElementById(id); }

  function kpiCards(module, d) {
    var h = '';
    switch (module) {
      case 'DETECTION_DELAY':
        h += VarEngine.card({ titre: 'Cas notifiés', valeur: VarEngine.fmt(d.total_cas), sub: 'tous statuts' });
        h += VarEngine.card({ titre: 'Délai moyen de détection', valeur: VarEngine.fmtDec(d.delai_moyen_jours) + ' j', sub: 'seuil : ' + VarEngine.fmtDec(d.seuil_jours) + ' j (' + VarEngine.fmtDec(d.seuil_heures) + ' h)', alerte: d.alerte });
        h += VarEngine.card({ titre: 'Cas sous seuil', valeur: VarEngine.fmt(d.sous_seuil), sub: VarEngine.fmtDec(d.pct_sous_seuil) + ' % des cas datés' });
        h += VarEngine.card({ titre: 'Date de début renseignée', valeur: VarEngine.fmtDec(d.pct_avec_date_debut) + ' %', sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_pct) + ' %', alerte: d.alerte && d.pct_avec_date_debut < d.objectif_pct });
        break;
      case 'CONTACT_TRACING':
        h += VarEngine.card({ titre: 'Contacts identifiés', valeur: VarEngine.fmt(d.contacts), sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_par_cas) + ' / cas' });
        h += VarEngine.card({ titre: 'Contacts par cas', valeur: VarEngine.fmtDec(d.contacts_par_cas), sub: d.cas_source + ' cas source' });
        h += VarEngine.card({ titre: 'Contacts suivis', valeur: VarEngine.fmtDec(d.pct_suivis) + ' %', sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_pct) + ' %' });
        h += VarEngine.card({ titre: 'Contacts devenus cas', valeur: VarEngine.fmt(d.devenus_cas), sub: 'durée moyenne ' + VarEngine.fmtDec(d.duree_moy_j) + ' j (cible ' + VarEngine.fmtDec(d.duree_cible_j) + ' j)' });
        break;
      case 'CASE_ASCERTAINMENT':
        h += VarEngine.card({ titre: 'Cas notifiés', valeur: VarEngine.fmt(d.total), sub: 'investigués : ' + VarEngine.fmtDec(d.pct_investigues) + ' %' });
        h += VarEngine.card({ titre: 'Confirmés', valeur: VarEngine.fmt(d.confirme), sub: VarEngine.fmtDec(d.pct_confirme) + ' %' });
        h += VarEngine.card({ titre: 'Probables', valeur: VarEngine.fmt(d.probable), sub: '' });
        h += VarEngine.card({ titre: 'Délai moyen d\'investigation', valeur: VarEngine.fmtDec(d.delai_investigation_j) + ' j', sub: 'cible : ' + VarEngine.fmtDec(d.delai_cible_j) + ' j', alerte: d.alerte_investigation });
        break;
      case 'LAB_CONFIRMATION':
        h += VarEngine.card({ titre: 'Cas testés', valeur: VarEngine.fmtDec(d.pct_testes) + ' %', sub: d.testes + ' / ' + d.total + ' (objectif ' + VarEngine.fmtDec(d.objectif_testes_pct) + ' %)' });
        h += VarEngine.card({ titre: 'Délai moyen de résultat', valeur: VarEngine.fmtDec(d.delai_resultat) + ' j', sub: 'seuil : ' + VarEngine.fmtDec(d.seuil_jours) + ' j', alerte: d.alerte });
        break;
      case 'ISOLATION_DELAY':
        h += VarEngine.card({ titre: 'Cas isolés', valeur: VarEngine.fmtDec(d.pct_isoles) + ' %', sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_pct) + ' %', alerte: d.alerte });
        h += VarEngine.card({ titre: 'Délai moyen d\'isolement', valeur: VarEngine.fmtDec(d.delai_moyen_j) + ' j', sub: 'seuil : ' + VarEngine.fmtDec(d.seuil_jours) + ' j' });
        h += VarEngine.card({ titre: 'Cas avec isolement', valeur: VarEngine.fmt(d.isoles), sub: '/' + VarEngine.fmt(d.total_cas) + ' cas' });
        break;
      case 'SPATIAL_TRANSMISSION':
        h += VarEngine.card({ titre: 'Provinces touchées', valeur: VarEngine.fmt((d.provinces || []).length), sub: 'épicentres : ' + VarEngine.fmt(d.nb_epicentres) + ' (min ' + VarEngine.fmtDec(d.nb_min_epicentres) + ')', alerte: d.alerte_epicentres });
        h += VarEngine.card({ titre: 'Cas totaux', valeur: VarEngine.fmt(d.total_cas), sub: '' });
        h += VarEngine.card({ titre: 'Transmission locale', valeur: VarEngine.fmtDec(d.pct_locaux) + ' %', sub: 'seuil communautaire : ' + VarEngine.fmtDec(d.seuil_communautaire) + ' %', alerte: d.alerte_communautaire });
        break;
      case 'MORTALITY':
        h += VarEngine.card({ titre: 'Décès', valeur: VarEngine.fmt(d.deces), sub: 'issues connues : ' + VarEngine.fmt(d.issues_connues) });
        h += VarEngine.card({ titre: 'Taux de létalité (CFR)', valeur: VarEngine.fmtDec(d.cfr) + ' %', sub: 'seuil d\'alerte : ' + VarEngine.fmtDec(d.seuil_alerte) + ' %', alerte: d.alerte });
        h += VarEngine.card({ titre: 'Cas confirmés', valeur: VarEngine.fmt(d.confirme), sub: '' });
        break;
      case 'ECONOMIC_IMPACT':
        h += VarEngine.card({ titre: 'Coût de riposte', valeur: VarEngine.fmt(d.total) + ' BIF', sub: 'total' });
        h += VarEngine.card({ titre: 'Budget consommé', valeur: VarEngine.fmtDec(d.pct_budget_consomme) + ' %', sub: 'budget annuel : ' + VarEngine.fmt(d.budget_annuel) + ' BIF', alerte: d.alerte_budget });
        h += VarEngine.card({ titre: 'Opérations de dépense', valeur: VarEngine.fmt(d.nb), sub: 'catégories : ' + (d.par_categorie || []).length });
        break;
      case 'FRAMEWORK':
        h += VarEngine.card({ titre: 'Districts sanitaires', valeur: VarEngine.fmt(d.districts), sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_districts), alerte: d.alerte_districts });
        h += VarEngine.card({ titre: 'Structures opérationnelles', valeur: VarEngine.fmt(d.structures), sub: 'objectif : ' + VarEngine.fmtDec(d.objectif_structures), alerte: d.alerte_structures });
        break;
      case 'INTELLIGENCE':
        h += VarEngine.card({ titre: 'Cas (7 derniers jours)', valeur: VarEngine.fmt(d.derniers_7j), sub: '' });
        h += VarEngine.card({ titre: 'Variation vs 7 jours précédents', valeur: VarEngine.fmtDec(d.variation_pct) + ' %', sub: 'seuil : ' + VarEngine.fmtDec(d.seuil_alerte) + ' %', alerte: d.alerte });
        h += VarEngine.card({ titre: 'Cas (7 jours précédents)', valeur: VarEngine.fmt(d.precedents_7j), sub: '' });
        break;
      default:
        h += VarEngine.card({ titre: 'Données', valeur: '—', sub: 'module inconnu' });
    }
    $('kpiRow').innerHTML = h;
  }

  function drawCharts(module, d) {
    var curve = (d.courbe || []).map(function (c) { return { periode: c.periode, total: Number(c.total) }; });
    var isBar = (module === 'SPATIAL_TRANSMISSION' || module === 'FRAMEWORK');

    if (isBar) {
      var series = (module === 'SPATIAL_TRANSMISSION')
        ? (d.provinces || []).map(function (x) { return Number(x.total || 0); })
        : [d.districts, d.structures];
      var cats = (module === 'SPATIAL_TRANSMISSION')
        ? (d.provinces || []).map(function (x) { return x.province_name; })
        : ['Districts', 'Structures'];
      $('chartsRow').innerHTML =
        '<div class="col-lg-12">' +
        '<div class="card"><div class="card-body">' +
        '<h5 class="card-title">' + (module === 'SPATIAL_TRANSMISSION' ? 'Cas par province' : 'Disponibilité du cadre') + '</h5>' +
        '<div style="height:320px; position:relative;"><canvas id="eboBar"></canvas></div>' +
        '</div></div></div>';
      new Chart($('eboBar'), {
        type: 'bar',
        data: {
          labels: cats,
          datasets: [{ label: 'Effectif', data: series, backgroundColor: 'rgba(10,122,10,0.85)', borderRadius: 4 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
      });
      return;
    }

    $('chartsRow').innerHTML =
      '<div class="col-lg-6">' +
      '<div class="card"><div class="card-body">' +
      '<h5 class="card-title">Courbe épidémique (cas par mois)</h5>' +
      '<div style="height:320px; position:relative;"><canvas id="eboCurve"></canvas></div>' +
      '</div></div></div>' +
      '<div class="col-lg-6">' +
      '<div class="card"><div class="card-body">' +
      '<h5 class="card-title">Répartition par statut</h5>' +
      '<div style="height:320px; position:relative;"><canvas id="eboStatut"></canvas></div>' +
      '</div></div></div>';

    new Chart($('eboCurve'), {
      type: 'line',
      data: {
        labels: curve.map(function (c) { return c.periode; }),
        datasets: [{ label: 'Cas', data: curve.map(function (c) { return c.total; }),
          borderColor: '#dc3545', backgroundColor: 'rgba(220,53,69,0.12)', fill: true, tension: 0.35, pointRadius: 3 }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, title: { display: true, text: 'Cas' } } } }
    });

    var statData = module === 'CASE_ASCERTAINMENT'
      ? [{ n: d.suspect }, { n: d.probable }, { n: d.confirme }, { n: d.ecarte }]
      : [];
    new Chart($('eboStatut'), statData.length
      ? {
          type: 'doughnut',
          data: { labels: ['Suspect', 'Probable', 'Confirmé', 'Écarté'], datasets: [{ data: statData.map(function (s) { return s.n; }), backgroundColor: ['#ffc107', '#0dcaf0', '#dc3545', '#adb5bd'] }] },
          options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        }
      : {
          type: 'doughnut',
          data: { labels: ['Cas notifiés'], datasets: [{ data: [1], backgroundColor: ['#dc3545'] }] },
          options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
  }

  async function chargerModule() {
    var res = await API.get('api/ebola/module?module=' + encodeURIComponent(moduleCode));
    if (!res.success) {
      API.simpleAlert('error', 'Erreur', res.message);
      if ($('kpiRow')) $('kpiRow').innerHTML = '<div class="col-12"><div class="alert alert-danger">' + API.esc(res.message) + '</div></div>';
      return;
    }
    currentData = res.data;
    if ($('kpiRow')) { kpiCards(moduleCode, res.data); drawCharts(moduleCode, res.data); }
    VarEngine.loadVariables(moduleCode, 'api/ebola');
  }

  function init() {
    VarEngine.bindVariables(moduleCode, 'api/ebola', '#dc3545');
    chargerModule();
  }

  document.addEventListener('DOMContentLoaded', init);
})();