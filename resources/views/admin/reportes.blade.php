@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="reportes()">
    <!-- Reportes Manuales -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">📝 Reportes Manuales</h2>
            <form method="GET" class="flex items-center space-x-2">
                <select name="estanque_id" class="border rounded px-3 py-2 text-sm">
                    <option value="">Todos los estanques</option>
                    @foreach($estanques as $estanque)
                        <option value="{{ $estanque->id }}" {{ request('estanque_id') == $estanque->id ? 'selected' : '' }}>
                            {{ $estanque->identificador }} - {{ ucfirst($estanque->tipo_cultivo) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                    <i class="fas fa-filter mr-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.reportes.manuales.exportar', request()->all()) }}" class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
                </a>
            </form>
        </div>

        @if($reportesManuales->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Fecha/Hora</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Estanque</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Operario</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Temperatura (°C)</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">pH</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Turbidez (NTU)</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Observaciones</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reportesManuales as $reporte)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $reporte->fecha_toma->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    @if($reporte->estanque)
                                        <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                            {{ $reporte->estanque->identificador }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Sin estanque</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $reporte->usuario->nombre }} {{ $reporte->usuario->apellido }}
                                </td>
                                <td class="py-3 px-4 text-sm {{ $reporte->temperatura >= 24 && $reporte->temperatura <= 28 ? 'text-green-600' : 'text-red-600 font-semibold' }}">
                                    {{ $reporte->temperatura }}°C
                                </td>
                                <td class="py-3 px-4 text-sm {{ $reporte->ph >= 6.5 && $reporte->ph <= 8.5 ? 'text-green-600' : 'text-red-600 font-semibold' }}">
                                    {{ $reporte->ph }}
                                </td>
                                <td class="py-3 px-4 text-sm {{ $reporte->turbidez <= 5 ? 'text-green-600' : 'text-red-600 font-semibold' }}">
                                    {{ $reporte->turbidez }} NTU
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">
                                    {{ $reporte->observaciones ? Str::limit($reporte->observaciones, 50) : 'Sin observaciones' }}
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.reportes.manuales.descargar', $reporte->id) }}" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition" 
                                        title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $reportesManuales->links() }}
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                No hay reportes manuales registrados
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Reportes de Sensores</h2>

        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estanque</label>
                <select x-model="estanqueId" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Todos los estanques</option>
                    @foreach($estanques as $estanque)
                        <option value="{{ $estanque->id }}">{{ $estanque->identificador }} - {{ ucfirst($estanque->tipo_cultivo) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" x-model="fechaInicio" :max="new Date().toISOString().split('T')[0]" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" x-model="fechaFin" :min="fechaInicio" :max="new Date().toISOString().split('T')[0]" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end">
                <button @click="generarReporte()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Generar
                </button>
            </div>
            <div class="flex items-end">
                <button @click="exportarPDF()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div x-show="estadisticas" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-blue-800">Total Registros</h3>
                <p class="text-2xl font-bold text-blue-600" x-text="estadisticas?.total_registros || 0"></p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="font-semibold text-red-800">Temperatura</h3>
                <p class="text-sm text-red-600">
                    Prom: <span x-text="estadisticas?.temp_promedio || 0"></span>°C<br>
                    Min: <span x-text="estadisticas?.temp_min || 0"></span>°C | Max: <span x-text="estadisticas?.temp_max || 0"></span>°C
                </p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="font-semibold text-yellow-800">pH</h3>
                <p class="text-sm text-yellow-600">
                    Prom: <span x-text="estadisticas?.ph_promedio || 0"></span><br>
                    Min: <span x-text="estadisticas?.ph_min || 0"></span> | Max: <span x-text="estadisticas?.ph_max || 0"></span>
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-800">Turbidez</h3>
                <p class="text-sm text-green-600">
                    Promedio: <span x-text="estadisticas?.turbidez_promedio || 0"></span> NTU
                </p>
            </div>
        </div>

        <!-- Tabla de Datos -->
        <div x-show="datos.length > 0" class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border text-left">Fecha</th>
                        <th class="px-4 py-2 border text-left">Temperatura (°C)</th>
                        <th class="px-4 py-2 border text-left">pH</th>
                        <th class="px-4 py-2 border text-left">Turbidez (NTU)</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="dato in datos.slice(0, 50)" :key="dato.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border" x-text="new Date(dato.fecha).toLocaleString()"></td>
                            <td class="px-4 py-2 border" x-text="dato.temperatura"></td>
                            <td class="px-4 py-2 border" x-text="dato.ph"></td>
                            <td class="px-4 py-2 border" x-text="dato.turbidez"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p x-show="datos.length > 50" class="text-sm text-gray-500 mt-2">
                Mostrando primeros 50 registros de <span x-text="datos.length"></span> total
            </p>
        </div>

        <div x-show="datos.length === 0 && estadisticas" class="text-center py-8 text-gray-500">
            No hay datos en el período seleccionado
        </div>
    </div>
</div>

<script>
function reportes() {
    return {
        fechaInicio: new Date(Date.now() - 7*24*60*60*1000).toISOString().split('T')[0],
        fechaFin: new Date().toISOString().split('T')[0],
        estanqueId: '',
        datos: [],
        estadisticas: null,

        async generarReporte() {
            if (this.fechaInicio > this.fechaFin) {
                alert('La fecha de inicio no puede ser mayor que la fecha fin');
                return;
            }
            if (this.fechaFin > new Date().toISOString().split('T')[0]) {
                alert('No se pueden generar reportes de fechas futuras');
                return;
            }
            try {
                const response = await fetch('/admin/reportes/generar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        fecha_inicio: this.fechaInicio,
                        fecha_fin: this.fechaFin,
                        estanque_id: this.estanqueId
                    })
                });
                
                const result = await response.json();
                this.datos = result.datos;
                this.estadisticas = result.estadisticas;
            } catch (error) {
                console.error('Error:', error);
            }
        },

        exportarPDF() {
            if (this.fechaInicio > this.fechaFin) {
                alert('La fecha de inicio no puede ser mayor que la fecha fin');
                return;
            }
            if (this.fechaFin > new Date().toISOString().split('T')[0]) {
                alert('No se pueden generar reportes de fechas futuras');
                return;
            }
            const url = `/admin/reportes/exportar?fecha_inicio=${this.fechaInicio}&fecha_fin=${this.fechaFin}&estanque_id=${this.estanqueId}`;
            window.open(url, '_blank');
        },

        init() {
            this.generarReporte();
        }
    }
}
</script>
@endsection