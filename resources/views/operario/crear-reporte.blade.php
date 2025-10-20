@extends('layouts.app')

@section('title', 'Crear Reporte Manual')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('operario.reportes.index') }}" class="text-green-600 hover:text-green-800 mr-4">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Crear Reporte Manual</h1>
        </div>

        <form method="POST" action="{{ route('operario.reportes.store') }}" class="space-y-6">
            @csrf
            
            <!-- Fecha y hora de toma -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Fecha y Hora de Toma</label>
                <input type="datetime-local" name="fecha_toma" required
                    value="{{ old('fecha_toma', now()->format('Y-m-d\TH:i')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
            </div>

            <!-- Mediciones -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Temperatura -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fas fa-thermometer-half text-red-500 mr-1"></i>
                        Temperatura (°C)
                    </label>
                    <input type="number" name="temperatura" step="0.01" min="0" max="50" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600"
                        placeholder="Ej: 26.5">
                    <p class="text-xs text-gray-500 mt-1">Rango normal: 24-28°C</p>
                </div>

                <!-- pH -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fas fa-flask text-blue-500 mr-1"></i>
                        pH
                    </label>
                    <input type="number" name="ph" step="0.01" min="0" max="14" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600"
                        placeholder="Ej: 7.2">
                    <p class="text-xs text-gray-500 mt-1">Rango normal: 6.5-8.5</p>
                </div>

                <!-- Turbidez -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        <i class="fas fa-water text-green-500 mr-1"></i>
                        Turbidez (NTU)
                    </label>
                    <input type="number" name="turbidez" step="0.01" min="0" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600"
                        placeholder="Ej: 2.5">
                    <p class="text-xs text-gray-500 mt-1">Máximo recomendado: 5 NTU</p>
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">
                    <i class="fas fa-sticky-note text-yellow-500 mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="4" maxlength="500"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600"
                    placeholder="Describe cualquier observación relevante sobre las condiciones del agua, comportamiento de los peces, etc."></textarea>
                <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('operario.reportes.index') }}" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-save mr-2"></i>Guardar Reporte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection