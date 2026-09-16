(function (global) {
  'use strict';

  var VarEngine = {};

  VarEngine.fmt = function (n) {
    return Number(n == null ? 0 : n).toLocaleString('fr-FR');
  };

  VarEngine.fmtDec = function (n, d) {
    if (n == null || isNaN(n)) return '—';
    return Number(n).toLocaleString('fr-FR', { maximumFractionDigits: d == null ? 1 : d });
  };

  VarEngine.card = function (c) {
    return '' +
      '<div class="col-xxl-3 col-md-6 col-lg-4 mb-3">' +
      '<div class="card info-card ' + (c.alerte ? 'border-danger' : '') + '">' +
      '<div class="card-body">' +
      '<h5 class="card-title">' + API.esc(c.titre) + '</h5>' +
      '<div class="d-flex align-items-center">' +
      '<div class="card-icon rounded-circle d-flex align-items-center justify-content-center ' + (c.alerte ? 'bg-danger bg-opacity-10 text-danger' : '') + '"><i class="bi ' + (c.alerte ? 'bi-exclamation-triangle' : 'bi-clipboard2-pulse') + '"></i></div>' +
      '<div class="ps-3"><h6>' + API.esc(c.valeur == null ? '—' : c.valeur) + '</h6>' +
      (c.sub ? '<span class="text-muted small pt-2 ps-1">' + API.esc(c.sub) + '</span>' : '') +
      '</div></div></div></div></div>';
  };

  VarEngine.loadVariables = async function (moduleCode, endpointPrefix, containerId) {
    var cont = document.getElementById(containerId || 'variablesList');
    if (!cont) return;
    var res = await API.get(endpointPrefix + '/variables?module=' + encodeURIComponent(moduleCode));
    if (!res.success) { cont.innerHTML = '<span class="text-muted">' + API.esc(res.message) + '</span>'; return; }
    var payload = res.data || {};
    var rows = payload.variables || [];
    var peutEditer = !!payload.peut_editer;
    if (!rows.length) { cont.innerHTML = '<span class="text-muted">Aucune variable configurée pour ce module.</span>'; return; }
    cont.innerHTML = rows.map(function (v) {
      var repr = v.type_variable === 'BOOLEEN'
        ? (v.valeur === '1' ? 'Oui' : 'Non')
        : (v.valeur != null ? v.valeur + (v.unite != null && v.unite !== 'jours' ? ' ' + v.unite : (v.unite === 'jours' ? ' j' : '')) : '—');
      var controls = peutEditer
        ? '<div class="d-flex align-items-center gap-2">' +
          '<input type="' + (v.type_variable === 'BOOLEEN' ? 'checkbox' : 'text') + '" class="form-control form-control-sm var-input" style="max-width:180px;" data-id="' + v.id_variable + '" ' +
          (v.type_variable === 'BOOLEEN' ? (v.valeur === '1' ? 'checked' : '') : 'placeholder="Nouvelle valeur"') + '>' +
          '<button class="btn btn-sm btn-primary var-save" data-id="' + v.id_variable + '"><i class="bi bi-check"></i></button>' +
          '<button class="btn btn-sm btn-outline-secondary var-hist" data-id="' + v.id_variable + '" title="Historique"><i class="bi bi-clock-history"></i></button>' +
          '</div>'
        : '<span class="text-muted small" title="Lecture seule">' +
          '<i class="bi bi-lock me-1"></i><button class="btn btn-sm btn-outline-secondary var-hist p-1 px-2" data-id="' + v.id_variable + '" title="Historique"><i class="bi bi-clock-history"></i></button>' +
          '</span>';
      return '' +
        '<div class="d-flex flex-wrap align-items-center justify-content-between border rounded p-2 bg-light bg-opacity-50">' +
        '<div class="me-3">' +
        '<strong class="d-block small">' + API.esc(v.libelle) + '</strong>' +
        '<span class="text-muted small">' + API.esc(v.code) + ' · ' + API.esc(v.type_variable) + (v.unite ? ' · ' + API.esc(v.unite) : '') + '</span>' +
        (v.description ? '<div class="text-muted small">' + API.esc(v.description) + '</div>' : '') +
        '<span class="text-dark small fw-semibold">Valeur actuelle : ' + API.esc(repr) + '</span>' +
        '</div>' +
        controls +
        '</div>';
    }).join('');
  };

  VarEngine.bindVariables = function (moduleCode, endpointPrefix, confirmColor, containerId) {
    var list = document.getElementById(containerId || 'variablesList');
    if (!list) return;
    list.addEventListener('click', async function (e) {
      var btn = e.target.closest('button');
      if (!btn) return;
      var id = btn.dataset.id;
      var row = btn.closest('.d-flex');
      var input = row.querySelector('.var-input');
      var value = input.type === 'checkbox' ? (input.checked ? '1' : '0') : input.value.trim();
      if (!value && input.type === 'text') { API.simpleAlert('warning', 'Valeur vide', 'Saisissez une valeur'); return; }
      if (btn.classList.contains('var-save')) {
        var res = await API.post(endpointPrefix + '/variables/update', { variable_id: id, valeur: value, commentaire: 'Modification via l\'application' });
        if (res.success) { API.simpleAlert('success', 'Variable mise à jour', 'La modification est historisée.'); VarEngine.loadVariables(moduleCode, endpointPrefix, containerId); }
        else API.simpleAlert('error', 'Erreur', res.message);
      } else if (btn.classList.contains('var-hist')) {
        var h = await API.get(endpointPrefix + '/variables/historique?variable_id=' + id);
        if (!h.success) { API.simpleAlert('error', 'Erreur', h.message); return; }
        var html = '<table class="table table-sm table-striped mb-2"><thead><tr><th>Valeur</th><th>Date d\'effet</th><th>Fin d\'effet</th><th>Auteur</th></tr></thead><tbody>' +
          (h.data || []).map(function (r) {
            return '<tr><td>' + API.esc(r.valeur) + '</td><td>' + API.esc(r.date_effet || '') + '</td><td>' + API.esc(r.date_fin_effet || '—') + '</td><td>' + API.esc(r.auteur || 'Système') + '</td></tr>';
          }).join('') + '</tbody></table>';
        if (typeof Swal !== 'undefined') {
          Swal.fire({ title: 'Historique de la valeur', html: html || 'Aucune valeur historique', width: '640px', confirmButtonColor: confirmColor || '#0d6efd' });
        } else { alert('Historique: ' + (h.data || []).length + ' valeur(s)'); }
      }
    });
  };

  global.VarEngine = VarEngine;
})(window);