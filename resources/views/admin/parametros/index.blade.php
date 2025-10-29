@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Parámetros por Tipo de Cultivo</h2>
        <button onclick="document.getElementById('modal-crear').classList.remove('hidden')" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Nuevo Parámetro
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border px-4 py-2 text-left">Tipo de Cultivo</th>
                    <th class="border px-4 py-2 text-left">Temperatura (°C)</th>
                    <th class="border px-4 py-2 text-left">pH</th>
                    <th class="border px-4 py-2 text-left">Turbidez (NTU)</th>
                    <th class="border px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parametros as $parametro)
                <tr>
                    <td class="border px-4 py-2 font-semibold">{{ ucfirst($parametro->tipo_cultivo) }}</td>
                    <td class="border px-4 py-2">{{ $parametro->temp_min }} - {{ $parametro->temp_max }}</td>
                    <td class="border px-4 py-2">{{ $parametro->ph_min }} - {{ $parametro->ph_max }}</td>
                    <td class="border px-4 py-2">{{ $parametro->turbidez_min }} - {{ $parametro->turbidez_max }}</td>
                    <td class="border px-4 py-2">
                        <form method="POST" action="{{ route('admin.parametros.destroy', $parametro->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" 
                                    onclick="return confirm('¿Eliminar parámetros?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Parámetros -->
<div id="modal-crear" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Configurar Parámetros de Cultivo</h3>
        <form method="POST" action="{{ route('admin.parametros.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Tipo de Cultivo</label>
                <input type="text" name="tipo_cultivo" required 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: trucha, bagre, carpa">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Temp. Mín (°C)</label>
                    <input type="number" step="0.1" name="temp_min" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Temp. Máx (°C)</label>
                    <input type="number" step="0.1" name="temp_max" required class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-2">pH Mín</label>
                    <input type="number" step="0.1" name="ph_min" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">pH Máx</label>
                    <input type="number" step="0.1" name="ph_max" required class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Turbidez Mín</label>
                    <input type="number" step="0.1" name="turbidez_min" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Turbidez Máx</label>
                    <input type="number" step="0.1" name="turbidez_max" required class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-crear').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Crear</button>
            </div>
        </form>
    </div>
</div>
@endsection