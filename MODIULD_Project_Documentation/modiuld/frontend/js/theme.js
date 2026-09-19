/**
 * theme.js
 * Applies the saved color theme (default / light / dark) on every page load.
 * - Reads `modiuld_theme` from localStorage
 * - Adds the matching CSS class (theme-default / theme-light / theme-dark) to <html>
 * - For pages that should NOT follow user theme (index, login, register),
 *   simply do NOT include this script.
 */
(function () {
  const VALID = ['default', 'light', 'dark'];
  const saved = localStorage.getItem('modiuld_theme') || 'default';
  const theme = VALID.includes(saved) ? saved : 'default';

  const html = document.documentElement;
  html.classList.remove('theme-default', 'theme-light', 'theme-dark');
  html.classList.add('theme-' + theme);
  html.setAttribute('data-theme', theme);

  // --- Dashboard toggle wiring (optional UI) ---
  document.addEventListener('DOMContentLoaded', () => {
    const chkColor = document.getElementById('toggle-color');
    const chkLight = document.getElementById('toggle-light');
    const chkDark  = document.getElementById('toggle-dark');
    if (!chkColor || !chkLight || !chkDark) return;

    // Sync initial state
    chkLight.checked = (theme === 'light');
    chkDark.checked  = (theme === 'dark');
    chkColor.checked = (theme !== 'default');

    function setTheme(name) {
      localStorage.setItem('modiuld_theme', name);
      html.classList.remove('theme-default', 'theme-light', 'theme-dark');
      html.classList.add('theme-' + name);
      html.setAttribute('data-theme', name);
    }

    chkColor.addEventListener('change', () => {
      if (!chkColor.checked) {
        chkLight.checked = false;
        chkDark.checked  = false;
        setTheme('default');
      } else {
        // Turn on dark by default when master enabled without explicit pick
        if (!chkLight.checked && !chkDark.checked) {
          chkDark.checked = true;
          setTheme('dark');
        }
      }
    });

    chkLight.addEventListener('change', () => {
      if (chkLight.checked) {
        chkDark.checked  = false;
        chkColor.checked = true;
        setTheme('light');
      }
    });

    chkDark.addEventListener('change', () => {
      if (chkDark.checked) {
        chkLight.checked = false;
        chkColor.checked = true;
        setTheme('dark');
      }
    });
  });
})();
