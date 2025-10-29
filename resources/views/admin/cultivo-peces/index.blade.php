@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Especies de Peces</h2>
        <button onclick="document.getElementById('modal-crear').classList.remove('hidden')" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Nueva Especie
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border px-4 py-2 text-left">ID</th>
                    <th class="border px-4 py-2 text-left">Especie</th>
                    <th class="border px-4 py-2 text-left">Nombre Científico</th>
                    <th class="border px-4 py-2 text-left">Densidad Recomendada</th>
                    <th class="border px-4 py-2 text-left">Descripción</th>
                    <th class="border px-4 py-2 text-left">Estado</th>
                    <th class="border px-4 py-2 text-left">Estanques</th>
                    <th class="border px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cultivoPeces as $pez)
                <tr>
                    <td class="border px-4 py-2 font-semibold">{{ $pez->id }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">
                            {{ $pez->nombre_especie }}
                        </span>
                    </td>
                    <td class="border px-4 py-2 italic text-gray-600">{{ $pez->nombre_cientifico ?? 'No especificado' }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-purple-100 text-purple-800">
                            {{ $pez->densidad_recomendada }} peces/m²
                        </span>
                    </td>
                    <td class="border px-4 py-2">{{ Str::limit($pez->descripcion ?? 'Sin descripción', 50) }}</td>
                    <td class="border px-4 py-2">
                        <button onclick="toggleActivo({{ $pez->id }})" 
                                class="px-2 py-1 rounded text-sm {{ $pez->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $pez->activo ? 'Activo' : 'Inactivo' }}
                        </button>
                    </td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-gray-100 text-gray-600">
                            {{ $pez->estanques->count() }} estanques
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        <button onclick="editarPez({{ $pez->id }})" class="text-green-600 hover:text-green-800 mr-2" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($pez->estanques->count() == 0)
                        <button onclick="confirmarEliminar({{ $pez->id }})" class="text-red-600 hover:text-red-800" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                        @else
                        <span class="text-gray-400" title="No se puede eliminar, hay estanques usando esta especie">
                            <i class="fas fa-lock"></i>
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Especie -->
<div id="modal-crear" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Crear Nueva Especie</h3>
        <form method="POST" action="{{ route('admin.cultivo-peces.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nombre de la Especie</label>
                <input type="text" name="nombre_especie" required 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: Tilapia">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nombre Científico</label>
                <input type="text" name="nombre_cientifico" 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: Oreochromis niloticus">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Densidad Recomendada (peces/m²)</label>
                <input type="number" name="densidad_recomendada" min="0.1" max="50" step="0.1"
                       class="w-full border rounded px-3 py-2" placeholder="Ej: 10.0">
                <p class="text-xs text-gray-500 mt-1">Si no conoces la densidad, se usará 10 peces/m² por defecto</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea name="descripcion" class="w-full border rounded px-3 py-2" rows="3" 
                          placeholder="Descripción de la especie..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-crear').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Especie -->
<div id="modal-editar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Editar Especie</h3>
        <form id="form-editar">
            <input type="hidden" id="edit-id">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nombre de la Especie</label>
                <input type="text" id="edit-nombre-especie" required 
                       class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nombre Científico</label>
                <input type="text" id="edit-nombre-cientifico" 
                       class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Densidad Recomendada (peces/m²)</label>
                <input type="number" id="edit-densidad-recomendada" min="0.1" max="50" step="0.1"
                       class="w-full border rounded px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">Si no conoces la densidad, se usará 10 peces/m² por defecto</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea id="edit-descripcion" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="cerrarModalEditar()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="button" onclick="actualizarPez()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editarPez(id) {
    fetch(`/admin/cultivo-peces/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nombre-especie').value = data.nombre_especie;
            document.getElementById('edit-nombre-cientifico').value = data.nombre_cientifico || '';
            document.getElementById('edit-densidad-recomendada').value = data.densidad_recomendada;
            document.getElementById('edit-descripcion').value = data.descripcion || '';
            document.getElementById('modal-editar').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function cerrarModalEditar() {
    document.getElementById('modal-editar').classList.add('hidden');
}

function actualizarPez() {
    const id = document.getElementById('edit-id').value;
    const data = {
        nombre_especie: document.getElementById('edit-nombre-especie').value,
        nombre_cientifico: document.getElementById('edit-nombre-cientifico').value,
        densidad_recomendada: document.getElementById('edit-densidad-recomendada').value,
        descripcion: document.getElementById('edit-descripcion').value,
        _method: 'PUT'
    };

    fetch(`/admin/cultivo-peces/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Especie actualizada correctamente',
                timer: 2000,
                showConfirmButton: false
            });
            cerrarModalEditar();
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleActivo(id) {
    fetch(`/admin/cultivo-peces/${id}/toggle-activo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/cultivo-peces/${id}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection