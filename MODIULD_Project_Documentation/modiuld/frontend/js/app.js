// MODIULD App — Loadout & Module management
const App = {
  async getModules() {
    const r = await Auth.request('/modules');
    return r.ok ? r.data.modules : [];
  },

  async getLoadouts() {
    const r = await Auth.request('/loadouts');
    return r.ok ? r.data.loadouts : [];
  },

  async getLoadout(id) {
    const r = await Auth.request('/loadouts/' + id);
    return r.ok ? r.data.loadout : null;
  },

  async createLoadout(name, description, module_ids) {
    return await Auth.request('/loadouts', 'POST', { name, description, module_ids });
  },

  async deleteLoadout(id) {
    return await Auth.request('/loadouts/' + id, 'DELETE');
  },

  async updateLoadout(id, name, description, module_ids) {
    return await Auth.request('/loadouts/' + id, 'PUT', { name, description, module_ids });
  }
};

// Sidebar account popup toggle
function initAccountPopup() {
  const avatar = document.getElementById('sidebar-avatar');
  const popup  = document.getElementById('account-popup');
  if (!avatar || !popup) return;

  const user = Auth.getUser();
  const logoutBtn = document.getElementById('btn-logout');
  if (logoutBtn) logoutBtn.addEventListener('click', (e) => { e.preventDefault(); Auth.logout(); });

  // Color mode toggles
  const colorToggle = document.getElementById('toggle-color');
  const lightToggle = document.getElementById('toggle-light');
  const darkToggle  = document.getElementById('toggle-dark');
  const applyTheme  = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('modiuld_theme', theme);
    if (lightToggle) lightToggle.checked = theme === 'light';
    if (darkToggle)  darkToggle.checked  = theme === 'dark';
  };
  const saved = localStorage.getItem('modiuld_theme') || 'dark';
  applyTheme(saved);
  if (colorToggle) colorToggle.checked = true;
  if (lightToggle) lightToggle.addEventListener('change', () => applyTheme('light'));
  if (darkToggle)  darkToggle.addEventListener('change',  () => applyTheme('dark'));

  avatar.addEventListener('click', (e) => { e.stopPropagation(); popup.classList.toggle('open'); });
  const closeBtn = document.getElementById('popup-close');
  if (closeBtn) closeBtn.addEventListener('click', () => popup.classList.remove('open'));
  document.addEventListener('click', (e) => {
    if (!popup.contains(e.target) && !avatar.contains(e.target)) popup.classList.remove('open');
  });
}

// Theme init on every page
(function() {
  const t = localStorage.getItem('modiuld_theme') || 'dark';
  document.documentElement.setAttribute('data-theme', t);
})();
