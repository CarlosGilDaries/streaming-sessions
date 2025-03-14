document.addEventListener('DOMContentLoaded', function () {
    let deviceId, csrfToken; 

    // Obtener el ID de la película desde la URL actual
    const pathParts = window.location.pathname.split('/');
    const movieId = pathParts[pathParts.length - 1]; // Extraer el último segmento de la URL

    // Obtener el CSRF Token antes de hacer cualquier otra solicitud
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Solicitar el device_id desde el backend
    fetch(`/movies/${movieId}/get-device-id`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error("No se ha encontrado el device_id en la cookie");
            return;
        }

        deviceId = data.device_id;  // Asignar deviceId después de obtenerlo de la respuesta

        // Solicitar la ruta de inicio de la transmisión
        fetch('/start-stream', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'User-Device-Id': deviceId,
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error === 'limit_reached') {
                window.location.href = data.redirect; // Redirigir a la página
                return;
            }
            console.log(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });

        // Cada 30 segundos, envía una petición POST al backend para actualizar el stream activo
        setInterval(() => {
            if (deviceId) {  
                fetch('/keep-alive', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'User-Device-Id': deviceId, 
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => console.log('Stream actualizado:', data))
                .catch(error => console.error('Error al actualizar stream:', error));
            }
        }, 30000);
    })
    .catch(error => {
        console.error('Error al obtener device_id:', error);
    });
});


