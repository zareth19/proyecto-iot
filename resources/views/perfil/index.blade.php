@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">👤 Mi Perfil</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Foto de Perfil -->
                <div class="md:col-span-1">
                    <div class="text-center">
                        <div class="mb-4">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/fotos/' . Auth::user()->foto) }}" 
                                     alt="Foto de perfil" 
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-green-200">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-green-100 flex items-center justify-center border-4 border-green-200">
                                    <i class="fas fa-user text-4xl text-green-600"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cambiar Foto</label>
                            <input type="file" name="foto" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        </div>
                    </div>
                </div>

                <!-- Información Personal -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" name="nombre" value="{{ Auth::user()->nombre }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('nombre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                            <input type="text" name="apellido" value="{{ Auth::user()->apellido }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('apellido')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="text" name="telefono" value="{{ Auth::user()->telefono }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('telefono')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                            <input type="text" value="{{ ucfirst(Auth::user()->rol) }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                            <input type="email" value="{{ Auth::user()->correo }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                            <input type="text" value="{{ Auth::user()->tipo_documento }} {{ Auth::user()->numero_documento }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                        <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection