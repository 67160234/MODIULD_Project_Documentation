/**
 * lang.js
 * Handles language switching (EN/TH) across the application.
 */

const translations = {
  en: {
    // Sidebar & Popup
    "account": "Account",
    "switch_account": "Switch account",
    "logout": "Logout",
    "color_mode": "Color Mode",
    "default": "Default",
    "light": "Light",
    "dark": "Dark",
    "color_settings": "Color Settings",
    "menu": "Menu",
    "your_loadout": "Your Loadout",
    "loadouts": "Loadouts",
    "create_new_loadout": "+ Create New Loadout",

    // Dashboard
    "dashboard_title": "Your Loadout",
    "dashboard_subtitle": "Select or create a workspace to manage your modules.",
    "loading_modules": "Loading modules...",
    
    // Create Loadout
    "create_loadout_title": "Create Loadout",
    "create_loadout_subtitle": "Create and name your loadout.",
    "loadout_name": "Loadout Name",
    "loadout_name_placeholder": "Loadout Untitled",
    "btn_reset": "Reset",
    "btn_create": "Create",
    "ai_assistant": "Feature Recommendation Assistant",
    "ai_placeholder": "Enter a description of your task, such as 'Booking details' or 'Patient records'.",

    // Settings
    "settings_title": "Color Settings",
    "settings_subtitle": "เลือกโหมดสีของเว็บแอป — ใช้งานได้ทีละโหมด",
    "settings_note": "* ไม่กระทบหน้าแรก, Login และ Register",
    "desc_default": "พื้นหลังสีม่วง‑น้ำเงินเข้ม ตัวอักษรสีขาว (ค่าเริ่มต้น)",
    "desc_light": "พื้นหลังสีขาว‑เทาอ่อน ตัวอักษรสีดำ",
    "desc_dark": "พื้นหลังสีดำ‑เทาเข้ม ตัวอักษรสีขาว",
    "btn_save": "บันทึกการตั้งค่า",
    "save_feedback": "บันทึกแล้ว! ธีมถูกเปลี่ยนแล้ว",
    "btn_back_dashboard": "← Dashboard"
  },
  th: {
    // Sidebar & Popup
    "account": "บัญชี",
    "switch_account": "สลับบัญชี",
    "logout": "ออกจากระบบ",
    "color_mode": "โหมดสี",
    "default": "ค่าเริ่มต้น",
    "light": "สว่าง",
    "dark": "มืด",
    "color_settings": "ตั้งค่าสี",
    "menu": "เมนู",
    "your_loadout": "โหลดเอาต์ของคุณ",
    "loadouts": "โหลดเอาต์",
    "create_new_loadout": "+ สร้างโหลดเอาต์ใหม่",

    // Dashboard
    "dashboard_title": "โหลดเอาต์ของคุณ",
    "dashboard_subtitle": "เลือกหรือสร้างพื้นที่โหลดเอาต์เพื่อจัดการโมดูลของคุณ",
    "loading_modules": "กำลังโหลดโมดูล...",
    
    // Create Loadout
    "create_loadout_title": "สร้างโหลดเอาต์",
    "create_loadout_subtitle": "สร้างและตั้งชื่อโหลดเอาต์ของคุณ",
    "loadout_name": "ชื่อโหลดเอาต์",
    "loadout_name_placeholder": "โหลดเอาต์ไม่มีชื่อ",
    "btn_reset": "รีเซ็ต",
    "btn_create": "สร้าง",
    "ai_assistant": "ผู้ช่วยแนะนำฟีเจอร์",
    "ai_placeholder": "พิมพ์คำอธิบายงานของคุณ เช่น 'ข้อมูลการจอง' หรือ 'ประวัติคนไข้'",

    // Settings
    "settings_title": "ตั้งค่าสี",
    "settings_subtitle": "เลือกโหมดสีของเว็บแอป — ใช้งานได้ทีละโหมด",
    "settings_note": "* ไม่กระทบหน้าแรก, ล็อกอิน และ สมัครสมาชิก",
    "desc_default": "พื้นหลังสีม่วง‑น้ำเงินเข้ม ตัวอักษรสีขาว (ค่าเริ่มต้น)",
    "desc_light": "พื้นหลังสีขาว‑เทาอ่อน ตัวอักษรสีดำ",
    "desc_dark": "พื้นหลังสีดำ‑เทาเข้ม ตัวอักษรสีขาว",
    "btn_save": "บันทึกการตั้งค่า",
    "save_feedback": "บันทึกแล้ว! ธีมถูกเปลี่ยนแล้ว",
    "btn_back_dashboard": "← แดชบอร์ด"
  }
};

(function() {
  function applyLanguage(lang) {
    const t = translations[lang] || translations['en'];

    // Translate textContent elements
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      if (t[key] !== undefined) el.textContent = t[key];
    });

    // Translate placeholder attributes
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
      const key = el.getAttribute('data-i18n-placeholder');
      if (t[key] !== undefined) el.setAttribute('placeholder', t[key]);
    });

    // Update language badge
    document.querySelectorAll('.lang-badge').forEach(badge => {
      badge.textContent = lang.toUpperCase();
    });
  }

  function toggleLanguage() {
    let currentLang = localStorage.getItem('modiuld_lang') || 'en';
    const newLang = currentLang === 'en' ? 'th' : 'en';
    localStorage.setItem('modiuld_lang', newLang);
    applyLanguage(newLang);
  }

  // Initial load
  document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('modiuld_lang') || 'en';
    applyLanguage(savedLang);

    // Bind event to all language badges
    document.querySelectorAll('.lang-badge').forEach(badge => {
      badge.addEventListener('click', toggleLanguage);
    });
  });

  // Listen for specific translation requests (e.g. dynamic elements created later)
  window.translateElement = function(el, key) {
    const lang = localStorage.getItem('modiuld_lang') || 'en';
    if (translations[lang] && translations[lang][key]) {
      el.textContent = translations[lang][key];
    }
  };
})();
