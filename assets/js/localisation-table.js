(function (global) {
  'use strict';

  function debounce(fn, ms) {
    var t;
    return function () {
      var args = arguments;
      var ctx = this;
      clearTimeout(t);
      t = setTimeout(function () { fn.apply(ctx, args); }, ms);
    };
  }

  function isActive(r) {
    return parseInt(r.est_actif, 10) === 1;
  }

  function defaultCount(total, active) {
    return String(total) + ' \u00b7 ' + active + (active > 1 ? ' actives' : ' active');
  }

  function GeoTable(cfg) {
    this.cfg = cfg || {};
    this.rows = [];
    this.page = 1;
    this.perPage = parseInt(this.cfg.perPage, 10) || 15;

    this.body = document.getElementById('tableBody');
    this.loading = document.getElementById('tableLoading');
    this.empty = document.getElementById('tableEmpty');
    this.searchEl = document.getElementById('tableSearch');
    this.statusEl = document.getElementById('tableStatus');
    this.perPageEl = document.getElementById('tblPerPage');
    this.infoEl = document.getElementById('tableInfo');
    this.pagerEl = document.getElementById('tblPager');
    this.countEl = document.getElementById('tableCount');

    var self = this;

    if (this.searchEl) {
      this.searchEl.addEventListener('input', debounce(function () {
        self.page = 1;
        self.render();
      }, 250));
    }
    if (this.statusEl) {
      this.statusEl.addEventListener('change', function () {
        self.page = 1;
        self.render();
      });
    }
    if (this.perPageEl) {
      this.perPageEl.value = String(this.perPage);
      this.perPageEl.addEventListener('change', function () {
        self.perPage = parseInt(this.value, 10) || self.perPage;
        self.page = 1;
        self.render();
      });
    }
    if (this.pagerEl) {
      this.pagerEl.addEventListener('click', function (e) {
        var a = e.target.closest('a[data-page]');
        if (!a) return;
        e.preventDefault();
        self.goToPage(parseInt(a.getAttribute('data-page'), 10));
      });
    }
  }

  GeoTable.prototype.goToPage = function (p) {
    var totalPages = Math.max(1, Math.ceil(this.filtered().length / this.perPage));
    if (p < 1) p = 1;
    if (p > totalPages) p = totalPages;
    this.page = p;
    this.render();
  };

  GeoTable.prototype.filtered = function () {
    var self = this;
    var query = (this.searchEl ? this.searchEl.value : '').toLowerCase().trim();
    var status = this.statusEl ? this.statusEl.value : '';
    var terms = this.cfg.searchTerms || function () { return ''; };
    return this.rows.filter(function (r) {
      if (status !== '' && (isActive(r) ? '1' : '0') !== status) return false;
      if (query && String(terms(r) || '').toLowerCase().indexOf(query) === -1) return false;
      return true;
    });
  };

  GeoTable.prototype.setRows = function (rows) {
    this.rows = rows || [];
    if (this.loading) this.loading.classList.add('d-none');
    this.page = 1;
    this.render();
  };

  GeoTable.prototype.render = function () {
    var list = this.filtered();
    var totalPages = Math.max(1, Math.ceil(list.length / this.perPage));
    if (this.page > totalPages) this.page = totalPages;

    var start = (this.page - 1) * this.perPage;
    var slice = list.slice(start, start + this.perPage);
    var html = '';
    var num = start;
    for (var i = 0; i < slice.length; i++) {
      html += this.cfg.renderRow(slice[i], ++num);
    }
    this.body.innerHTML = html;

    var total = this.rows.length;
    var active = this.rows.filter(isActive).length;
    if (this.countEl) {
      var countFn = this.cfg.count || defaultCount;
      this.countEl.textContent = countFn(total, active);
    }

    if (this.empty) {
      this.empty.classList.toggle('d-none', slice.length > 0);
    }

    if (this.infoEl) {
      this.infoEl.textContent = list.length
        ? 'Affichage ' + (start + 1) + '\u2013' + (start + slice.length) + ' sur ' + list.length
        : '';
    }

    this.renderPager(totalPages);
    if (this.cfg.afterRender) this.cfg.afterRender(slice, list);
  };

  GeoTable.prototype.renderPager = function (totalPages) {
    if (!this.pagerEl) return;
    var html = '';
    var i;

    html += '<li class="page-item' + (this.page === 1 ? ' disabled' : '') + '">' +
      '<a class="page-link" href="#" data-page="' + (this.page - 1) + '" aria-label="Page pr\u00e9c\u00e9dente"><span>&laquo;</span></a></li>';

    var from = Math.max(1, this.page - 3);
    var to = Math.min(totalPages, from + 6);
    from = Math.max(1, to - 6);
    for (i = from; i <= to; i++) {
      html += '<li class="page-item' + (i === this.page ? ' active' : '') + '">' +
        '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
    }

    html += '<li class="page-item' + (this.page === totalPages ? ' disabled' : '') + '">' +
      '<a class="page-link" href="#" data-page="' + (this.page + 1) + '" aria-label="Page suivante"><span>&raquo;</span></a></li>';

    this.pagerEl.innerHTML = html;
  };

  GeoTable.fmtCoord = function (value, isLat) {
    var v = parseFloat(value);
    if (!isFinite(v)) return '\u2014';
    var dir = isLat ? (v >= 0 ? 'N' : 'S') : (v >= 0 ? 'E' : 'O');
    return Math.abs(v).toFixed(2) + '\u00b0 ' + dir;
  };

  global.GeoTable = GeoTable;

})(window);
