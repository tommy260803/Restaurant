document.addEventListener('DOMContentLoaded', function() {
    // Helper to get URL from blade-injected map
    function url(key) {
        if (window.REPORTES_URLS && window.REPORTES_URLS[key]) return window.REPORTES_URLS[key];
        throw new Error('REPORTES_URLS not defined or missing key: ' + key);
    }

    // Ingresos por día
    fetch(url('pagosPorDia'))
        .then(res => res.json())
        .then(json => {
            const ctx = document.getElementById('chartIngresosDia').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: json.labels.map(l => new Date(l).toLocaleDateString()),
                    datasets: [{
                        label: 'Ingresos S/.',
                        data: json.data,
                        borderColor: '#4caf50',
                        backgroundColor: 'rgba(76,175,80,0.1)',
                        tension: 0.3
                    }]
                },
                options: { responsive: true }
            });
        })
        .catch(err => console.error('Error cargando ingresos:', err));

    // Reservas por día
    fetch(url('reservasPorDia'))
        .then(res => res.json())
        .then(json => {
            const ctx = document.getElementById('chartReservasDia').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: json.labels.map(l => new Date(l).toLocaleDateString()),
                    datasets: [{
                        label: 'Reservas',
                        data: json.data,
                        backgroundColor: '#17a2b8'
                    }]
                },
                options: { responsive: true }
            });
        })
        .catch(err => console.error('Error cargando reservas:', err));

    // Ingresos por método
    fetch(url('pagosPorMetodo'))
        .then(res => res.json())
        .then(json => {
            const labels = json.data.map(r => r.metodo);
            const values = json.data.map(r => parseFloat(r.total));
            const ctx = document.getElementById('chartIngresosMetodo').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{ data: values, backgroundColor: ['#4caf50','#2196f3','#ff9800','#9c27b0','#607d8b'] }]
                },
                options: { responsive: true }
            });
        })
        .catch(err => console.error('Error cargando ingresos por método:', err));

    // Top clientes
    fetch(url('topClientes'))
        .then(res => res.json())
        .then(json => {
            const ul = document.getElementById('top-clientes');
            ul.innerHTML = '';
            if (!json.data || json.data.length === 0) {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `<em class="text-muted">Sin datos para mostrar</em>`;
                ul.appendChild(li);
                return;
            }
            json.data.forEach(c => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `<div><strong>${c.nombre}</strong><br><small class="text-muted">S/. ${parseFloat(c.total).toFixed(2)}</small></div>`;
                ul.appendChild(li);
            });
        })
        .catch(err => {
            console.error('Error cargando top clientes:', err);
            const ul = document.getElementById('top-clientes');
            ul.innerHTML = '';
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.innerHTML = `<em class="text-danger">Error al cargar datos</em>`;
            ul.appendChild(li);
        });
});