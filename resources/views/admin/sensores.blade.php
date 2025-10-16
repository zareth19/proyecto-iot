@extends('layouts.app')

@section('title', 'Dashboard Sensores')

@section('content')
<main class="transition-all duration-300 p-6">

    <!-- TÍTULO -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Panel de Monitoreo de Sensores</h1>
    </div>

    <!-- CARDS DE DATOS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- CARD PH -->
        <div class="sensor-card border-green-500">
            <div class="sensor-inner">
                <i id="iconPh" class="fa-solid fa-vial-circle-check text-green-600 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">pH</p>
                <h2 id="phValue" class="text-3xl font-bold text-green-600">--</h2>
                <p id="lastUpdatePh" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD TEMPERATURA -->
        <div class="sensor-card border-red-400">
            <div class="sensor-inner">
                <i id="iconTemp" class="fa-solid fa-temperature-three-quarters text-red-500 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">Temperatura</p>
                <h2 id="tempValue" class="text-3xl font-bold text-red-500">--</h2>
                <p id="lastUpdateTemp" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD TURBIDEZ -->
        <div class="sensor-card border-blue-400">
            <div class="sensor-inner">
                <i id="iconTurbidez" class="fa-solid fa-water text-blue-500 text-3xl mb-2"></i>
                <p class="text-gray-500 text-sm">Turbidez</p>
                <h2 id="turbidezValue" class="text-3xl font-bold text-blue-500">--</h2>
                <p id="lastUpdateTurbidez" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>
    </div>

    <!-- GRAFICAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Niveles de pH</h3>
            <canvas id="chartPh"></canvas>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Temperatura</h3>
            <canvas id="chartTemp"></canvas>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4 text-gray-700">Turbidez</h3>
            <canvas id="chartTurbidez"></canvas>
        </div>
    </div>

    <!-- HISTORIAL -->
    <div class="bg-white mt-10 p-6 rounded-lg shadow">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <h3 class="text-lg font-bold text-gray-700">Historial de Lecturas</h3>
            
            <!-- FILTRO -->
            <div class="flex items-center gap-2">
                <label for="filtro" class="text-sm text-gray-600">Mostrar últimos:</label>
                <select id="filtro" class="border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-green-600">
                    <option value="10">10 registros</option>
                    <option value="20">20 registros</option>
                    <option value="30">30 registros</option>
                </select>
            </div>
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-green-600 text-white text-sm uppercase">
                    <tr>
                        <th class="py-2 px-3 text-left">Fecha / Hora</th>
                        <th class="py-2 px-3 text-center">pH</th>
                        <th class="py-2 px-3 text-center">Temperatura (°C)</th>
                        <th class="py-2 px-3 text-center">Turbidez (NTU)</th>
                    </tr>
                </thead>
                <tbody id="bodyHistorial" class="text-gray-700 text-sm"></tbody>
            </table>
        </div>
    </div>
</main>


<style>
    .sensor-card {
        position: relative;
        background: #fff;
        padding: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 0 15px rgba(2, 128, 5, 0.20);
        transition: all 0.3s ease;
        text-align: center;
        overflow: hidden;
    }

    .sensor-card:hover {
        box-shadow: 0 0 20px rgba(0, 128, 0, 0.15);
        transform: translateY(-2px);
    }

    .sensor-inner {
        position: relative;
        z-index: 10;
    }

    @keyframes pulseSoft {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.15); }
    }

    .pulse-update {
        animation: pulseSoft 0.5s ease-in-out;
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const filtro = document.getElementById('filtro');
    const bodyHistorial = document.getElementById('bodyHistorial');

    const chartPh = createChart('chartPh', '#16a34a');
    const chartTemp = createChart('chartTemp', '#ef4444');
    const chartTurbidez = createChart('chartTurbidez', '#3b82f6');

    let ultimoPH = null, ultimaTemp = null, ultimaTurbidez = null;

    function createChart(id, color) {
        return new Chart(document.getElementById(id), {
            type: 'line',
            data: { labels: [], datasets: [{ data: [], borderColor: color, backgroundColor: color + '22', fill: true, tension: 0.4, borderWidth: 2 }] },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#555' }, grid: { display: false } },
                    y: { ticks: { color: '#555' }, grid: { color: '#eee' } }
                }
            }
        });
    }

    async function cargarDatos() {
        try {
            const limite = filtro.value;
            const response = await fetch(`/admin/sensores/datos?limite=${limite}`);
            const datos = await response.json();

            if (datos.length > 0) {
                const ultimo = datos[0];

                actualizarCard('ph', ultimo.ph, ultimoPH);
                actualizarCard('temp', ultimo.temperatura, ultimaTemp, '°C');
                actualizarCard('turbidez', ultimo.turbidez, ultimaTurbidez);

                ultimoPH = ultimo.ph;
                ultimaTemp = ultimo.temperatura;
                ultimaTurbidez = ultimo.turbidez;

                // Actualizar gráficas
                const etiquetas = datos.map(d => d.fecha.split(' ')[1]).reverse();
                actualizarGrafico(chartPh, etiquetas, datos.map(d => d.ph).reverse());
                actualizarGrafico(chartTemp, etiquetas, datos.map(d => d.temperatura).reverse());
                actualizarGrafico(chartTurbidez, etiquetas, datos.map(d => d.turbidez).reverse());

                // Tabla
                bodyHistorial.innerHTML = datos.map(d => `
                    <tr class="border-t border-gray-200 hover:bg-gray-50">
                        <td class="py-2 px-3">${d.fecha}</td>
                        <td class="py-2 px-3 text-center">${d.ph}</td>
                        <td class="py-2 px-3 text-center">${d.temperatura}</td>
                        <td class="py-2 px-3 text-center">${d.turbidez}</td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            console.error('Error al cargar los datos:', error);
        }
    }

    function actualizarCard(tipo, nuevoValor, ultimoValor, unidad = '') {
        const valorElem = document.getElementById(`${tipo}Value`);
        const iconElem = document.getElementById(`icon${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
        const updateElem = document.getElementById(`lastUpdate${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);

        if (nuevoValor !== ultimoValor) {
            iconElem.classList.add('pulse-update');
            setTimeout(() => iconElem.classList.remove('pulse-update'), 500);
        }

        valorElem.textContent = nuevoValor.toFixed(2) + unidad;
        updateElem.textContent = 'Última actualización: ' + new Date().toLocaleTimeString();
    }

    function actualizarGrafico(chart, etiquetas, datos) {
        chart.data.labels = etiquetas;
        chart.data.datasets[0].data = datos;
        chart.update();
    }

    filtro.addEventListener('change', cargarDatos);
    cargarDatos();
    setInterval(cargarDatos, 5000);
</script>
@endsection
