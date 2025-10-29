let ultimasAlertasIds = [];

function verificarNuevasAlertas() {
    fetch('/api/alertas')
        .then(response => response.json())
        .then(data => {
            const nuevasAlertas = data.filter(a => !ultimasAlertasIds.includes(a.id) && !a.leida);
            
            if (nuevasAlertas.length > 0 && ultimasAlertasIds.length > 0) {
                nuevasAlertas.forEach(alerta => {
                    Swal.fire({
                        title: '⚠️ Nueva Alerta',
                        html: `<div class='text-left'>
                                <p class='mb-2'><strong>${alerta.tipo.toUpperCase()}</strong></p>
                                <p>${alerta.mensaje}</p>
                                <p class='text-sm text-gray-500 mt-2'>${new Date(alerta.fecha_alerta).toLocaleString()}</p>
                               </div>`,
                        icon: 'warning',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#16a34a',
                        timer: 10000,
                        timerProgressBar: true
                    });
                });
            }
            
            ultimasAlertasIds = data.map(a => a.id);
        })
        .catch(error => console.error('Error:', error));
}

setInterval(verificarNuevasAlertas, 30000);
verificarNuevasAlertas();
