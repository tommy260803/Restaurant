document.addEventListener('DOMContentLoaded', () => {
    const video = document.querySelector('.video__presentation');
    const spinner = document.getElementById('spinner');
    const wrapper = document.querySelector('.wrapper');

    // Inicialmente mostrar solo el video
    spinner.style.display = 'none';
    
    // Función para manejar errores del video
    video.addEventListener('error', (e) => {
        console.error('Error cargando el video:', e);
        // Si hay error con el video, mostrar spinner y redirigir
        showSpinnerAndRedirect();
    });

    // Función para cuando el video no puede reproducirse
    video.addEventListener('canplaythrough', () => {
        console.log('Video listo para reproducir');
    });

    // Cuando el video termina
    video.addEventListener('ended', () => {
        showSpinnerAndRedirect();
    });

    // Si el video no se carga en 5 segundos, redirigir
    setTimeout(() => {
        if (video.readyState < 3) { // Si no está listo para reproducir
            console.log('Video tardó mucho en cargar, redirigiendo...');
            showSpinnerAndRedirect();
        }
    }, 5000);

    function showSpinnerAndRedirect() {
        // Ocultar video y mostrar spinner
        wrapper.style.opacity = '0.3';
        spinner.style.display = 'flex';
        
        // Añadir efecto de desenfoque
        wrapper.classList.add('blur-active');
        
        // Redirigir después de 2 segundos
        setTimeout(() => {
            window.location.href = '/login';
        }, 2000);
    }

    // Intentar reproducir el video manualmente si autoplay falla
    const playPromise = video.play();
    if (playPromise !== undefined) {
        playPromise.catch(error => {
            console.log('Autoplay falló, intentando reproducir manualmente:', error);
            // Si autoplay falla, mostrar spinner inmediatamente
            showSpinnerAndRedirect();
        });
    }
});