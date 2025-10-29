@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #dc3545, #fd7e14);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-black">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Alertas del Sistema
                        </h3>
                        <button class="btn btn-light btn-sm" onclick="marcarTodasLeidas()">
                            <i class="fas fa-check-double me-1"></i>
                            Marcar todas como leídas
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($alertas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center"><i class="fas fa-circle me-1 text-white"></i>Estado</th>
                                        <th><i class="fas fa-calendar me-1 text-white"></i>Fecha</th>
                                        <th><i class="fas fa-fish me-1 text-white"></i>Tipo</th>
                                        <th><i class="fas fa-message me-1 text-white"></i>Mensaje</th>
                                        <th class="text-center"><i class="fas fa-exclamation me-1 text-white"></i>Nivel</th>
                                        <th class="text-center"><i class="fas fa-cogs me-1 text-white"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alertas as $alerta)
                                    <tr class="{{ $alerta->leida ? 'table-light' : 'table-warning' }} align-middle">
                                        <td class="text-center">
                                            @if($alerta->leida)
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-check-circle me-1"></i>Leída
                                                </span>
                                            @else
                                                <span class="badge bg-danger fs-6 pulse">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Nueva
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $alerta->fecha_alerta->format('d/m/Y') }}</span>
                                                <small class="text-muted">{{ $alerta->fecha_alerta->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($alerta->tipo === 'tilapia')
                                                <span class="badge bg-primary fs-6">
                                                    <i class="fas fa-fish me-1"></i>Tilapia
                                                </span>
                                            @else
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-fish me-1"></i>Cachama
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="alert-message">
                                                {{ $alerta->mensaje }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @switch($alerta->nivel)
                                                @case('critical')
                                                    <span class="badge bg-danger fs-6">
                                                        <i class="fas fa-skull-crossbones me-1"></i>Crítico
                                                    </span>
                                                    @break
                                                @case('warning')
                                                    <span class="badge bg-warning text-dark fs-6">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Advertencia
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-info fs-6">
                                                        <i class="fas fa-info-circle me-1"></i>Info
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            @if(!$alerta->leida)
                                                <button class="btn btn-outline-success btn-sm" 
                                                        onclick="marcarLeida({{ $alerta->id }})">
                                                    <i class="fas fa-check me-1"></i>Marcar leída
                                                </button>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center p-3 bg-light">
                            {{ $alertas->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                            </div>
                            <h4 class="text-success mb-3">¡Todo está bajo control!</h4>
                            <p class="text-muted fs-5">No hay alertas activas. Todos los parámetros están dentro del rango normal.</p>
                            <div class="mt-4">
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Sistema funcionando correctamente
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.alert-message {
    font-size: 0.9rem;
    line-height: 1.4;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.badge {
    font-weight: 500;
}
</style>

<script>
function marcarLeida(id) {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Procesando...';
    
    fetch(`/alertas/${id}/marcar-leida`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                title: '¡Éxito!',
                text: 'Alerta marcada como leída',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-check me-1"></i>Marcar leída';
        Swal.fire('Error', 'No se pudo marcar la alerta', 'error');
    });
}

function marcarTodasLeidas() {
    Swal.fire({
        title: '¿Marcar todas como leídas?',
        text: 'Esta acción marcará todas las alertas como leídas',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check-double me-1"></i>Sí, marcar todas',
        cancelButtonText: '<i class="fas fa-times me-1"></i>Cancelar',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/alertas/marcar-todas-leidas', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(() => {
                Swal.fire({
                    title: '¡Completado!',
                    text: 'Todas las alertas han sido marcadas como leídas',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            });
        }
    });
}
</script>
@endsection