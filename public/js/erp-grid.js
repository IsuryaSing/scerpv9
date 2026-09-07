(function () {
    function parseJson(raw, fallback) {
        if (!raw) {
            return fallback;
        }

        try {
            return JSON.parse(raw);
        } catch (error) {
            return fallback;
        }
    }

    function buildUrl(baseUrl, params) {
        const search = new URLSearchParams();

        Object.keys(params).forEach(function (key) {
            const value = params[key];
            if (value !== null && value !== undefined && value !== '') {
                search.set(key, String(value));
            }
        });

        const queryString = search.toString();
        return queryString ? baseUrl + '?' + queryString : baseUrl;
    }

    function getPanelState(panel) {
        return parseJson(panel.dataset.erpGridState, {});
    }

    function setPanelState(panel, state) {
        panel.dataset.erpGridState = JSON.stringify(state);
    }

    function collectParams(panel, overrides) {
        const state = Object.assign({}, getPanelState(panel), overrides || {});
        const form = panel.querySelector('.erp-filter-form');

        if (form) {
            form.querySelectorAll('select[name], input[name]').forEach(function (field) {
                state[field.name] = field.value;
            });
        }

        const footer = panel.querySelector('.erp-grid-footer');
        if (footer) {
            const perPage = footer.querySelector('[data-erp-grid-per-page]')?.value;
            if (perPage) {
                state.per_page = perPage;
            }
        }

        if (!state.page) {
            state.page = 1;
        }

        return state;
    }

    function loadGrid(panel, overrides) {
        const url = panel.dataset.erpAjaxUrl;
        const params = collectParams(panel, overrides);
        const results = panel.querySelector('[data-erp-grid-results]');

        if (!url || !results) {
            return Promise.resolve();
        }

        panel.classList.add('is-loading');

        return fetch(buildUrl(url, params), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Failed to load grid data.');
                }

                return response.json();
            })
            .then(function (data) {
                results.innerHTML = data.html;
                setPanelState(panel, params);
                bindGridFooter(panel);
            })
            .catch(function () {
                window.location.href = buildUrl(url, params);
            })
            .finally(function () {
                panel.classList.remove('is-loading');
            });
    }

    function bindGridFooter(panel) {
        const footer = panel.querySelector('.erp-grid-footer');
        if (!footer) {
            return;
        }

        const perPageSelect = footer.querySelector('[data-erp-grid-per-page]');
        const pageInput = footer.querySelector('[data-erp-grid-page-input]');
        const searchButton = footer.querySelector('[data-erp-grid-search]');
        const refreshButton = footer.querySelector('[data-erp-grid-refresh]');

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function () {
                loadGrid(panel, { page: 1, per_page: perPageSelect.value });
            });
        }

        footer.querySelectorAll('[data-erp-grid-page]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (button.disabled) {
                    return;
                }

                loadGrid(panel, { page: parseInt(button.dataset.erpGridPage, 10) });
            });
        });

        if (pageInput) {
            pageInput.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter') {
                    return;
                }

                event.preventDefault();
                const page = parseInt(pageInput.value, 10);
                if (!Number.isNaN(page)) {
                    loadGrid(panel, { page: page });
                }
            });

            pageInput.addEventListener('blur', function () {
                const page = parseInt(pageInput.value, 10);
                const currentPage = parseInt(footer.dataset.currentPage || '1', 10);

                if (!Number.isNaN(page) && page !== currentPage) {
                    loadGrid(panel, { page: page });
                }
            });
        }

        if (searchButton) {
            searchButton.addEventListener('click', function () {
                const filterForm = panel.querySelector('.erp-filter-form');
                const firstField = filterForm?.querySelector('select, input');
                if (firstField) {
                    firstField.focus();
                }
            });
        }

        if (refreshButton) {
            refreshButton.addEventListener('click', function () {
                loadGrid(panel, { page: parseInt(footer.dataset.currentPage || '1', 10) });
            });
        }
    }

    function bindAjaxGrid(panel) {
        const filterForm = panel.querySelector('.erp-filter-form');

        if (filterForm) {
            filterForm.addEventListener('submit', function (event) {
                event.preventDefault();
                loadGrid(panel, { page: 1 });
            });
        }

        panel.addEventListener('click', function (event) {
            const sortLink = event.target.closest('[data-erp-grid-sort]');
            if (sortLink && panel.contains(sortLink)) {
                event.preventDefault();
                loadGrid(panel, {
                    sort: sortLink.dataset.erpGridSort,
                    dir: sortLink.dataset.erpGridDir,
                    page: 1,
                });
            }
        });

        bindGridFooter(panel);
    }

    document.querySelectorAll('.erp-ajax-grid').forEach(function (panel) {
        if (panel.dataset.erpAjaxUrl) {
            bindAjaxGrid(panel);
        }
    });
})();
