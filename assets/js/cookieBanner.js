document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('cookie-banner');
    const acceptBtn = document.getElementById('cookie-accept');
    const declineBtn = document.getElementById('cookie-decline');

    if (!banner || !acceptBtn || !declineBtn) {
        return;
    }

    const consent = localStorage.getItem('cookieConsent');

    if (!consent) {
        banner.classList.remove('d-none');
        setTimeout(() => {
            banner.classList.add('is-visible');
        }, 100);
    }

    acceptBtn.addEventListener('click', () => {
        localStorage.setItem('cookieConsent', 'accepted');
        hideBanner();
    });

    declineBtn.addEventListener('click', () => {
        localStorage.setItem('cookieConsent', 'declined');
        hideBanner();
    });

    function hideBanner() {
        banner.classList.remove('is-visible');
        setTimeout(() => {
            banner.classList.add('d-none');
        }, 250);
    }
});