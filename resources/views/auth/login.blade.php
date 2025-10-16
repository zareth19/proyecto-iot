<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">

    <div x-data="{ images: ['{{ asset('imagenes/maxresdefault.jpg') }}','{{ asset('imagenes/descarga.webp') }}', '{{ asset('imagenes/piscicola.jpg') }}','{{ asset('imagenes/Foto-Referencial.png') }}'], current: 0 }"
        x-init="setInterval(() => current = (current + 1) % images.length, 5000)"
        class="relative w-full h-full overflow-hidden">

        <!-- Carrusel de fondo -->
        <template x-for="(image, index) in images" :key="index">
            <div x-show="current === index" 
                 x-transition:enter="transition-opacity ease-in-out duration-700"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0">
                <img :src="image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            </div>
        </template>

        <!-- FORMULARIO -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

                <div class="flex justify-center mb-4">
                    <img src="{{ asset('imagenes/LOGO_SENA.png') }}" alt="Logo" class="h-12">
                </div>

                <h2 class="text-center text-xl font-bold text-gray-800 mb-6">
                    INGRESO USUARIOS
                </h2>

                <!-- Formulario de inicio de sesión -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Tipo de documento -->
                    <div class="mb-4">
                        <label for="tipo_documento" class="block text-gray-700 mb-1">Tipo de Documento</label>
                        <select id="tipo_documento" name="tipo_documento"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                    </div>

                    <!-- Número de documento -->
                    <div class="mb-4 relative">
                        <input id="numero_documento" name="numero_documento" type="text"
                            placeholder="Número de Documento"
                            class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <span class="absolute left-3 top-2.5 text-gray-500">
                            <i class="fas fa-id-card"></i>
                        </span>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-4 relative">
                        <input id="contraseña" name="contraseña" type="password" placeholder="Contraseña"
                            class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <span class="absolute left-3 top-2.5 text-gray-500">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>

                    <!-- Olvidé contraseña -->
                    <div class="text-sm mb-4 ">
                        <a href="{{ route('password.request') }}" class="text-green-600 hover:underline">
                            Olvidé mi contraseña
                        </a>
                    </div>

                    <!-- Botón -->
                    <button type="submit"
                        class="w-full hover:bg-green-700 py-2 rounded-md shadow text-center">
                        INGRESAR
                    </button>

                    <!-- Mostrar errores -->
                    @if ($errors->any())
                        <div class="mt-4 text-red-600 text-sm">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>

</body>

<style>
button {
  background: transparent;
  position: relative;
  padding: 5px 15px;
  display: flex;
  align-items: center;
  justify-content: center; 
  font-size: 17px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  border: 1px solid #15803D;
  border-radius: 25px;
  outline: none;
  overflow: hidden;
  color: #15803D;
  transition: color 0.3s 0.1s ease-out;
  text-align: center; 
}

button::before {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  content: '';
  border-radius: 50%;
  display: block;
  width: 20em;
  height: 20em;
  left: -5em;
  text-align: center;
  transition: box-shadow 0.5s ease-out;
  z-index: -1;
}

button:hover {
  color: #fff;
  border: 1px solid #15803D;
  transform: scale(1.03);
}

button:hover::before {
  box-shadow: inset 0 0 0 10em #15803D;
}
</style>
</html>
