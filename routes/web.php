<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\EstandarController;


/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/dashboard/admin/usuarios/{id}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/store', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('/dashboard/admin/usuarios/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.users.delete');
    Route::get('/dashboard/admin/sensores', [AdminController::class, 'dashboardSensores'])->name('admin.sensores');

});



/*
|--------------------------------------------------------------------------
| RUTAS OPERARIO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:operario'])->group(function () {
    Route::get('/dashboard/operario', [OperarioController::class, 'index'])->name('dashboard.operario');

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\SensorController;

Route::get('/enviar-alerta', [AlertaController::class, 'enviar']);

// Rutas para Arduino (sin middleware de autenticación)
Route::post('/api/sensores', [SensorController::class, 'recibirDatos']);
Route::get('/api/sensores', [SensorController::class, 'obtenerDatos']);

// Ruta de prueba para verificar conectividad
Route::get('/api/test', function() {
    return response()->json(['status' => 'ok', 'message' => 'API funcionando']);
});

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| RUTAS ESTÁNDAR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:estandar'])->group(function () {
    Route::get('/dashboard/estandar', [EstandarController::class, 'index'])->name('dashboard.estandar');

});

/*
|--------------------------------------------------------------------------
| RUTA TEMPORAL DE RECUPERAR CONTRASEÑA
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return 'Función de recuperar contraseña no habilitada todavía.';
})->name('password.request');
