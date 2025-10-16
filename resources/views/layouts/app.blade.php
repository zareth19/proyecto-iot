<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-bell mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>
                        Alertas 
                    </span>
                </a>

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
                <a href="#" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-file-lines mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>Reportes Manuales</span>
                </a>
                <a href="" class="flex items-center hover:bg-green-700 p-2 rounded">
                    <i class="fa-solid fa-bell mr-2"></i>
                    <span x-show="sidebarOpen" x-transition>
                        Alertas 
                    </span>
                </a>

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
                    <div class="bg-green-700 text-white w-8 h-8 flex items-center justify-center rounded-full">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="text-gray-700 font-medium">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
                    <i class="fa-solid fa-caret-down text-gray-500"></i>
                </div>

                <!-- Menú desplegable -->
                <div x-show="open" 
                    x-cloak 
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 top-full mt-2 w-40 bg-white shadow-md rounded-md overflow-hidden z-50">
     
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
                        Sí, salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
