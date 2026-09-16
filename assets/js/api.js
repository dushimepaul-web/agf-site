if (typeof API === 'undefined') {
var API = {
  base_url: (typeof BASE_URL !== 'undefined') ? BASE_URL : '/',
  csrf_token: (typeof CSRF_TOKEN !== 'undefined') ? CSRF_TOKEN : '',

  _withCsrf(data) {
    if (!data || typeof data !== 'object') data = {};
    data[API.csrf_field_name()] = API.csrf_token;
    return data;
  },

  csrf_field_name() {
    return 'csrf_token';
  },

  async request(url, options = {}) {
    try {
      const res = await fetch(this.base_url + url, options);
      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error('API Error Response (not valid JSON):', text);
        return { success: false, message: 'Réponse serveur non valide (HTTP ' + res.status + ')' };
      }
      return data;
    } catch (err) {
      console.error('API Fetch Exception:', err);
      return { success: false, message: 'Erreur de connexion' };
    }
  },

  get(url) {
    return this.request(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
  },

  post(url, data) {
    return this.request(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify(this._withCsrf(data || {}))
    });
  },

  jsonRequest(url, options = {}) {
    if (options.method === 'POST' && options.body) {
      try {
        const parsed = JSON.parse(options.body);
        const merged = this._withCsrf(parsed);
        options.body = JSON.stringify(merged);
      } catch (e) { /* corps non JSON : laisser tel quel */ }
    }
    return this.request(url, options);
  }
};
}

function crudResource(name) {
  API[name] = {
    list: () => API.get('api/' + name),
    get: (id) => API.get('api/' + name + '/' + id),
    create: (d) => API.post('api/' + name + '/create', d),
    update: (id, d) => API.post('api/' + name + '/' + id + '/update', d),
    remove: (id) => API.post('api/' + name + '/' + id + '/delete', {})
  };
}

['provinces', 'communes', 'zones', 'collines', 'districts', 'structures', 'roles', 'menus', 'patients', 'cas', 'vaccinations', 'campagnes'].forEach(crudResource);
crudResource('variables');

API.surveillance = {
  meta: function () { return API.get('api/surveillance/meta'); },
  filters: function (niveau, parent) {
    return API.get('api/surveillance/filters?niveau=' + encodeURIComponent(niveau) + (parent ? '&parent=' + encodeURIComponent(parent) : ''));
  },
  patients: function () { return API.get('api/surveillance/patients-select'); }
};

API.import = {
  templateUrl: function (type) {
    return API.base_url + 'api/import/template?type=' + encodeURIComponent(type);
  },
  upload: function (file) {
    var fd = new FormData();
    fd.append('file', file);
    fd.append(API.csrf_field_name(), API.csrf_token);
    return API.request('api/import/upload', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    });
  }
};

API.sauvegardes = {
  list: () => API.get('api/sauvegardes'),
  create: () => API.post('api/sauvegardes/create', {}),
  remove: (f) => API.post('api/sauvegardes/delete/' + encodeURIComponent(f), {})
};

API.parametres = {
  list: () => API.get('api/parametres'),
  update: (d) => API.post('api/parametres/update', d)
};

API.simpleAlert = function (icon, title, text) {
  if (typeof Swal !== 'undefined') {
    return Swal.fire({ icon: icon || 'info', title: title || '', text: text || '', confirmButtonColor: '#012970' });
  }
  alert(title + (text ? ' - ' + text : ''));
  return Promise.resolve({ isConfirmed: true });
};

API.confirm = function (title, text) {
  if (typeof Swal !== 'undefined') {
    return Swal.fire({ title: title || 'Confirmer', text: text || '', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Oui', cancelButtonText: 'Annuler' });
  }
  return Promise.resolve(confirm(title + ' ' + text) ? { isConfirmed: true } : { isConfirmed: false });
};

API.esc = function (s) {
  return String(s == null ? '' : s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
};
