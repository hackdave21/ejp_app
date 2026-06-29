const defaultLanguage = 'fr';
let currentLanguage = localStorage.getItem('language') || defaultLanguage;
let translations = {};

async function loadTranslations(lang) {
    try {
        const response = await fetch(`assets/lang/${lang}.json`);
        translations = await response.json();
    } catch (error) {
        console.error('Error loading translations:', error);
    }
}

function translatePage() {
    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        const translation = getNestedTranslation(translations, key);
        if (translation) {
            if (element.tagName === 'INPUT' && element.type === 'submit') {
                element.value = translation;
            } else if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                element.placeholder = translation;
            } else {
                element.innerHTML = translation;
            }
        }
    });
    
    // Update active state in language switchers
    document.querySelectorAll('.lang-switcher').forEach(btn => {
        if (btn.getAttribute('data-lang') === currentLanguage) {
            btn.classList.add('font-bold');
            if (btn.closest('.fixed') && window.location.pathname.includes('login.html')) {
                btn.classList.add('text-accent');
                btn.classList.remove('text-gold');
            } else {
                btn.classList.add('text-gold');
                btn.classList.remove('text-accent');
            }
            btn.classList.remove('text-white', 'text-black', 'dark:text-white', 'opacity-70');
        } else {
            btn.classList.remove('text-gold', 'text-accent', 'font-bold');
            btn.classList.add('text-black', 'dark:text-white', 'opacity-70');
        }
    });
    
    document.documentElement.lang = currentLanguage;
    window.translations = translations;
    document.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: currentLanguage, translations } }));
}

function getNestedTranslation(obj, path) {
    return path.split('.').reduce((acc, part) => acc && acc[part], obj);
}

async function changeLanguage(lang) {
    if (currentLanguage === lang) return;
    currentLanguage = lang;
    localStorage.setItem('language', lang);
    await loadTranslations(lang);
    translatePage();
}

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    await loadTranslations(currentLanguage);
    translatePage();
    
    // Setup switchers
    document.body.addEventListener('click', (e) => {
        const switcher = e.target.closest('.lang-switcher');
        if (switcher) {
            e.preventDefault();
            const lang = switcher.getAttribute('data-lang');
            changeLanguage(lang);
        }
    });
});
