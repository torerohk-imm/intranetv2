document.addEventListener('DOMContentLoaded', () => {
    const toggleSidebar = document.getElementById('toggleSidebar');
    const collapseSidebar = document.getElementById('collapseSidebar');
    const sidebar = document.getElementById('sidebar');

    if (toggleSidebar && sidebar) {
        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    if (collapseSidebar && sidebar) {
        collapseSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }

    const phraseContainer = document.getElementById('phraseOfDay');
    if (phraseContainer) {
        fetch('https://frasedeldia.azurewebsites.net/api/phrase')
            .then(response => response.ok ? response.json() : null)
            .then(data => {
                if (data && data.phrase) {
                    phraseContainer.innerHTML = `"${data.phrase}" <span class="d-block mt-2">${data.author ?? ''}</span>`;
                }
            })
            .catch(() => {
                phraseContainer.textContent = 'No se pudo cargar la frase del día.';
            });
    }

    const calendarEl = document.getElementById('eventsCalendar');
    if (calendarEl && window.FullCalendar) {
        const events = JSON.parse(calendarEl.dataset.events || '[]');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            locale: 'es',
            events,
        });
        calendar.render();
    }

    const statsChartCanvas = document.getElementById('statsChart');
    if (statsChartCanvas && window.Chart) {
        const chartData = JSON.parse(statsChartCanvas.dataset.stats || '{}');
        new Chart(statsChartCanvas, {
            type: 'doughnut',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    data: chartData.values || [],
                    backgroundColor: ['#0d6efd', '#6610f2', '#198754', '#fd7e14'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    document.querySelectorAll('[data-action="toggle-node"]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('d-none');
                button.querySelector('i').classList.toggle('bi-caret-down-fill');
                button.querySelector('i').classList.toggle('bi-caret-right-fill');
            }
        });
    });
});
