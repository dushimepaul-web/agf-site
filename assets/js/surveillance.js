(function (global) {
  'use strict';

  var Surg = {};

  Surg.LEVELS = ['province', 'commune', 'zone', 'colline'];

  Surg.STAT_LABELS = {
    suspect: 'Suspect',
    probable: 'Probable',
    confirme: 'Confirmé',
    ecarte: 'Écarté'
  };

  Surg.GRAVITE_LABELS = {
    legere: 'Légère',
    moderee: 'Modérée',
    severe: 'Sévère',
    critique: 'Critique'
  };

  Surg.ISSUE_LABELS = {
    en_traitement: 'En traitement',
    gueri: 'Guéri',
    decede: 'Décédé',
    perdu_de_vue: 'Perdu de vue'
  };

  Surg.TRANCHES = ['0-4', '5-14', '15-49', '50-64', '65+'];

  Surg.badgeSex = function (s) {
    if (s === 'M') return '<span class="badge bg-info">Masculin</span>';
    if (s === 'F') return '<span class="badge bg-warning text-dark">Féminin</span>';
    return '<span class="badge bg-secondary">—</span>';
  };

  Surg.badgeStatut = function (s) {
    var map = { suspect: 'bg-warning text-dark', probable: 'bg-primary', confirme: 'bg-danger', ecarte: 'bg-secondary' };
    return '<span class="badge ' + (map[s] || 'bg-secondary') + '">' + API.esc(Surg.STAT_LABELS[s] || s || '—') + '</span>';
  };

  Surg.badgeActive = function (v) {
    return parseInt(v, 10) === 1
      ? '<span class="badge bg-success">Active</span>'
      : '<span class="badge bg-secondary">Inactive</span>';
  };

  Surg.badgeIssue = function (i) {
    var map = { en_traitement: 'bg-warning text-dark', gueri: 'bg-success', decede: 'bg-dark', perdu_de_vue: 'bg-secondary' };
    return '<span class="badge ' + (map[i] || 'bg-secondary') + '">' + API.esc(Surg.ISSUE_LABELS[i] || i || '—') + '</span>';
  };

  Surg.loadLevel = function (niveau, parentUuid) {
    return API.surveillance.filters(niveau, parentUuid).then(function (res) {
      return res.success ? (res.data || []) : [];
    });
  };

  function fillOptions(sel, rows) {
    sel.innerHTML = '<option value="">— Sélectionner —</option>';
    (rows || []).forEach(function (r) {
      var opt = document.createElement('option');
      opt.value = r.uuid;
      opt.textContent = r.nom;
      sel.appendChild(opt);
    });
  }

  Surg.fillSelect = function (sel, rows, field) {
    if (!sel) return;
    sel.innerHTML = '<option value="">— Sélectionner —</option>';
    (rows || []).forEach(function (r) {
      var opt = document.createElement('option');
      opt.value = r.uuid;
      opt.textContent = r[field] || r.nom || '';
      sel.appendChild(opt);
    });
  };

  function elementsOf(form) {
    var els = {};
    Surg.LEVELS.forEach(function (level) {
      els[level] = form.elements[level + '_uuid'];
    });
    return els;
  }

  Surg.geoFromForm = function (form) {
    var g = {};
    var els = elementsOf(form);
    if (!els.province) return g;
    Surg.LEVELS.forEach(function (level) {
      g[level + '_uuid'] = els[level].value;
    });
    return g;
  };

  Surg.geoSet = function (form, g) {
    var els = elementsOf(form);
    if (!els.province) return Promise.resolve();

    return new Promise(function (resolve) {
      els.province.value = g.province_uuid || '';
      els.commune.innerHTML = '';
      els.zone.innerHTML = '';
      els.colline.innerHTML = '';
      walk(0);

      function walk(idx) {
        if (idx >= Surg.LEVELS.length) {
          Surg.LEVELS.forEach(function (level) {
            els[level].value = g[level + '_uuid'] || (els[level].value || '');
          });
          return resolve();
        }
        var level = Surg.LEVELS[idx];
        var parent = idx === 0 ? '' : g[Surg.LEVELS[idx - 1] + '_uuid'];
        Surg.loadLevel(level, parent).then(function (rows) {
          fillOptions(els[level], rows);
          walk(idx + 1);
        });
      }
    });
  };

  Surg.setByText = function (sel, text) {
    if (!text) { sel.value = ''; return; }
    var wanted = String(text).trim().toLowerCase();
    for (var i = 0; i < sel.options.length; i++) {
      if (String(sel.options[i].text).trim().toLowerCase() === wanted) {
        sel.value = sel.options[i].value;
        return;
      }
    }
    sel.value = '';
  };

  Surg.geoSetByName = function (form, names) {
    var els = elementsOf(form);
    if (!els.province) return Promise.resolve();

    return new Promise(function (resolve) {
      els.province.innerHTML = '';
      els.commune.innerHTML = '';
      els.zone.innerHTML = '';
      els.colline.innerHTML = '';
      walk(0);

      function walk(idx) {
        if (idx >= Surg.LEVELS.length) return resolve();
        var level = Surg.LEVELS[idx];
        var key = idx === 0 ? 'province_name' : level + '_name';
        var parent = idx === 0 ? '' : els[Surg.LEVELS[idx - 1]].value;
        Surg.loadLevel(level, parent).then(function (rows) {
          fillOptions(els[level], rows);
          Surg.setByText(els[level], names[key] || '');
          walk(idx + 1);
        });
      }
    });
  };

  Surg.wire = function (form) {
    var els = elementsOf(form);
    if (!els.province) return;

    function loadInto(idx, parent) {
      var level = Surg.LEVELS[idx];
      for (var i = idx; i < Surg.LEVELS.length; i++) {
        els[Surg.LEVELS[i]].innerHTML = '';
      }
      return Surg.loadLevel(level, parent).then(function (rows) {
        fillOptions(els[level], rows);
      });
    }

    els.province.addEventListener('change', function () {
      loadInto(1, els.province.value);
    });
    els.commune.addEventListener('change', function () {
      loadInto(2, els.commune.value);
    });
    els.zone.addEventListener('change', function () {
      loadInto(3, els.zone.value);
    });

    loadInto(0, '');
  };

  global.Surg = Surg;

})(window);