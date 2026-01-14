document.addEventListener('DOMContentLoaded', function() {
    // Ingresos por día
    fetch('/reportes/pagos-por-dia')
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
    fetch('/reportes/reservas-por-dia')
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
    fetch('/reportes/pagos-por-metodo')
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
    fetch('/reportes/top-clientes')
        .then(res => res.json())
        .then(json => {
            const ul = document.getElementById('top-clientes');
            ul.innerHTML = '';
            json.data.forEach(c => {
                const li = document.createElement('li');
                li.className = 'list-group-item bg-transparent text-white';
                li.innerHTML = `<strong>${c.nombre}</strong><br><small class="text-muted">S/. ${parseFloat(c.total).toFixed(2)}</small>`;
                ul.appendChild(li);
            });
        })
        .catch(err => console.error('Error cargando top clientes:', err));
});