@extends('layouts.app')

@section('title', 'Dashboard Sensores')

@section('content')
<main class="transition-all duration-300 p-6">

    <!-- TÍTULO Y CONTROLES -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Panel de Monitoreo de Sensores</h1>
        <div class="flex justify-center">
            <div id="estadoConexion" class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                <i class="fas fa-wifi mr-2"></i>Conectado
            </div>
        </div>
    </div>

    <!-- CARDS DE DATOS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- CARD PH -->
        <div class="sensor-card border-green-500">
            <div class="sensor-inner">
                <i id="iconPh" class="fa-solid fa-vial-circle-check text-green-600 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">pH</p>
                <h2 id="phValue" class="text-2xl font-bold text-green-600">Cargando...</h2>
                <p id="lastUpdatePh" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD TEMPERATURA -->
        <div class="sensor-card border-red-400">
            <div class="sensor-inner">
                <i id="iconTemp" class="fa-solid fa-temperature-three-quarters text-red-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Temperatura</p>
                <h2 id="tempValue" class="text-2xl font-bold text-red-500">Cargando...</h2>
                <p id="lastUpdateTemp" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD TURBIDEZ -->
        <div class="sensor-card border-blue-400">
            <div class="sensor-inner">
                <i id="iconTurbidez" class="fa-solid fa-water text-blue-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Turbidez</p>
                <h2 id="turbidezValue" class="text-2xl font-bold text-blue-500">Cargando...</h2>
                <p id="lastUpdateTurbidez" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD OXÍGENO -->
        <div class="sensor-card border-yellow-400">
            <div class="sensor-inner">
                <i id="iconOxigeno" class="fa-solid fa-wind text-yellow-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Oxígeno Disuelto</p>
                <h2 id="oxigenoValue" class="text-2xl font-bold text-yellow-500">--</h2>
                <p id="lastUpdateOxigeno" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>
    </div>

    <!-- SEGUNDA FILA DE CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- CARD AMONIACO -->
        <div class="sensor-card border-purple-400">
            <div class="sensor-inner">
                <i id="iconAmoniaco" class="fa-solid fa-flask text-purple-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Amoniaco</p>
                <h2 id="amoniacoValue" class="text-2xl font-bold text-purple-500">--</h2>
                <p id="lastUpdateAmoniaco" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD NITRITOS -->
        <div class="sensor-card border-orange-400">
            <div class="sensor-inner">
                <i id="iconNitritos" class="fa-solid fa-atom text-orange-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Nitritos</p>
                <h2 id="nitritosValue" class="text-2xl font-bold text-orange-500">--</h2>
                <p id="lastUpdateNitritos" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
            </div>
        </div>

        <!-- CARD CONDUCTIVIDAD -->
        <div class="sensor-card border-indigo-400">
            <div class="sensor-inner">
                <i id="iconConductividad" class="fa-solid fa-bolt text-indigo-500 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Conductividad</p>
                <h2 id="conductividadValue" class="text-2xl font-bold text-indigo-500">--</h2>
                <p id="lastUpdateConductividad" class="text-xs text-gray-400 mt-1">Sin actualizar</p>
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
        console.log('Cargando datos...');
        try {
            const response = await fetch('/api/sensores');
            console.log('Response status:', response.status);
            const datos = await response.json();
            console.log('Datos recibidos:', datos);

            if (datos.length > 0) {
                const ultimo = datos[0];

                actualizarCard('ph', ultimo.ph, ultimoPH);
                actualizarCard('temp', ultimo.temperatura, ultimaTemp, '°C');
                actualizarCard('turbidez', ultimo.turbidez, ultimaTurbidez, ' NTU');
                actualizarCard('oxigeno', ultimo.oxigeno_disuelto, ultimo.oxigeno_disuelto, ' mg/L');
                actualizarCard('amoniaco', ultimo.amoniaco, ultimo.amoniaco, ' mg/L');
                actualizarCard('nitritos', ultimo.nitritos, ultimo.nitritos, ' mg/L');
                actualizarCard('conductividad', ultimo.conductividad, ultimo.conductividad, ' μS/cm');

                ultimoPH = ultimo.ph;
                ultimaTemp = ultimo.temperatura;
                ultimaTurbidez = ultimo.turbidez;

                // Actualizar gráficas
                const etiquetas = datos.map(d => new Date(d.fecha).toLocaleTimeString()).reverse();
                actualizarGrafico(chartPh, etiquetas, datos.map(d => parseFloat(d.ph)).reverse());
                actualizarGrafico(chartTemp, etiquetas, datos.map(d => parseFloat(d.temperatura)).reverse());
                actualizarGrafico(chartTurbidez, etiquetas, datos.map(d => parseFloat(d.turbidez)).reverse());

                // Tabla (limitar según filtro)
                const datosLimitados = datos.slice(0, parseInt(filtro.value));
                bodyHistorial.innerHTML = datosLimitados.map(d => `
                    <tr class="border-t border-gray-200 hover:bg-gray-50">
                        <td class="py-2 px-3">${new Date(d.fecha).toLocaleString()}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.ph).toFixed(2)}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.temperatura).toFixed(2)}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.turbidez).toFixed(2)}</td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            actualizarEstadoConexion(false);
        }
    }

    function actualizarCard(tipo, nuevoValor, ultimoValor, unidad = '') {
        console.log(`Actualizando card ${tipo}:`, nuevoValor);
        const valorElem = document.getElementById(`${tipo}Value`);
        const iconElem = document.getElementById(`icon${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);
        const updateElem = document.getElementById(`lastUpdate${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`);

        if (!valorElem) {
            console.error(`Elemento ${tipo}Value no encontrado`);
            return;
        }

        if (nuevoValor !== ultimoValor) {
            iconElem?.classList.add('pulse-update');
            setTimeout(() => iconElem?.classList.remove('pulse-update'), 500);
        }

        valorElem.textContent = parseFloat(nuevoValor).toFixed(2) + unidad;
        if (updateElem) {
            updateElem.textContent = 'Última actualización: ' + new Date().toLocaleTimeString();
        }
    }

    function actualizarGrafico(chart, etiquetas, datos) {
        chart.data.labels = etiquetas;
        chart.data.datasets[0].data = datos;
        chart.update();
    }

    filtro.addEventListener('change', cargarDatos);

    // Sistema de notificaciones
    function mostrarNotificacion(mensaje, tipo = 'info') {
        const notificacion = document.createElement('div');
        notificacion.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            tipo === 'success' ? 'bg-green-500 text-white' :
            tipo === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notificacion.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${tipo === 'success' ? 'check' : tipo === 'error' ? 'times' : 'info'} mr-2"></i>
                ${mensaje}
            </div>
        `;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.remove();
        }, 3000);
    }

    // Actualizar estado de conexión
    function actualizarEstadoConexion(conectado) {
        const estado = document.getElementById('estadoConexion');
        if (conectado) {
            estado.className = 'bg-green-100 text-green-800 px-4 py-2 rounded-lg';
            estado.innerHTML = '<i class="fas fa-wifi mr-2"></i>Conectado';
        } else {
            estado.className = 'bg-red-100 text-red-800 px-4 py-2 rounded-lg';
            estado.innerHTML = '<i class="fas fa-wifi-slash mr-2"></i>Desconectado';
        }
    }

    // Cargar datos con manejo de errores
    async function cargarDatos() {
        try {
            const response = await fetch('/api/sensores');
            if (!response.ok) throw new Error('Error de red');
            
            const datos = await response.json();
            actualizarEstadoConexion(true);
            
            // Resto del código de cargarDatos...
            if (datos.length > 0) {
                const ultimo = datos[0];

                actualizarCard('ph', ultimo.ph, ultimoPH);
                actualizarCard('temp', ultimo.temperatura, ultimaTemp, '°C');
                actualizarCard('turbidez', ultimo.turbidez, ultimaTurbidez, ' NTU');
                actualizarCard('oxigeno', ultimo.oxigeno_disuelto, ultimo.oxigeno_disuelto, ' mg/L');
                actualizarCard('amoniaco', ultimo.amoniaco, ultimo.amoniaco, ' mg/L');
                actualizarCard('nitritos', ultimo.nitritos, ultimo.nitritos, ' mg/L');
                actualizarCard('conductividad', ultimo.conductividad, ultimo.conductividad, ' μS/cm');

                ultimoPH = ultimo.ph;
                ultimaTemp = ultimo.temperatura;
                ultimaTurbidez = ultimo.turbidez;

                // Actualizar gráficas
                const etiquetas = datos.map(d => new Date(d.fecha).toLocaleTimeString()).reverse();
                actualizarGrafico(chartPh, etiquetas, datos.map(d => parseFloat(d.ph)).reverse());
                actualizarGrafico(chartTemp, etiquetas, datos.map(d => parseFloat(d.temperatura)).reverse());
                actualizarGrafico(chartTurbidez, etiquetas, datos.map(d => parseFloat(d.turbidez)).reverse());

                // Tabla (limitar según filtro)
                const datosLimitados = datos.slice(0, parseInt(filtro.value));
                bodyHistorial.innerHTML = datosLimitados.map(d => `
                    <tr class="border-t border-gray-200 hover:bg-gray-50">
                        <td class="py-2 px-3">${new Date(d.fecha).toLocaleString()}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.ph).toFixed(2)}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.temperatura).toFixed(2)}</td>
                        <td class="py-2 px-3 text-center">${parseFloat(d.turbidez).toFixed(2)}</td>
                    </tr>
                `).join('');
            }
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            actualizarEstadoConexion(false);
        }
    }

    // Cargar datos iniciales y actualizar cada 10 segundos
    cargarDatos();
    setInterval(cargarDatos, 10000);
    
    console.log('Sistema de monitoreo iniciado - Actualización cada 10 segundos');
</script>
@endsection
