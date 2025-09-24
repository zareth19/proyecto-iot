<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sensores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: false  }">

    <!-- SIDEBAR -->
    <aside 
        :class="sidebarOpen ? 'w-60' : 'w-20'"
        class="fixed top-0 left-0 h-full bg-green-800 text-white flex flex-col p-4 transition-all duration-300">

        <!-- Botón hamburguesa -->
        <div class="flex justify-start mb-3">
            <button @click="sidebarOpen = !sidebarOpen" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Logo -->
        <div :class="sidebarOpen ? 'mt-0' : 'mt-6' " 
             class="flex items-start justify-start mb-6 transition-all duration-300">
            <img src="{{ asset('imagenes/logo sena.png') }}" alt="Logo" 
                 class="h-12 transition-all duration-300">
        </div>

        <!-- Menú -->
        <nav class="space-y-4">
            <a href="#" class="flex items-center hover:bg-green-700 p-2 rounded">
                <i class="fa-solid fa-gauge mr-2"></i>
                <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Inicio</span>
            </a>
            <a href="#" class="flex items-center hover:bg-green-700 p-2 rounded">
                <i class="fa-solid fa-flask mr-2"></i>
                <span x-show="sidebarOpen" x-transition>Sensores</span>
            </a>
            
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="transition-all duration-300 p-6">

        <!-- CARDS DE DATOS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- pH -->
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <i class="fa-solid fa-vial-circle-check text-green-600 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">pH</p>
                <h2 class="text-3xl font-bold text-green-600">7.2</h2>
            </div>

            <!-- Temperatura -->
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <i class="fa-solid fa-temperature-three-quarters text-red-500 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">Temperatura</p>
                <h2 class="text-3xl font-bold text-red-500">25°C</h2>
            </div>

            <!-- Turbidez -->
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <i class="fa-solid fa-water text-blue-500 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">Turbidez</p>
                <h2 class="text-3xl font-bold text-blue-500">15 NTU</h2>
            </div>

            <!-- Luminosidad -->
            <div class="bg-white p-4 rounded-lg shadow text-center">
                <i class="fa-solid fa-sun text-yellow-500 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">Luminosidad</p>
                <h2 class="text-3xl font-bold text-yellow-500">1200 lx</h2>
            </div>
        </div>

        <!-- GRAFICAS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Niveles de pH</h3>
                <canvas id="chartPh"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Temperatura</h3>
                <canvas id="chartTemp"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Turbidez</h3>
                <canvas id="chartTurbidez"></canvas>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4">Luminosidad</h3>
                <canvas id="chartLuz"></canvas>
            </div>
        </div>
    </main>

    <script>
        function createLineChart(ctx, label, color, data) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: color + '33',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        }

        createLineChart(document.getElementById('chartPh'), 'pH', '#16a34a', [7.0, 7.2, 7.1, 7.3, 7.1, 7.4, 7.2]);
        createLineChart(document.getElementById('chartTemp'), 'Temperatura', '#ef4444', [24, 25, 26, 25, 24, 26, 25]);
        createLineChart(document.getElementById('chartTurbidez'), 'Turbidez', '#3b82f6', [10, 12, 15, 13, 16, 14, 15]);
        createLineChart(document.getElementById('chartLuz'), 'Luminosidad', '#eab308', [1100, 1200, 1150, 1500, 1400, 1300, 1250]);
    </script>

</body>
</html>
