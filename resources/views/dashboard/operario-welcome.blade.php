@extends('layouts.app')

@section('title', 'Dashboard Operario')

@section('content')
<div class="space-y-6">
    <!-- Bienvenida -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">¡Bienvenido, {{ $usuario->nombre }}!</h1>
                <p class="text-gray-600 mt-1">Panel de Control - Operario</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Último acceso</p>
                <p class="text-lg font-semibold text-green-600">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Sensores Activos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-flask text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sensores Activos</p>
                    <p class="text-2xl font-bold text-gray-900" id="sensores-activos">3</p>
                </div>
            </div>
        </div>

        <!-- Alertas Pendientes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Alertas Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900" id="alertas-pendientes">0</p>
                </div>
            </div>
        </div>

        <!-- Lecturas Hoy -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lecturas Hoy</p>
                    <p class="text-2xl font-bold text-gray-900" id="lecturas-hoy">--</p>
                </div>
            </div>
        </div>

        <!-- Estado Sistema -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Estado Sistema</p>
                    <p class="text-lg font-bold text-green-600">Operativo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Monitoreo de Sensores -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-4 rounded-full bg-green-100 text-green-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-flask text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Monitoreo de Sensores</h3>
                <p class="text-gray-600 mb-4">Visualiza datos en tiempo real de temperatura, pH y turbidez</p>
                <a href="{{ route('admin.sensores') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-block transition">
                    <i class="fas fa-eye mr-2"></i>Ver Sensores
                </a>
            </div>
        </div>

        <!-- Reportes Manuales -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-4 rounded-full bg-blue-100 text-blue-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-file-lines text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Reportes Manuales</h3>
                <p class="text-gray-600 mb-4">Registra mediciones manuales para comparar con sensores</p>
                <a href="{{ route('operario.reportes.crear') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-block transition">
                    <i class="fas fa-plus mr-2"></i>Crear Reporte
                </a>
            </div>
        </div>

        <!-- Alertas Recibidas -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="text-center">
                <div class="p-4 rounded-full bg-yellow-100 text-yellow-600 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Alertas del Sistema</h3>
                <p class="text-gray-600 mb-4">Recibe y visualiza alertas automáticas del sistema</p>
                <p class="text-sm text-gray-500">Solo lectura - Las alertas se muestran automáticamente</p>
            </div>
        </div>
    </div>

    <!-- Últimas lecturas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Últimas Lecturas de Sensores</h2>
            <button onclick="actualizarDatos()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                <i class="fas fa-sync-alt mr-1"></i>Actualizar
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Fecha/Hora</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Temperatura (°C)</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">pH</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Turbidez (NTU)</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Estado</th>
                    </tr>
                </thead>
                <tbody id="tabla-sensores" class="divide-y divide-gray-200">
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Cargando datos...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function actualizarDatos() {
    fetch('/api/sensores')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('tabla-sensores');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-gray-500">No hay datos disponibles</td></tr>';
                return;
            }

            tbody.innerHTML = data.slice(0, 10).map(sensor => {
                const fecha = new Date(sensor.fecha).toLocaleString('es-ES');
                const tempStatus = getStatus(sensor.temperatura, 24, 28);
                const phStatus = getStatus(sensor.ph, 6.5, 8.5);
                const turbidezStatus = getTurbidezStatus(sensor.turbidez);
                
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-900">${fecha}</td>
                        <td class="py-3 px-4 text-sm ${tempStatus.class}">${sensor.temperatura}°C</td>
                        <td class="py-3 px-4 text-sm ${phStatus.class}">${sensor.ph}</td>
                        <td class="py-3 px-4 text-sm ${turbidezStatus.class}">${sensor.turbidez} NTU</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full ${getOverallStatus(tempStatus.ok, phStatus.ok, turbidezStatus.ok).class}">
                                ${getOverallStatus(tempStatus.ok, phStatus.ok, turbidezStatus.ok).text}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');

            // Actualizar contador de lecturas hoy
            const hoy = new Date().toDateString();
            const lecturasHoy = data.filter(d => new Date(d.fecha).toDateString() === hoy).length;
            document.getElementById('lecturas-hoy').textContent = lecturasHoy;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('tabla-sensores').innerHTML = 
                '<tr><td colspan="5" class="py-4 text-center text-red-500">Error al cargar datos</td></tr>';
        });
}

function getStatus(value, min, max) {
    const ok = value >= min && value <= max;
    return {
        ok: ok,
        class: ok ? 'text-green-600' : 'text-red-600 font-semibold'
    };
}

function getTurbidezStatus(value) {
    const ok = value <= 5;
    return {
        ok: ok,
        class: ok ? 'text-green-600' : 'text-red-600 font-semibold'
    };
}

function getOverallStatus(tempOk, phOk, turbidezOk) {
    if (tempOk && phOk && turbidezOk) {
        return { class: 'bg-green-100 text-green-800', text: 'Normal' };
    } else {
        return { class: 'bg-red-100 text-red-800', text: 'Alerta' };
    }
}

// Cargar datos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    actualizarDatos();
    
    // Actualizar cada 30 segundos
    setInterval(actualizarDatos, 30000);
});
</script>
@endsection