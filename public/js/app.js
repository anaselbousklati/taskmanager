document.addEventListener('DOMContentLoaded', function () {

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .5s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 500);
        }, 4000);
    });

    const passwordInput  = document.getElementById('password');
    const confirmInput   = document.getElementById('password_confirm');

    if (passwordInput && confirmInput) {
        confirmInput.addEventListener('input', function () {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Wachtwoorden komen niet overeen.');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(function (row) {
        const editLink = row.querySelector('a.btn-secondary');
        if (!editLink) return;

        row.style.cursor = 'pointer';
        row.addEventListener('click', function (e) {
            // Negeer klik op knoppen en formulieren
            if (e.target.closest('.actions')) return;
            editLink.click();
        });
    });

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const soon  = new Date(today);
    soon.setDate(soon.getDate() + 3);

    document.querySelectorAll('.table td').forEach(function (cell) {
        const text = cell.textContent.trim();
        const dateMatch = text.match(/(\d{2})\s+(\w{3})\s+(\d{4})/);
        if (!dateMatch) return;

        const cellDate = new Date(text);
        if (isNaN(cellDate.getTime())) return;

        if (cellDate >= today && cellDate <= soon && !cell.classList.contains('text-danger')) {
            cell.style.color = '#d97706'; 
            cell.title = 'Deadline nadert!';
        }
    });

});
