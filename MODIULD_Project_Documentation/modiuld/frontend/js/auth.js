// ================================================================
// MODIULD — auth.js
// Shared auth utilities available to all pages
// ================================================================

const MODIULD = {
  API_BASE: 'http://localhost/api',

  getToken() {
    return localStorage.getItem('modiuld_token');
  },

  getUser() {
    try {
      return JSON.parse(localStorage.getItem('modiuld_user') || 'null');
    } catch {
      return null;
    }
  },

  isLoggedIn() {
    return !!this.getToken();
  },

  logout() {
    const token = this.getToken();
    if (token) {
      fetch(`${this.API_BASE}/logout`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` },
      }).catch(() => {});
    }
    localStorage.removeItem('modiuld_token');
    localStorage.removeItem('modiuld_user');
    localStorage.removeItem('modiuld_guest');
    window.location.href = 'index.html';
  },

  async request(endpoint, options = {}) {
    const token = this.getToken();
    const headers = {
      'Content-Type': 'application/json',
      ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
      ...options.headers,
    };

    const res = await fetch(`${this.API_BASE}${endpoint}`, {
      ...options,
      headers,
    });

    const data = await res.json();
    return { ok: res.ok, status: res.status, data };
  },
};

// Export for non-module environments
window.MODIULD = MODIULD;
