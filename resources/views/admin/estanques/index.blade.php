@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Estanques</h2>
        <button onclick="document.getElementById('modal-crear').classList.remove('hidden')" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Nuevo Estanque
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border px-4 py-2 text-left">ID</th>
                    <th class="border px-4 py-2 text-left">Tipo de Cultivo</th>
                    <th class="border px-4 py-2 text-left">Dimensiones</th>
                    <th class="border px-4 py-2 text-left">Área (m²)</th>
                    <th class="border px-4 py-2 text-left">Capacidad Máx</th>
                    <th class="border px-4 py-2 text-left">Peces Sembrados</th>
                    <th class="border px-4 py-2 text-left">Densidad</th>
                    <th class="border px-4 py-2 text-left">Descripción</th>
                    <th class="border px-4 py-2 text-left">Estado</th>
                    <th class="border px-4 py-2 text-left">Sensores</th>
                    <th class="border px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estanques as $estanque)
                <tr>
                    <td class="border px-4 py-2 font-semibold">{{ $estanque->id }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">
                            {{ ucfirst($estanque->tipo_cultivo) }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        @if($estanque->largo && $estanque->ancho)
                            <div class="text-sm">
                                {{ $estanque->largo }}m × {{ $estanque->ancho }}m
                                <button onclick="configurarDimensiones({{ $estanque->id }})" class="ml-2 text-blue-600 hover:text-blue-800" title="Editar dimensiones">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        @else
                            <button onclick="configurarDimensiones({{ $estanque->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus"></i> Configurar
                            </button>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($estanque->area_m2)
                            <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">
                                {{ number_format($estanque->area_m2, 2) }} m²
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">No calculada</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($estanque->capacidad)
                            <span class="px-2 py-1 rounded text-sm bg-purple-100 text-purple-800">
                                {{ number_format($estanque->capacidad) }} peces
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">No calculada</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($estanque->cantidad_sembrada)
                            <div class="text-sm">
                                <span class="px-2 py-1 rounded text-sm bg-green-100 text-green-800">
                                    {{ number_format($estanque->cantidad_sembrada) }} peces
                                </span>
                                <button onclick="configurarSiembra({{ $estanque->id }})" class="ml-2 text-green-600 hover:text-green-800" title="Editar siembra">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        @else
                            <button onclick="configurarSiembra({{ $estanque->id }})" class="text-green-600 hover:text-green-800 text-sm">
                                <i class="fas fa-fish"></i> Sembrar
                            </button>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if($estanque->densidad_siembra)
                            <span class="px-2 py-1 rounded text-sm bg-purple-100 text-purple-800">
                                {{ number_format($estanque->densidad_siembra, 2) }} peces/m²
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">No calculada</span>
                        @endif
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
                        @php
                            $sensoresCount = \DB::table('sensores_final')->where('estanque_id', $estanque->id)->count();
                        @endphp
                        @if($sensoresCount > 0)
                            <span class="px-2 py-1 rounded text-sm bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Monitoreado
                            </span>
                            <button onclick="quitarSensor({{ $estanque->id }})" class="ml-2 text-red-500 hover:text-red-700" title="Quitar monitoreo">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        @else
                            <span class="px-2 py-1 rounded text-sm bg-gray-100 text-gray-600">
                                Sin monitoreo
                            </span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        <select onchange="cambiarEstado({{ $estanque->id }}, this.value)" class="text-sm border rounded px-2 py-1 mr-2">
                            <option value="activo" {{ $estanque->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="disponible" {{ $estanque->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="mantenimiento" {{ $estanque->estado == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="inactivo" {{ $estanque->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <button onclick="mostrarModalSensor({{ $estanque->id }})" class="text-blue-600 hover:text-blue-800 mr-2" title="Configurar monitoreo">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button onclick="editarEstanque({{ $estanque->id }})" class="text-green-600 hover:text-green-800 mr-2" title="Editar estanque">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.estanques.destroy', $estanque->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-800" 
                                    onclick="confirmarEliminar({{ $estanque->id }})">
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

<!-- Modal Crear Estanque -->
<div id="modal-crear" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">Crear Nuevo Estanque</h3>
        <form method="POST" action="{{ route('admin.estanques.store') }}" onsubmit="return validarFormularioCrear(event)">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Especie de Pez</label>
                <select name="cultivo_pez_id" required class="form-select w-full border rounded px-3 py-2" aria-label="Seleccionar especie de pez">
                    <option selected>Seleccionar especie...</option>
                    @foreach($cultivoPeces as $pez)
                    <option value="{{ $pez->id }}">{{ $pez->nombre_especie }} ({{ $pez->densidad_recomendada }} peces/m²)</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Largo (metros)</label>
                <input type="number" name="largo" required min="0.1" step="0.01"
                       class="w-full border rounded px-3 py-2" 
                       placeholder="Ej: 22.9" id="crear-largo">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Ancho (metros)</label>
                <input type="number" name="ancho" required min="0.1" step="0.01"
                       class="w-full border rounded px-3 py-2" 
                       placeholder="Ej: 19.15" id="crear-ancho">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Capacidad máxima (peces)</label>
                <input type="text" id="crear-capacidad" readonly 
                       class="w-full border rounded px-3 py-2 bg-gray-100" 
                       placeholder="Se calcula automáticamente">
                <small class="text-gray-500">Basado en: área × 10 peces/m²</small>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea name="descripcion" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-crear').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Asignar Sensor -->
<div id="modal-sensor" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Configurar Monitoreo del Estanque</h3>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Sistema de Monitoreo</label>
            <select id="sensor-select" class="w-full border rounded px-3 py-2">
                <option value="">Seleccionar sensor...</option>
                <!-- Los sensores se cargarán dinámicamente -->
            </select>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="cerrarModalSensor()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
            <button type="button" onclick="asignarSensor()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Activar Monitoreo</button>
        </div>
    </div>
</div>

<!-- Modal Editar Estanque -->
<div id="modal-editar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Editar Estanque</h3>
        <form id="form-editar">
            <input type="hidden" id="edit-id">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Especie de Pez</label>
                <select id="edit-cultivo-pez-id" name="cultivo_pez_id" required class="form-select w-full border rounded px-3 py-2" aria-label="Seleccionar especie de pez">
                    <option value="">Seleccionar especie...</option>
                    @foreach($cultivoPeces as $pez)
                    <option value="{{ $pez->id }}">{{ $pez->nombre_especie }} ({{ $pez->densidad_recomendada }} peces/m²)</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Descripción</label>
                <textarea id="edit-descripcion" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="cerrarModalEditar()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="button" onclick="actualizarEstanque()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Configurar Dimensiones -->
<div id="modal-dimensiones" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Configurar Dimensiones del Estanque</h3>
        <form id="form-dimensiones">
            <input type="hidden" id="dim-estanque-id">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Largo (metros)</label>
                <input type="number" id="dim-largo" step="0.01" min="0.1" required 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: 22.9">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Ancho (metros)</label>
                <input type="number" id="dim-ancho" step="0.01" min="0.1" required 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: 19.15">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Área calculada (m²)</label>
                <input type="text" id="dim-area" readonly 
                       class="w-full border rounded px-3 py-2 bg-gray-100" placeholder="Se calcula automáticamente">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="cerrarModalDimensiones()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="button" onclick="guardarDimensiones()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Configurar Siembra -->
<div id="modal-siembra" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg p-4 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Configurar Siembra de Peces</h3>
        <form id="form-siembra">
            <input type="hidden" id="siembra-estanque-id">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Cantidad de peces sembrados</label>
                <input type="number" id="siembra-cantidad" min="1" required 
                       class="w-full border rounded px-3 py-2" placeholder="Ej: 270">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Densidad calculada (peces/m²)</label>
                <input type="text" id="siembra-densidad" readonly 
                       class="w-full border rounded px-3 py-2 bg-gray-100" placeholder="Se calcula automáticamente">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="cerrarModalSiembra()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                <button type="button" onclick="guardarSiembra()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
let estanqueSeleccionado = null;
let estanqueActual = null;

function configurarDimensiones(id) {
    estanqueActual = id;
    document.getElementById('dim-estanque-id').value = id;
    
    // Cargar datos existentes si los hay
    fetch(`/admin/estanques/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.largo) document.getElementById('dim-largo').value = data.largo;
            if (data.ancho) document.getElementById('dim-ancho').value = data.ancho;
            calcularArea();
            document.getElementById('modal-dimensiones').classList.remove('hidden');
        })
        .catch(() => {
            document.getElementById('modal-dimensiones').classList.remove('hidden');
        });
}

function cerrarModalDimensiones() {
    document.getElementById('modal-dimensiones').classList.add('hidden');
    document.getElementById('form-dimensiones').reset();
}

function calcularArea() {
    const largo = parseFloat(document.getElementById('dim-largo').value) || 0;
    const ancho = parseFloat(document.getElementById('dim-ancho').value) || 0;
    const area = largo * ancho;
    document.getElementById('dim-area').value = area > 0 ? area.toFixed(2) : '';
}

function guardarDimensiones() {
    const id = document.getElementById('dim-estanque-id').value;
    const largo = document.getElementById('dim-largo').value;
    const ancho = document.getElementById('dim-ancho').value;
    
    if (!largo || !ancho) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor completa todas las dimensiones'
        });
        return;
    }
    
    const area = parseFloat(largo) * parseFloat(ancho);
    
    fetch(`/admin/estanques/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            largo: largo,
            ancho: ancho,
            area_m2: area,
            _method: 'PUT'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Dimensiones guardadas correctamente',
                timer: 2000,
                showConfirmButton: false
            });
            cerrarModalDimensiones();
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function configurarSiembra(id) {
    estanqueActual = id;
    document.getElementById('siembra-estanque-id').value = id;
    
    // Obtener datos del estanque
    fetch(`/admin/estanques/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.area_m2) {
                // Cargar datos existentes si los hay
                if (data.cantidad_sembrada) {
                    document.getElementById('siembra-cantidad').value = data.cantidad_sembrada;
                    calcularDensidad();
                }
                document.getElementById('modal-siembra').classList.remove('hidden');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Configuración requerida',
                    text: 'Primero debes configurar las dimensiones del estanque'
                });
            }
        });
}

function cerrarModalSiembra() {
    document.getElementById('modal-siembra').classList.add('hidden');
    document.getElementById('form-siembra').reset();
}

function calcularDensidad() {
    const cantidad = parseFloat(document.getElementById('siembra-cantidad').value) || 0;
    const id = document.getElementById('siembra-estanque-id').value;
    
    if (cantidad > 0 && id) {
        fetch(`/admin/estanques/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.area_m2) {
                    const densidad = cantidad / data.area_m2;
                    document.getElementById('siembra-densidad').value = densidad.toFixed(2);
                }
            });
    }
}

function guardarSiembra() {
    const id = document.getElementById('siembra-estanque-id').value;
    const cantidad = document.getElementById('siembra-cantidad').value;
    
    if (!cantidad) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor ingresa la cantidad de peces'
        });
        return;
    }
    
    fetch(`/admin/estanques/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            const densidad = parseFloat(cantidad) / data.area_m2;
            
            return fetch(`/admin/estanques/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cantidad_sembrada: cantidad,
                    densidad_siembra: densidad,
                    _method: 'PUT'
                })
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Siembra registrada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
                cerrarModalSiembra();
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
}

// Event listeners para cálculos automáticos
document.addEventListener('DOMContentLoaded', function() {
    const largoInput = document.getElementById('dim-largo');
    const anchoInput = document.getElementById('dim-ancho');
    const cantidadInput = document.getElementById('siembra-cantidad');
    const crearLargoInput = document.getElementById('crear-largo');
    const crearAnchoInput = document.getElementById('crear-ancho');
    
    if (largoInput && anchoInput) {
        largoInput.addEventListener('input', calcularArea);
        anchoInput.addEventListener('input', calcularArea);
    }
    
    if (crearLargoInput && crearAnchoInput) {
        crearLargoInput.addEventListener('input', calcularCapacidadCrear);
        crearAnchoInput.addEventListener('input', calcularCapacidadCrear);
    }
    
    if (cantidadInput) {
        cantidadInput.addEventListener('input', calcularDensidad);
    }
});

function mostrarModalSensor(estanqueId) {
    console.log('Abriendo modal para estanque:', estanqueId);
    estanqueSeleccionado = estanqueId;
    cargarSensoresDisponibles();
    document.getElementById('modal-sensor').classList.remove('hidden');
}

function cerrarModalSensor() {
    document.getElementById('modal-sensor').classList.add('hidden');
    estanqueSeleccionado = null;
}

function cargarSensoresDisponibles() {
    const select = document.getElementById('sensor-select');
    select.innerHTML = '<option value="">Seleccionar sensor...</option>';
    select.innerHTML += '<option value="1">Sensores IoT - Sistema Principal</option>';
}

function asignarSensor() {
    const sensorId = document.getElementById('sensor-select').value;
    if (!sensorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Selección requerida',
            text: 'Por favor selecciona un sistema de monitoreo'
        });
        return;
    }
    
    fetch(`/admin/estanques/${estanqueSeleccionado}/asignar-sensor`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ sensor_id: sensorId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Monitoreo activado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
            cerrarModalSensor();
            // Actualizar estado visual sin recargar
            const row = document.querySelector(`button[onclick*="${estanqueSeleccionado}"]`).closest('tr');
            const sensorCell = row.cells[10]; // Columna de sensores ajustada
            sensorCell.innerHTML = '<span class="px-2 py-1 rounded text-sm bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Monitoreado</span>';
        }
    })
    .catch(error => console.error('Error:', error));
}
function cambiarEstado(id, estado) {
    fetch(`/admin/estanques/${id}/estado`, {
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
            const estadoCell = row.cells[9]; // Ajustado por las nuevas columnas
            const span = estadoCell.querySelector('span');
            
            if (span) {
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
        }
    })
    .catch(error => console.error('Error:', error));
}

