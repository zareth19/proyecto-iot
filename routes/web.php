<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\EstandarController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas para cambio de contraseña
Route::middleware('auth')->group(function () {
    Route::get('/cambiar-contraseña', [App\Http\Controllers\CambiarContraseñaController::class, 'mostrar'])->name('cambiar.contraseña');
    Route::post('/cambiar-contraseña', [App\Http\Controllers\CambiarContraseñaController::class, 'cambiar'])->name('cambiar.contraseña.post');
});

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
    Route::get('/admin/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.reportes');
    Route::post('/admin/reportes/generar', [App\Http\Controllers\ReporteController::class, 'generar']);
    Route::get('/admin/reportes/exportar', [App\Http\Controllers\ReporteController::class, 'exportar']);

});



/*
|--------------------------------------------------------------------------
| RUTAS OPERARIO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:operario'])->group(function () {
    Route::get('/dashboard/operario', [OperarioController::class, 'index'])->name('dashboard.operario');
    Route::get('/operario/reportes', [App\Http\Controllers\ReporteManualesController::class, 'index'])->name('operario.reportes.index');
    Route::get('/operario/reportes/crear', [App\Http\Controllers\ReporteManualesController::class, 'crear'])->name('operario.reportes.crear');
    Route::post('/operario/reportes', [App\Http\Controllers\ReporteManualesController::class, 'store'])->name('operario.reportes.store');
    Route::get('/operario/reportes/exportar', [App\Http\Controllers\ReporteManualesController::class, 'exportarPDF'])->name('operario.reportes.exportar');
    Route::get('/operario/reportes/{id}/descargar', [App\Http\Controllers\ReporteManualesController::class, 'descargarIndividual'])->name('operario.reportes.descargar');
    Route::get('/operario/comparacion', [App\Http\Controllers\ReporteManualesController::class, 'comparacion'])->name('operario.comparacion');
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

// Rutas de recuperación de contraseña
Route::get('/recuperar-contraseña', [App\Http\Controllers\RecuperarContraseñaController::class, 'mostrar'])->name('recuperar.contraseña');
Route::post('/recuperar-contraseña', [App\Http\Controllers\RecuperarContraseñaController::class, 'enviar'])->name('recuperar.contraseña.enviar');

/*
|--------------------------------------------------------------------------
| RUTAS API SENSORES
|--------------------------------------------------------------------------
*/

Route::get('/enviar-alerta', [AlertaController::class, 'enviar']);
Route::post('/api/sensores', [SensorController::class, 'recibirDatos']);
Route::get('/api/sensores', [SensorController::class, 'obtenerDatos']);
Route::get('/api/test', function() {
    return response()->json(['status' => 'ok', 'message' => 'API funcionando']);
});
Route::get('/api/alertas', [AlertaController::class, 'obtenerAlertas']);
Route::get('/api/alertas/no-leidas', [AlertaController::class, 'contarNoLeidas']);
Route::post('/api/alertas/{id}/leida', [AlertaController::class, 'marcarLeida']);

/*
|--------------------------------------------------------------------------
| RUTAS PERFIL
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [App\Http\Controllers\PerfilController::class, 'actualizar'])->name('perfil.actualizar');
});