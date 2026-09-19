// MODIULD Auth Helper
const API = '/api';

const Auth = {
  getToken: () => localStorage.getItem('modiuld_token'),
  setToken: (t) => localStorage.setItem('modiuld_token', t),
  removeToken: () => localStorage.removeItem('modiuld_token'),
  getUser: () => {
    try {
      const v = localStorage.getItem('modiuld_user');
      if (!v || v === 'undefined') return null;
      return JSON.parse(v);
    } catch { return null; }
  },
  setUser: (u) => localStorage.setItem('modiuld_user', JSON.stringify(u)),
  removeUser: () => localStorage.removeItem('modiuld_user'),

  getGuestToken: () => {
    let t = localStorage.getItem('modiuld_guest_token');
    if (!t) { t = crypto.randomUUID(); localStorage.setItem('modiuld_guest_token', t); }
    return t;
  },
  isLoggedIn: () => !!localStorage.getItem('modiuld_token'),
  isGuest: () => !localStorage.getItem('modiuld_token'),

  headers(json = true) {
    const h = {};
    if (json) h['Content-Type'] = 'application/json';
    const tok = this.getToken();
    if (tok) h['Authorization'] = 'Bearer ' + tok;
    else h['X-Guest-Token'] = this.getGuestToken();
    return h;
  },

  async request(path, method = 'GET', body = null) {
    const opts = { method, headers: this.headers() };
    if (body) opts.body = JSON.stringify(body);
    try {
      const res = await fetch(API + path, opts);
      const data = await res.json().catch(() => ({}));
      return { ok: res.ok, status: res.status, data };
    } catch(e) {
      return { ok: false, status: 0, data: { error: 'Network error.' } };
    }
  },

  async register(email, username, password, confirm, name) {
    const r = await this.request('/register', 'POST', { email, username, password, confirm_password: confirm, display_name: name });
    if (r.ok) { this.setToken(r.data.token); this.setUser(r.data.user); }
    return r;
  },

  async login(email, password) {
    const r = await this.request('/login', 'POST', { email, password });
    if (r.ok) { this.setToken(r.data.token); this.setUser(r.data.user); }
    return r;
  },

  async googleSignIn(idToken) {
    const r = await this.request('/google/sign-in', 'POST', { id_token: idToken });
    if (r.ok && r.data.token) { this.setToken(r.data.token); this.setUser(r.data.user); }
    return r;
  },

  async completeGoogleSignup(signupToken, username) {
    const r = await this.request('/google/complete', 'POST', { signup_token: signupToken, username });
    if (r.ok) { this.setToken(r.data.token); this.setUser(r.data.user); }
    return r;
  },

  async initGoogle(buttonId, onResult) {
    const config = await this.request('/google/config');
    if (!config.ok) { onResult({ error: config.data.error || 'Google Sign-In is unavailable.' }); return; }
    const start = Date.now();
    const render = () => {
      if (window.google?.accounts?.id) {
        google.accounts.id.initialize({
          client_id: config.data.client_id,
          callback: async (response) => onResult(await this.googleSignIn(response.credential))
        });
        google.accounts.id.renderButton(document.getElementById(buttonId), { theme: 'outline', size: 'large', width: 320, text: 'signin_with', locale: 'th' });
      } else if (Date.now() - start < 10000) setTimeout(render, 100);
      else onResult({ error: 'Unable to load Google Sign-In.' });
    };
    render();
  },

  async logout() {
    await this.request('/logout', 'POST');
    this.removeToken(); this.removeUser();
    window.location.href = '/index.html';
  },

  async me() {
    return await this.request('/me');
  },

  requireAuth() {
    if (!this.isLoggedIn()) { window.location.href = '/login.html'; return false; }
    return true;
  },

  redirectIfLoggedIn() {
    if (this.isLoggedIn()) { window.location.href = '/dashboard.html'; }
  }
};

// Show/hide alert helper
function showAlert(el, msg, type = 'error') {
  el.textContent = msg;
  el.className = 'alert alert-' + type;
  el.classList.remove('hidden');
  if (type === 'success') setTimeout(() => el.classList.add('hidden'), 4000);
}