function editarEstanque(id) {
    fetch(`/admin/estanques/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-tipo-cultivo').value = data.tipo_cultivo;

            document.getElementById('edit-descripcion').value = data.descripcion || '';
            document.getElementById('modal-editar').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function cerrarModalEditar() {
    document.getElementById('modal-editar').classList.add('hidden');
}

function actualizarEstanque() {
    const id = document.getElementById('edit-id').value;
    const identificador = document.getElementById('edit-identificador').value;
    const tipo_cultivo = document.getElementById('edit-tipo-cultivo').value;

    
    // Validaciones
    if (!identificador.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'El identificador es obligatorio'
        });
        return;
    }
    
    if (!tipo_cultivo.trim()) {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'El tipo de cultivo es obligatorio'
        });
        return;
    }
    


    const data = {
        identificador: identificador,
        tipo_cultivo: tipo_cultivo,

        descripcion: document.getElementById('edit-descripcion').value,
        _method: 'PUT'
    };

    fetch(`/admin/estanques/${id}`, {
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
                text: 'Estanque actualizado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                cerrarModalEditar();
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al actualizar el estanque'
        });
        console.error('Error:', error);
    });
}

function calcularCapacidadCrear() {
    const largo = parseFloat(document.getElementById('crear-largo').value) || 0;
    const ancho = parseFloat(document.getElementById('crear-ancho').value) || 0;
    const area = largo * ancho;
    const capacidad = area * 10; // 10 peces por m²
    document.getElementById('crear-capacidad').value = capacidad > 0 ? Math.round(capacidad) + ' peces' : '';
}

function validarFormularioCrear(event) {
    const cultivo_pez_id = document.querySelector('select[name="cultivo_pez_id"]').value;
    const largo = document.querySelector('input[name="largo"]').value;
    const ancho = document.querySelector('input[name="ancho"]').value;
    
    if (!cultivo_pez_id) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'Debe seleccionar una especie de pez'
        });
        return false;
    }
    
    if (!largo || largo < 0.1) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'El largo debe ser mayor a 0.1 metros'
        });
        return false;
    }
    
    if (!ancho || ancho < 0.1) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'El ancho debe ser mayor a 0.1 metros'
        });
        return false;
    }
    
    return true;
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
            // Crear formulario dinámico para eliminar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/estanques/${id}`;
            
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

function quitarSensor(id) {
    Swal.fire({
        title: '¿Quitar monitoreo?',
        text: 'Se desactivará el monitoreo de este estanque',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, quitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/estanques/${id}/quitar-sensor`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Monitoreo desactivado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Actualizar estado visual
                    const row = document.querySelector(`button[onclick*="quitarSensor(${id})"]`).closest('tr');
                    const sensorCell = row.cells[10];
                    sensorCell.innerHTML = '<span class="px-2 py-1 rounded text-sm bg-gray-100 text-gray-600">Sin monitoreo</span>';
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al quitar el monitoreo'
                });
                console.error('Error:', error);
            });
        }
    });
}
</script>
@endsection