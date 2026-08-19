document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const close = document.querySelector('[data-menu-close]');
    const backdrop = document.querySelector('[data-menu-backdrop]');
    const nav = document.getElementById('mobile-nav');

    if (!toggle || !nav) {
        return;
    }

    const openMenu = () => {
        nav.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');
    };

    const closeMenu = () => {
        nav.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
    };

    toggle.addEventListener('click', openMenu);
    close?.addEventListener('click', closeMenu);
    backdrop?.addEventListener('click', closeMenu);
});
