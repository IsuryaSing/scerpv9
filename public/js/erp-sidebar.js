(function () {
    const sidebar = document.getElementById('erp-sidebar');
    const menuButton = document.querySelector('.erp-menu-btn');

    function setSidebar(open) {
        document.body.classList.toggle('erp-nav-open', open);
        menuButton?.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    menuButton?.addEventListener('click', function () {
        setSidebar(!document.body.classList.contains('erp-nav-open'));
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            setSidebar(false);
        }
    });

    if (!sidebar) {
        return;
    }

    sidebar.addEventListener('click', function (event) {
        const toggleButton = event.target.closest('.erp-menu-toggle');

        if (!toggleButton || !sidebar.contains(toggleButton)) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const item = toggleButton.closest('.erp-menu-item.has-children');
        if (!item) {
            return;
        }

        const isOpen = item.classList.toggle('is-open');
        toggleButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    sidebar.querySelectorAll('.erp-menu-item.has-children > .erp-menu-row > .erp-menu-link[href="#"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const item = link.closest('.erp-menu-item.has-children');
            const toggleButton = item ? item.querySelector('.erp-menu-toggle') : null;

            if (toggleButton) {
                toggleButton.click();
            }
        });
    });
})();
