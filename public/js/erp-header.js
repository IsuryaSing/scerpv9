(function () {
    const config = window.erpHeaderConfig || {};
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const searchInput = document.getElementById('erp-search-input');
    const searchResults = document.getElementById('erp-search-results');
    const searchList = document.getElementById('erp-search-list');
    const unitSelect = document.getElementById('erp-unit-select');
    const sessionSelect = document.getElementById('erp-session-select');
    const userToggle = document.getElementById('erp-user-toggle');
    const userMenu = document.getElementById('erp-user-menu');

    let searchTimer = null;

    function postJson(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(body),
        }).then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, data: data };
            });
        });
    }

    function hideSearchResults() {
        if (searchResults) {
            searchResults.style.display = 'none';
        }
        if (searchList) {
            searchList.innerHTML = '';
        }
    }

    function renderSearchResults(items) {
        if (!searchResults || !searchList) {
            return;
        }

        searchList.innerHTML = '';

        if (!items.length) {
            hideSearchResults();
            return;
        }

        items.forEach(function (item) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.className = 'tcode_link';
            link.href = item.url || '#';
            link.textContent = item.label || (item.t_code + ' - ' + item.file_name);
            li.appendChild(link);
            searchList.appendChild(li);
        });

        searchResults.style.display = 'block';
    }

    function runSearch() {
        if (!searchInput || !config.searchUrl) {
            return;
        }

        const query = searchInput.value.trim();
        if (!query) {
            hideSearchResults();
            return;
        }

        fetch(config.searchUrl + '?q=' + encodeURIComponent(query), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                renderSearchResults(data.results || []);
            })
            .catch(function () {
                hideSearchResults();
            });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(runSearch, 250);
        });

        searchInput.addEventListener('focus', function () {
            if (searchInput.value.trim()) {
                runSearch();
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (searchResults && !searchResults.contains(event.target) && event.target !== searchInput) {
            hideSearchResults();
        }

        if (userMenu && userToggle && !userMenu.contains(event.target) && !userToggle.contains(event.target)) {
            userMenu.style.display = 'none';
        }
    });

    if (unitSelect && config.changeUnitUrl) {
        unitSelect.addEventListener('change', function () {
            const selectedUnit = unitSelect.value;
            const previousUnit = unitSelect.dataset.currentUnit || selectedUnit;
            unitSelect.disabled = true;

            postJson(config.changeUnitUrl, { unit: selectedUnit })
                .then(function (result) {
                    if (!result.ok || !result.data.success) {
                        alert(result.data.message || 'Unable to switch unit.');
                        unitSelect.value = previousUnit;
                        return;
                    }

                    unitSelect.dataset.currentUnit = selectedUnit;
                    window.location.href = result.data.redirect || window.location.href;
                })
                .catch(function () {
                    alert('Unable to switch unit.');
                    unitSelect.value = previousUnit;
                })
                .finally(function () {
                    unitSelect.disabled = false;
                });
        });

        unitSelect.dataset.currentUnit = unitSelect.value;
    }

    if (sessionSelect && config.changeSessionUrl) {
        sessionSelect.addEventListener('change', function () {
            postJson(config.changeSessionUrl, { session_year: sessionSelect.value }).catch(function () {
                alert('Unable to change session year.');
            });
        });
    }

    if (userToggle && userMenu) {
        userToggle.addEventListener('click', function () {
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });
    }
})();
