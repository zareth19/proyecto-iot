<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - Sistema IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md" x-data="passwordForm()">
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
                <div class="relative">
                    <input :type="showCurrentPassword ? 'text' : 'password'" name="contraseña_actual" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Ingresa tu contraseña temporal">
                    <button type="button" @click="showCurrentPassword = !showCurrentPassword" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i :class="showCurrentPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                @error('contraseña_actual')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Nueva Contraseña
                </label>
                <div class="relative">
                    <input :type="showNewPassword ? 'text' : 'password'" name="nueva_contraseña" required
                           x-model="newPassword"
                           @input="validatePassword($event.target.value)"
                           :class="errors.newPassword ? 'w-full border border-red-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-red-500 focus:border-red-500' : 'w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-green-500 focus:border-green-500'"
                           placeholder="Mínimo 6 caracteres">
                    <button type="button" @click="showNewPassword = !showNewPassword" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i :class="showNewPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                <template x-if="errors.newPassword">
                    <p class="text-red-500 text-sm mt-1" x-text="errors.newPassword"></p>
                </template>
                @error('nueva_contraseña')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Confirmar Nueva Contraseña
                </label>
                <div class="relative">
                    <input :type="showConfirmPassword ? 'text' : 'password'" name="nueva_contraseña_confirmation" required
                           x-model="confirmPassword"
                           @input="validateConfirm($event.target.value)"
                           :class="errors.confirmPassword ? 'w-full border border-red-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-red-500 focus:border-red-500' : 'w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-green-500 focus:border-green-500'"
                           placeholder="Repite la nueva contraseña">
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
                <template x-if="errors.confirmPassword">
                    <p class="text-red-500 text-sm mt-1" x-text="errors.confirmPassword"></p>
                </template>
            </div>

            <!-- Botón -->
            <button type="submit" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                <i class="fas fa-check mr-2"></i>Cambiar Contraseña
            </button>
        </form>

        <!-- Validación en tiempo real -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg" x-show="newPassword">
            <h3 class="font-semibold text-blue-800 mb-2">🔒 Requisitos de contraseña:</h3>
            <ul class="text-sm space-y-1">
                <li :class="requirements.length ? 'text-green-700' : 'text-red-600'">
                    <i :class="requirements.length ? 'fas fa-check' : 'fas fa-times'" class="mr-2"></i>
                    Mínimo 6 caracteres
                </li>
                <li :class="requirements.letters ? 'text-green-700' : 'text-red-600'">
                    <i :class="requirements.letters ? 'fas fa-check' : 'fas fa-times'" class="mr-2"></i>
                    Al menos una letra
                </li>
                <li :class="requirements.numbers ? 'text-green-700' : 'text-gray-500'">
                    <i :class="requirements.numbers ? 'fas fa-check' : 'fas fa-circle'" class="mr-2"></i>
                    Al menos un número (recomendado)
                </li>
                <li :class="requirements.symbols ? 'text-green-700' : 'text-gray-500'">
                    <i :class="requirements.symbols ? 'fas fa-check' : 'fas fa-circle'" class="mr-2"></i>
                    Al menos un símbolo (recomendado)
                </li>
            </ul>
        </div>
        
        <!-- Info adicional cuando no hay password -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg" x-show="!newPassword">
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

<script>
function passwordForm() {
    return {
        currentPassword: '',
        newPassword: '',
        confirmPassword: '',
        errors: {},
        requirements: {
            length: false,
            letters: false,
            numbers: false,
            symbols: false
        },
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        validatePassword(password) {
            this.requirements.length = password.length >= 6;
            this.requirements.letters = /[a-zA-Z]/.test(password);
            this.requirements.numbers = /[0-9]/.test(password);
            this.requirements.symbols = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            if (password.length < 6) {
                this.errors.newPassword = 'Debe tener al menos 6 caracteres';
            } else if (!this.requirements.letters) {
                this.errors.newPassword = 'Debe contener al menos una letra';
            } else {
                this.errors.newPassword = '';
            }
        },
        validateConfirm(confirm) {
            if (confirm && confirm !== this.newPassword) {
                this.errors.confirmPassword = 'Las contraseñas no coinciden';
            } else {
                this.errors.confirmPassword = '';
            }
        }
    }
}
</script>
</body>
</html>