document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            tabContents.forEach(content => content.classList.remove('active'));

            const targetId = tab.getAttribute('data-tab');
            document.getElementById(`${targetId}-section`).classList.add('active');
        });
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();

            document.querySelectorAll('.styled-table').forEach(table => {
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                Array.from(rows).forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    row.style.display = rowText.includes(searchTerm) ? 'table-row' : 'none';
                });
            });
        });
    }

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    e.preventDefault();
                    input.style.borderColor = 'var(--danger)';
                    input.addEventListener('input', function () {
                        input.style.borderColor = '';
                    }, { once: true });
                    valid = false;
                }
            });

            return valid;
        });
    });
});
