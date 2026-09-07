(function () {
    const rowsContainer = document.getElementById('subject-rows');
    const addButton = document.getElementById('add-subject-row');

    if (!rowsContainer) {
        return;
    }

    function reindexRows() {
        rowsContainer.querySelectorAll('.subject-row').forEach(function (row, index) {
            row.querySelectorAll('[name]').forEach(function (input) {
                input.name = input.name.replace(/rows\[\d+]/, 'rows[' + index + ']');
            });

            const slNoInput = row.querySelector('input[name$="[sl_no]"]');
            if (slNoInput && !slNoInput.dataset.manual) {
                slNoInput.value = index + 1;
            }
        });
    }

    function bindRemoveButtons() {
        rowsContainer.querySelectorAll('.remove-subject-row').forEach(function (button) {
            button.onclick = function () {
                const rows = rowsContainer.querySelectorAll('.subject-row');
                if (rows.length <= 1) {
                    return;
                }

                button.closest('.subject-row').remove();
                reindexRows();
            };
        });
    }

    if (addButton) {
        addButton.addEventListener('click', function () {
            const lastRow = rowsContainer.querySelector('.subject-row:last-child');
            if (!lastRow) {
                return;
            }

            const clone = lastRow.cloneNode(true);
            clone.querySelectorAll('input').forEach(function (input) {
                if (input.type === 'hidden') {
                    input.remove();
                } else {
                    input.value = '';
                    input.removeAttribute('readonly');
                }
            });

            clone.querySelector('td:first-child').innerHTML = '<span class="erp-muted">-</span>';

            rowsContainer.appendChild(clone);
            reindexRows();
            bindRemoveButtons();
        });
    }

    rowsContainer.querySelectorAll('input[name$="[sl_no]"]').forEach(function (input) {
        input.addEventListener('input', function () {
            input.dataset.manual = '1';
        });
    });

    bindRemoveButtons();
})();
