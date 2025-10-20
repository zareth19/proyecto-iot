<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-400 to-green-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <img src="{{ asset('imagenes/LOGO_SENA.png') }}" alt="Logo SENA" class="h-16 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Recuperar Contraseña</h2>
            <p class="text-gray-600 mt-2">Ingresa tu correo electrónico para recibir una nueva contraseña</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('recuperar.contraseña.enviar') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-envelope mr-2"></i>Correo Electrónico
                </label>
                <input type="email" name="correo" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                    placeholder="tu@correo.com">
            </div>

            <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-paper-plane mr-2"></i>Enviar Nueva Contraseña
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Volver al Login
            </a>
        </div>
    </div>
</body>
</html>