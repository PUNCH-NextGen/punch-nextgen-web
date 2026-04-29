(function () {
    'use strict';

    const navToggle = document.querySelector('.png-nav-toggle');
    const nav = document.querySelector('#png-primary-nav');

    if (navToggle && nav) {
        navToggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    const searchToggle = document.querySelector('.png-search-toggle');
    const searchPanel = document.querySelector('#png-search-panel');

    if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', function () {
            const isHidden = searchPanel.hasAttribute('hidden');

            if (isHidden) {
                searchPanel.removeAttribute('hidden');
                searchToggle.setAttribute('aria-expanded', 'true');

                const searchInput = searchPanel.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            } else {
                searchPanel.setAttribute('hidden', '');
                searchToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
})();
