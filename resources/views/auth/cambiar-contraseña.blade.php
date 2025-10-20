<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - Sistema IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Cambio de contraseña obligatorio</strong>
            </div>
            <img src="{{ asset('imagenes/logo sena.png') }}" alt="Logo SENA" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800">🔐 Cambiar Contraseña</h1>
            <p class="text-gray-600 mt-2">Por seguridad, debes cambiar tu contraseña temporal</p>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('cambiar.contraseña.post') }}" class="space-y-6">
            @csrf

            <!-- Contraseña Actual -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key mr-2"></i>Contraseña Temporal
                </label>
                <input type="password" name="contraseña_actual" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Ingresa tu contraseña temporal">
                @error('contraseña_actual')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Nueva Contraseña
                </label>
                <input type="password" name="nueva_contraseña" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Mínimo 6 caracteres">
                @error('nueva_contraseña')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Confirmar Nueva Contraseña
                </label>
                <input type="password" name="nueva_contraseña_confirmation" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Repite la nueva contraseña">
            </div>

            <!-- Botón -->
            <button type="submit" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                <i class="fas fa-check mr-2"></i>Cambiar Contraseña
            </button>
        </form>

        <!-- Info adicional -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">💡 Consejos para una contraseña segura:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Mínimo 6 caracteres</li>
                <li>• Combina letras, números y símbolos</li>
                <li>• No uses información personal</li>
                <li>• Hazla única y memorable</li>
            </ul>
        </div>

        <!-- Logout -->
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>