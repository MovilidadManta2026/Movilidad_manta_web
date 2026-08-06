import './bootstrap';
import 'flowbite';

const pages = {
    '/': 'home',
    '/la-ciudad': 'city',
    '/mision-vision': 'missionVision',
    '/servicios': 'services',
    '/noticias': 'news',
    '/transparencia': 'accountability',
    '/transparencia/rendicion-de-cuentas': 'accountability',
    '/transparencia/lotaip': 'lotaip',
    '/contacto': 'contact',
};

const loader = document.querySelector('#page-loader');
const toast = document.querySelector('#toast');
let toastTimer;
let pendingServiceSearch = '';
let activeBulletinModal = null;
let activeAnnouncementModal = null;
let bulletinCarouselPaused = false;

function ensureBulletinBackdrop() {
    let backdrop = document.querySelector('[data-bulletin-backdrop]');

    if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.dataset.bulletinBackdrop = 'true';
        backdrop.className = 'bulletin-page-backdrop';
        document.body.appendChild(backdrop);
    }

    backdrop.addEventListener('click', closeBulletinModal, { once: true });
    return backdrop;
}

function openBulletinModal(modal) {
    if (!modal) return;
    closeBulletinModal();
    activeBulletinModal = modal;
    const backdrop = ensureBulletinBackdrop();
    backdrop.classList.add('is-active');
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    modal.style.zIndex = '1001';
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    modal.querySelector('button, a, input, textarea, select')?.focus();
}

function closeBulletinModal() {
    if (!activeBulletinModal) return;
    activeBulletinModal.classList.add('hidden');
    activeBulletinModal.classList.remove('is-active');
    activeBulletinModal.style.display = 'none';
    activeBulletinModal.setAttribute('aria-hidden', 'true');
    document.querySelector('[data-bulletin-backdrop]')?.classList.remove('is-active');
    document.body.classList.remove('modal-open');
    activeBulletinModal = null;
}

function openAnnouncementModal(modal) {
    if (!modal) return;
    activeAnnouncementModal = modal;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    modal.querySelector('button, a')?.focus();
}

function closeAnnouncementModal() {
    if (!activeAnnouncementModal) return;
    activeAnnouncementModal.classList.add('hidden');
    activeAnnouncementModal.style.display = 'none';
    activeAnnouncementModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
    activeAnnouncementModal = null;
}

function currentPage(path = window.location.pathname) {
    return pages[path] || 'home';
}

function showLoader() {
    loader?.classList.add('is-active');
}

function hideLoader() {
    window.setTimeout(() => loader?.classList.remove('is-active'), 260);
}

function showToast(message) {
    if (!toast) return;
    window.clearTimeout(toastTimer);
    toast.textContent = message;
    toast.classList.add('show');
    toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2600);
}

function renderPage(path = window.location.pathname) {
    const page = currentPage(path);

    document.querySelectorAll('[data-page]').forEach((view) => {
        view.classList.toggle('active', view.dataset.page === page);
    });

    document.querySelectorAll('[data-nav]').forEach((link) => {
        link.classList.toggle('active', link.dataset.nav === page);
    });

    if (page === 'accountability' || page === 'lotaip') {
        document.querySelectorAll('[data-nav="accountability"]').forEach((link) => link.classList.add('active'));
    }

    document.querySelector('#main-nav')?.classList.remove('open');
    document.querySelector('[data-drawer-hide="mobile-navigation"]')?.click();

    if (page === 'services' && pendingServiceSearch) {
        const input = document.querySelector('[data-service-search]');
        input.value = pendingServiceSearch;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        pendingServiceSearch = '';
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
    hideLoader();
}

document.querySelectorAll('[data-route]').forEach((link) => {
    link.addEventListener('click', (event) => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('http')) return;
        showLoader();
    });
});

window.addEventListener('popstate', () => {
    showLoader();
    renderPage();
});

document.querySelector('[data-menu]')?.addEventListener('click', () => {
    document.querySelector('#main-nav')?.classList.toggle('open');
});

document.querySelector('[data-open-search]')?.addEventListener('click', () => {
    document.querySelector('#global-search')?.focus();
});

document.querySelector('[data-modal-search-form]')?.addEventListener('submit', (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    pendingServiceSearch = new FormData(form).get('q')?.toString() || '';
    document.querySelector('[data-modal-hide="search-modal"]')?.click();
    showLoader();
    const query = pendingServiceSearch ? `?q=${encodeURIComponent(pendingServiceSearch)}` : '';
    window.location.href = `/servicios${query}`;
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-bulletin-target]');
    if (!button) return;
    event.preventDefault();
    openBulletinModal(document.querySelector(`#${button.dataset.bulletinTarget}`));
});

