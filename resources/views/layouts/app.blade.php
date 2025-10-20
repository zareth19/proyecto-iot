<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen"
      x-data="{ sidebarOpen: false, showLogoutConfirm: false, deleteUserId: null }">


    @php
        $rol = strtolower(trim(Auth::user()->rol ?? ''));
    @endphp

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-20'"
           class="fixed top-0 left-0 h-full bg-green-800 text-white flex flex-col p-4 transition-all duration-300 z-40">

        <!-- Botón hamburguesa -->
        <div class="flex justify-start mb-3">
            <button @click="sidebarOpen = !sidebarOpen" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Logo -->
        <div class="flex items-center justify-center mb-6">
            <img src="{{ asset('imagenes/logo sena.png') }}" alt="Logo" class="h-12">
        </div>

        <!-- Menú -->
        <nav class="space-y-4">

            <!-- ADMIN -->
            @if($rol === 'admin')
            <a href="{{ route('dashboard.admin') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                <i class="fa-solid fa-gauge mr-2"></i>
                <span x-show="sidebarOpen" x-transition>Inicio</span>
            </a>
                <a href="{{ route('admin.sensores') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-flask mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Sensores</span>
                </a>
                <a href="{{ route('admin.usuarios') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-users mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Usuarios</span>
                </a>
                <a href="{{ route('admin.reportes') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-chart-line mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Reportes</span>
                </a>
                <div class="relative" x-data="{ alertasOpen: false, alertas: [], noLeidas: 0 }" x-init="cargarAlertas()">
                    <button @click="alertasOpen = !alertasOpen" class="flex items-center hover:bg-green-700 p-2 rounded w-full text-left relative">
                        <i class="fa-solid fa-bell mr-2"></i>
                        <span x-show="sidebarOpen" x-transition>Alertas</span>
                        <span x-show="noLeidas > 0" x-text="noLeidas" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                    </button>
                    
                    <div x-show="alertasOpen" x-cloak @click.away="alertasOpen = false" x-transition
                         class="absolute left-full top-0 ml-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-800">Alertas Recientes</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-for="alerta in alertas" :key="alerta.id">
                                <div class="p-3 border-b hover:bg-gray-50 cursor-pointer" @click="marcarLeida(alerta.id)">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800" x-text="alerta.mensaje"></p>
                                            <p class="text-xs text-gray-500" x-text="new Date(alerta.fecha_alerta).toLocaleString()"></p>
                                            <span class="text-xs px-2 py-1 rounded" :class="alerta.tipo === 'tilapia' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'" x-text="alerta.tipo"></span>
                                        </div>
                                        <div x-show="!alerta.leida" class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="alertas.length === 0" class="p-4 text-center text-gray-500">
                                No hay alertas recientes
                            </div>
                        </div>
                    </div>
                </div>

            @endif

            <!-- OPERARIO -->
            @if($rol === 'operario')
            <a href="{{ route('dashboard.operario') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                <i class="fa-solid fa-gauge mr-2"></i>
                <span x-show="sidebarOpen" x-transition>Inicio</span>
            </a>
                <a href="{{ route('admin.sensores') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-flask mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Sensores</span>
                </a>
                <a href="{{ route('operario.reportes.index') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-file-lines mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Reportes Manuales</span>
                </a>
                <div class="relative" x-data="{ alertasOpen: false, alertas: [], noLeidas: 0 }" x-init="cargarAlertas()">
                    <button @click="alertasOpen = !alertasOpen" class="flex items-center hover:bg-green-700 p-2 rounded w-full text-left relative">
                        <i class="fa-solid fa-bell mr-2"></i>
                        <span x-show="sidebarOpen" x-transition>Alertas</span>
                        <span x-show="noLeidas > 0" x-text="noLeidas" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                    </button>
                    
                    <div x-show="alertasOpen" x-cloak @click.away="alertasOpen = false" x-transition
                         class="absolute left-full top-0 ml-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold text-gray-800">Alertas Recientes</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-for="alerta in alertas" :key="alerta.id">
                                <div class="p-3 border-b hover:bg-gray-50 cursor-pointer" @click="marcarLeida(alerta.id)">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800" x-text="alerta.mensaje"></p>
                                            <p class="text-xs text-gray-500" x-text="new Date(alerta.fecha_alerta).toLocaleString()"></p>
                                            <span class="text-xs px-2 py-1 rounded" :class="alerta.tipo === 'tilapia' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'" x-text="alerta.tipo"></span>
                                        </div>
                                        <div x-show="!alerta.leida" class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="alertas.length === 0" class="p-4 text-center text-gray-500">
                                No hay alertas recientes
                            </div>
                        </div>
                    </div>
                </div>

                </a>
            @endif

            <!-- ESTÁNDAR -->
            @if($rol === 'estandar')
            <a href="{{ route('dashboard.estandar') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                <i class="fa-solid fa-gauge mr-2"></i>
                <span x-show="sidebarOpen" x-transition>Inicio</span>
            </a>
                <a href="{{ route('admin.sensores') }}" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-flask mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Sensores</span>
                </a>
            @endif
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="transition-all duration-300">

        <!-- BARRA SUPERIOR -->
        <header class="bg-white shadow flex items-center justify-between px-6 py-3 sticky top-0 z-30">
            <span class="text-xl font-semibold text-gray-800">Panel Usuario {{ $rol }}</span>

            <div class="flex items-center space-x-3 relative" x-data="{ open: false }">
                <div class="flex items-center space-x-2 cursor-pointer" @click="open = !open">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/fotos/' . Auth::user()->foto) }}" 
                             alt="Foto de perfil" 
                             class="w-8 h-8 rounded-full object-cover border-2 border-green-200">
                    @else
                        <div class="bg-green-700 text-white w-8 h-8 flex items-center justify-center rounded-full">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                    <span class="text-gray-700 font-medium">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
                    <i class="fa-solid fa-caret-down text-gray-500"></i>
                </div>

                <!-- Menú desplegable -->
                <div x-show="open" 
                    x-cloak 
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 top-full mt-2 w-40 bg-white shadow-md rounded-md overflow-hidden z-50">
     
                    <a href="{{ route('perfil.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Perfil
                    </a>
    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" 
                                @click="showLogoutConfirm = true; open = false"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Cerrar sesión
                        </button>
                    </form>
                </div>

            </div>
        </header>

        <!-- CONTENIDO VARIABLE -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>

    <!-- MODAL DE CERRAR SESIÓN -->
    <div x-show="showLogoutConfirm" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         x-transition>
        <div class="bg-white rounded-lg p-6 shadow-lg w-96">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Cerrar sesión?</h3>
            <p class="text-gray-600 mb-4">¿Estás seguro de que deseas salir?</p>
            <div class="flex justify-end space-x-2">
                <button @click="showLogoutConfirm = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">No</button>
                <button @click="document.getElementById('logout-form').submit()" 
                        class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
                    Sí, salir
                </button>
            </div>
        </div>
    </div>

<script>
function cargarAlertas() {
    fetch('/api/alertas')
        .then(response => response.json())
        .then(data => {
            this.alertas = data;
        })
        .catch(error => console.error('Error:', error));
    
    fetch('/api/alertas/no-leidas')
        .then(response => response.json())
        .then(data => {
            this.noLeidas = data.count;
        })
        .catch(error => console.error('Error:', error));
}

function marcarLeida(id) {
    fetch(`/api/alertas/${id}/leida`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const alerta = this.alertas.find(a => a.id === id);
            if (alerta && !alerta.leida) {
                alerta.leida = true;
                this.noLeidas = Math.max(0, this.noLeidas - 1);
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<!-- Formulario oculto para logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

</body>
</html>
