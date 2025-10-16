@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<main class="transition-all duration-300 p-6"
      x-data="{
          showModalCrear: false,
          form: {
            nombre: '',
            apellido: '',
            tipo_documento: '',
            numero_documento: '',
            telefono: '',
            correo: '',
            contraseña: '',
            confirmcontraseña: '',
            rol: ''
            },
          errors: {}
      }"
      x-init="
        $watch('form.nombre', val => {
            errors.nombre = /^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/.test(val) || val === '' ? '' : 'Solo se permiten letras';
        });
        $watch('form.apellido', val => {
            errors.apellido = /^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/.test(val) || val === '' ? '' : 'Solo se permiten letras';
        });
        $watch('form.numero_documento', val => {
            errors.numero_documento = /^[0-9]+$/.test(val) || val === '' ? '' : 'Solo se permiten números';
        });
        $watch('form.telefono', val => {
            errors.telefono = /^[0-9]+$/.test(val) || val === '' ? '' : 'Solo se permiten números';
        });
        $watch('form.correo', val => {
            errors.correo = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/.test(val) || val === '' ? '' : 'Correo electrónico no válido';
        });
        $watch('form.contraseña', val => {
            if (form.confirmcontraseña === '') {
                errors.confirmcontraseña = '';
            } else {
                errors.confirmcontraseña = val === form.confirmcontraseña ? '' : 'Las contraseñas no coinciden';
            }
        });
        $watch('form.confirmcontraseña', val => {
            if (form.contraseña === '' && val === '') {
                errors.confirmcontraseña = '';
            } else {
                errors.confirmcontraseña = form.contraseña === val ? '' : 'Las contraseñas no coinciden';
            }
        });
      "
>
<div x-data="{
    showModalCrear: false,
    showModalEditar: false,
    form: {},
    formEdit: {
        id: '',
        nombre: '',
        apellido: '',
        tipo_documento: '',
        numero_documento: '',
        correo: '',
        telefono: '',
    },
    errors: {},
}"
>

<!-- ALERTA DE ÉXITO -->
@if (session('success'))
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-10 right-5 z-50 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center space-x-3"
>
    <i class="fa-solid fa-circle-check text-2xl"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
    <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif

<!-- ALERTA DE ELIMINACIÓN -->
@if (session('delete'))
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-10 right-5 z-50 bg-red-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center space-x-3"
>
    <i class="fa-solid fa-trash-can text-2xl"></i>
    <span class="text-sm font-medium">{{ session('delete') }}</span>
    <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-700">Gestión de Usuarios</h1>
        <button 
            @click="showModalCrear = true"
            class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm transition"
        >
            <i class="fa-solid fa-plus mr-2"></i> Nuevo usuario
        </button>
    </div>

    <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg">
            <thead class="bg-green-700 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Tipo Documento</th>
                    <th class="py-3 px-4 text-left">Numero Documento</th>
                    <th class="py-3 px-4 text-left">Nombre</th>
                    <th class="py-3 px-4 text-left">Apellido</th>
                    <th class="py-3 px-4 text-left">Rol</th>
                    <th class="py-3 px-4 text-left">Correo</th>
                    <th class="py-3 px-4 text-left">Telefono</th>
                    <th class="py-3 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($usuarios as $usuario)
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $usuario->id }}</td>
                        <td class="py-3 px-4">{{ $usuario->tipo_documento }}</td>
                        <td class="py-3 px-4">{{ $usuario->numero_documento }}</td>
                        <td class="py-3 px-4">{{ $usuario->nombre }}</td>
                        <td class="py-3 px-4">{{ $usuario->apellido }}</td>
                        <td class="py-3 px-4">{{ ucfirst($usuario->rol) }}</td>
                        <td class="py-3 px-4">{{ $usuario->correo }}</td>
                        <td class="py-3 px-4">{{ $usuario->telefono }}</td>
                        <td class="py-3 px-4">
                            <div class="flex space-x-2">
                              <button 
                                @click="
                                    showModalEditar = true;
                                    formEdit.id = '{{ $usuario->id }}';
                                    formEdit.nombre = '{{ $usuario->nombre }}';
                                    formEdit.apellido = '{{ $usuario->apellido }}';
                                    formEdit.tipo_documento = '{{ $usuario->tipo_documento }}';
                                    formEdit.numero_documento = '{{ $usuario->numero_documento }}';
                                    formEdit.correo = '{{ $usuario->correo }}';
                                 formEdit.telefono = '{{ $usuario->telefono }}';
                                "
                                class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm"
                                >
                             <i class="fa-solid fa-pen-to-square text-lg"></i>
                            </button>


                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm" @click="deleteUserId = {{ $usuario->id }}" >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($usuarios->isEmpty())
            <p class="text-gray-500 text-center py-4">No hay usuarios registrados.</p>
        @endif
    </div>

    <!--  MODAL CREAR USUARIO -->
    <div 
        x-show="showModalCrear"
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
        x-transition
    >
        <div 
            @click.away="showModalCrear = false"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 transform transition-all max-h-[100vh] overflow-y-auto"
        >
            <!-- titulo del modal -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagenes/LOGO_SENA.png') }}" alt="Logo SENA" class="h-10">
                    <h2 class="text-xl font-bold text-green-800">Registrar Usuario</h2>
                </div>
                <button @click="showModalCrear = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Formulario -->
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NOMBRE -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
                        <input type="text" name="nombre" x-model="form.nombre" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                        <template x-if="errors.nombre">
                            <p class="text-red-600 text-sm mt-1" x-text="errors.nombre"></p>
                        </template>
                    </div>

                    <!-- APELLIDO -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Apellido</label>
                        <input type="text" name="apellido" x-model="form.apellido" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                        <template x-if="errors.apellido">
                            <p class="text-red-600 text-sm mt-1" x-text="errors.apellido"></p>
                        </template>
                    </div>
                </div>

                <!-- DOCUMENTO Y TIPO -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Tipo de Documento</label>
                        <select name="tipo_documento" x-model="form.tipo_documento" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                            <option value="">Seleccionar</option>
                            <option value="CC">CC</option>
                            <option value="TI">TI</option>
                            <option value="CE">CE</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Número de Documento</label>
                        <input type="text" name="numero_documento" x-model="form.numero_documento" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                        <template x-if="errors.numero_documento">
                            <p class="text-red-600 text-sm mt-1" x-text="errors.numero_documento"></p>
                        </template>
                    </div>
                </div>

                <!-- TELÉFONO Y correo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
                        <input type="text" name="telefono" x-model="form.telefono" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                        <template x-if="errors.telefono">
                            <p class="text-red-600 text-sm mt-1" x-text="errors.telefono"></p>
                        </template>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Correo Electrónico</label>
                        <input type="email" name="correo" x-model="form.correo" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                    </div>
                </div>

                <!-- ROL Y CONTRASEÑA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Rol</label>
                        <select name="rol" x-model="form.rol" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                            <option value="">Seleccionar</option>
                            <option value="admin">Administrador</option>
                            <option value="operario">Operario</option>
                            <option value="estandar">Estandar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Contraseña</label>
                        <input type="password" name="contraseña" x-model="form.contraseña" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                    </div>
                </div>

                <!-- CONFIRMAR CONTRASEÑA -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Confirmar Contraseña</label>
                    <input type="password" name="contraseña_confirmation" x-model="form.confirmcontraseña" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                    <template x-if="errors.confirmcontraseña">
                        <p class="text-red-600 text-sm mt-1" x-text="errors.confirmcontraseña"></p>
                    </template>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" @click="showModalCrear = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 shadow-md">
                        <i class="fa-solid fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

   <!--  MODAL EDITAR USUARIO -->
<div 
    x-show="showModalEditar"
    x-cloak
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
    x-transition
>
    <div 
        @click.away="showModalEditar = false"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 transform transition-all max-h-[100vh] overflow-y-auto"
    >
        <!-- titulo del modal -->
        <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-4">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('imagenes/LOGO_SENA.png') }}" alt="Logo SENA" class="h-10">
                <h2 class="text-xl font-bold text-green-700">Editar Usuario</h2>
            </div>
            <button @click="showModalEditar = false" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Formulario -->
        <form :action="'/dashboard/admin/usuarios/' + formEdit.id" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NOMBRE -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
                    <input type="text" name="nombre" x-model="formEdit.nombre" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                </div>

                <!-- APELLIDO -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Apellido</label>
                    <input type="text" name="apellido" x-model="formEdit.apellido" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                </div>
            </div>

            <!-- Documento y tipo -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tipo de Documento</label>
                    <select name="tipo_documento" x-model="formEdit.tipo_documento" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                        <option value="">Seleccionar</option>
                        <option value="CC">CC</option>
                        <option value="TI">TI</option>
                        <option value="CE">CE</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Número de Documento</label>
                    <input type="text" name="numero_documento" x-model="formEdit.numero_documento" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed">
                </div>
            </div>

            <!-- Telefono y correo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
                    <input type="text" name="telefono" x-model="formEdit.telefono" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" x-model="formEdit.correo" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
                </div>
            </div>

            <!-- ocultar rol para no dejar editarlo -->
            <input type="hidden" name="rol" x-model="formEdit.rol">

            <!-- Botones -->
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" @click="showModalEditar = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-md">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>


      <!--  MODAL ELIMINAR USUARIO -->
    <div 
        x-show="deleteUserId" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-transition
    >
        <div class="bg-white rounded-lg p-6 shadow-lg w-96">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Eliminar usuario</h3>
            <p class="text-gray-600 mb-4">¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>

            <div class="flex justify-end space-x-2">
                <button @click="deleteUserId = null" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <form method="POST" :action="'/admin/users/delete/' + deleteUserId">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</main>
@endsection
