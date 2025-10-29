@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Estado de Estanques</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border px-4 py-2 text-left">ID</th>
                    <th class="border px-4 py-2 text-left">Identificador</th>
                    <th class="border px-4 py-2 text-left">Tipo de Cultivo</th>
                    <th class="border px-4 py-2 text-left">Capacidad</th>
                    <th class="border px-4 py-2 text-left">Descripción</th>
                    <th class="border px-4 py-2 text-left">Estado</th>
                    <th class="border px-4 py-2 text-left">Cambiar Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estanques as $estanque)
                <tr>
                    <td class="border px-4 py-2">{{ $estanque->id }}</td>
                    <td class="border px-4 py-2 font-semibold">{{ $estanque->identificador }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">
                            {{ ucfirst($estanque->tipo_cultivo) }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-purple-100 text-purple-800">
                            {{ number_format($estanque->capacidad ?? 0) }} especies
                        </span>
                    </td>
                    <td class="border px-4 py-2">{{ $estanque->descripcion ?? 'Sin descripción' }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm 
                            @if($estanque->estado == 'activo') bg-green-100 text-green-800
                            @elseif($estanque->estado == 'disponible') bg-blue-100 text-blue-800
                            @elseif($estanque->estado == 'mantenimiento') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($estanque->estado) }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        <select onchange="cambiarEstado({{ $estanque->id }}, this.value)" class="text-sm border rounded px-2 py-1 w-full">
                            <option value="activo" {{ $estanque->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="disponible" {{ $estanque->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="mantenimiento" {{ $estanque->estado == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="inactivo" {{ $estanque->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function cambiarEstado(id, estado) {
    fetch(`/operario/estanques/${id}/estado`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado: estado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`select[onchange*="${id}"]`).closest('tr');
            const estadoCell = row.cells[4];
            const span = estadoCell.querySelector('span');
            
            span.className = 'px-2 py-1 rounded text-sm ';
            if (estado === 'activo') {
                span.className += 'bg-green-100 text-green-800';
            } else if (estado === 'disponible') {
                span.className += 'bg-blue-100 text-blue-800';
            } else if (estado === 'mantenimiento') {
                span.className += 'bg-yellow-100 text-yellow-800';
            } else {
                span.className += 'bg-red-100 text-red-800';
            }
            span.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection