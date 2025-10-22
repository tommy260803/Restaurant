
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle.querySelector('i');
themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    themeIcon.classList.toggle('bx-moon');
    themeIcon.classList.toggle('bx-sun');

    // Save preference
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
});

// Check for saved theme preference
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
    themeIcon.classList.remove('bx-moon');
    themeIcon.classList.add('bx-sun');
}

// Actualizar gráfico en modo oscuro
themeToggle.addEventListener('click', () => {
    setTimeout(() => {
        statsChart.update();
    }, 300);
});
