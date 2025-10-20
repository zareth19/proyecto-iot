@extends('layouts.app')

@section('title', 'Reportes Manuales')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reportes Manuales</h1>
                <p class="text-gray-600 mt-1">Gestiona las mediciones manuales registradas</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('operario.reportes.crear') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nuevo Reporte
                </a>
            </div>
        </div>
        
        <!-- Filtros y descarga -->
        <form method="GET" action="{{ route('operario.reportes.exportar') }}" class="flex items-end space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" 
                    value="{{ request('fecha_inicio', now()->subMonth()->format('Y-m-d')) }}"
                    class="border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" 
                    value="{{ request('fecha_fin', now()->format('Y-m-d')) }}"
                    class="border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <button type="submit" 
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-file-pdf mr-2"></i>Descargar PDF
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Lista de reportes -->
    <div class="bg-white rounded-lg shadow p-6">
        @if($reportes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Fecha/Hora</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Operario</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Temperatura (°C)</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">pH</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Turbidez (NTU)</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Observaciones</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reportes as $reporte)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ $reporte->fecha_toma->format('d/m/Y H:i') }}
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
                                    <a href="{{ route('operario.reportes.descargar', $reporte->id) }}" 
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

            <!-- Paginación -->
            <div class="mt-4">
                {{ $reportes->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-file-lines text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-600 mb-2">No hay reportes manuales</h3>
                <p class="text-gray-500 mb-4">Comienza creando tu primer reporte manual</p>
                <a href="{{ route('operario.reportes.crear') }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-block transition">
                    <i class="fas fa-plus mr-2"></i>Crear Primer Reporte
                </a>
            </div>
        @endif
    </div>
</div>
@endsection