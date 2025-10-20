<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-lock text-6xl text-red-500 mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">403</h1>
            <h2 class="text-2xl font-semibold text-gray-600 mb-4">Acceso denegado</h2>
            <p class="text-gray-500 mb-8">No tienes permisos para acceder a esta página.</p>
        </div>
        
        <div class="space-y-4">
            <a href="{{ route('login') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-home mr-2"></i>Volver al Inicio
            </a>
        </div>
    </div>
</body>
</html>