document.addEventListener('keydown', (event) => {
    const button = event.target.closest('[data-bulletin-target]');
    if (!button || !['Enter', ' '].includes(event.key)) return;
    event.preventDefault();
    openBulletinModal(document.querySelector(`#${button.dataset.bulletinTarget}`));
});

document.querySelectorAll('[data-bulletin-hide]').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();
        closeBulletinModal();
    });
});

document.querySelector('[data-announcement-hide]')?.addEventListener('click', (event) => {
    event.preventDefault();
    closeAnnouncementModal();
});

document.querySelector('#announcement-modal')?.addEventListener('click', (event) => {
    if (event.target.id === 'announcement-modal') {
        closeAnnouncementModal();
    }
});

document.querySelectorAll('[data-bulletin-carousel]').forEach((carousel) => {
    const cards = Array.from(carousel.children);
    if (cards.length > 1 && !carousel.dataset.cloned) {
        cards.forEach((card) => {
            const clone = card.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            carousel.appendChild(clone);
        });
        carousel.dataset.cloned = 'true';
    }

    carousel.addEventListener('mouseenter', () => {
        bulletinCarouselPaused = true;
    });
    carousel.addEventListener('mouseleave', () => {
        bulletinCarouselPaused = false;
    });
    carousel.addEventListener('touchstart', () => {
        bulletinCarouselPaused = true;
    }, { passive: true });
    carousel.addEventListener('touchend', () => {
        window.setTimeout(() => {
            bulletinCarouselPaused = false;
        }, 2500);
    });

    window.setInterval(() => {
        if (bulletinCarouselPaused || activeBulletinModal || carousel.scrollWidth <= carousel.clientWidth) return;
        const firstCard = carousel.querySelector('.bulletin-card');
        const gap = Number.parseFloat(window.getComputedStyle(carousel).columnGap || '18') || 18;
        const step = (firstCard?.getBoundingClientRect().width || carousel.clientWidth * 0.78) + gap;
        const loopPoint = carousel.scrollWidth / 2;

        if (carousel.scrollLeft >= loopPoint - step) {
            carousel.scrollTo({ left: 0, behavior: 'auto' });
            return;
        }

        carousel.scrollTo({ left: carousel.scrollLeft + step, behavior: 'smooth' });
    }, 3400);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeBulletinModal();
        closeAnnouncementModal();
    }
});

document.querySelectorAll('[data-tabs] button').forEach((button) => {
    button.addEventListener('click', () => {
        const tabs = button.closest('[data-tabs]');
        tabs?.querySelectorAll('button').forEach((tab) => tab.classList.remove('active'));
        button.classList.add('active');

        const filter = button.dataset.filter;
        if (!filter) return;
        document.querySelectorAll('[data-service-card]').forEach((card) => {
            const visible = filter === 'all' || card.dataset.category === filter;
            card.classList.toggle('is-hidden', !visible);
        });
    });
});

document.querySelector('[data-service-search]')?.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    document.querySelectorAll('[data-service-card]').forEach((card) => {
        card.classList.toggle('is-hidden', !card.textContent.toLowerCase().includes(query));
    });
});

document.querySelectorAll('[data-accordion] > article > button').forEach((button) => {
    button.addEventListener('click', () => {
        const item = button.parentElement;
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('[data-accordion] > article').forEach((article) => {
            article.classList.remove('open');
            article.querySelector('i').textContent = '⌄';
        });
        if (!isOpen) {
            item.classList.add('open');
            button.querySelector('i').textContent = '⌃';
        }
    });
});

document.querySelectorAll('[data-toast]').forEach((element) => {
    element.addEventListener('click', (event) => {
        event.preventDefault();
        showToast(element.dataset.toast);
    });
});

document.querySelector('[data-contact-form]')?.addEventListener('submit', (event) => {
    event.preventDefault();
    showToast('Mensaje enviado correctamente. Te contactaremos pronto.');
    event.currentTarget.reset();
});

document.querySelector('[data-subscribe-form]')?.addEventListener('submit', (event) => {
    event.preventDefault();
    showToast('Suscripción registrada correctamente.');
    event.currentTarget.reset();
});

window.addEventListener('load', () => {
    renderPage();
    window.setTimeout(() => {
        if (window.location.pathname === '/') {
            openAnnouncementModal(document.querySelector('#announcement-modal'));
        }
    }, 650);
});
