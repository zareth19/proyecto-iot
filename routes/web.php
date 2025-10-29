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
    Route::get('/dashboard/admin', [AdminController::class, 'dashboardSensores'])->name('dashboard.admin');
    
    // Admin routes without middleware restriction
    Route::get('/dashboard/admin/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/dashboard/admin/sensores', [AdminController::class, 'dashboardSensores'])->name('admin.sensores');
    Route::get('/admin/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.reportes');
    Route::get('/admin/estanques', [App\Http\Controllers\EstanqueController::class, 'index'])->name('admin.estanques');
    Route::post('/admin/estanques', [App\Http\Controllers\EstanqueController::class, 'store'])->name('admin.estanques.store');
    Route::delete('/admin/estanques/{id}', [App\Http\Controllers\EstanqueController::class, 'destroy'])->name('admin.estanques.destroy');
    Route::patch('/admin/estanques/{id}/estado', [App\Http\Controllers\EstanqueController::class, 'cambiarEstado'])->name('admin.estanques.estado');
    Route::post('/admin/estanques/{id}/asignar-sensor', [App\Http\Controllers\EstanqueController::class, 'asignarSensor'])->name('admin.estanques.asignar.sensor');
    Route::get('/admin/estanques/{id}/edit', [App\Http\Controllers\EstanqueController::class, 'edit'])->name('admin.estanques.edit');
    Route::put('/admin/estanques/{id}', [App\Http\Controllers\EstanqueController::class, 'update'])->name('admin.estanques.update');
    Route::delete('/admin/estanques/{id}/quitar-sensor', [App\Http\Controllers\EstanqueController::class, 'quitarSensor'])->name('admin.estanques.quitar.sensor');
    Route::get('/admin/parametros', [App\Http\Controllers\ParametrosCultivoController::class, 'index'])->name('admin.parametros');
    Route::post('/admin/parametros', [App\Http\Controllers\ParametrosCultivoController::class, 'store'])->name('admin.parametros.store');
    Route::delete('/admin/parametros/{id}', [App\Http\Controllers\ParametrosCultivoController::class, 'destroy'])->name('admin.parametros.destroy');
    
    // Rutas para cultivo de peces
    Route::get('/admin/cultivo-peces', [App\Http\Controllers\CultivoPezController::class, 'index'])->name('admin.cultivo-peces.index');
    Route::post('/admin/cultivo-peces', [App\Http\Controllers\CultivoPezController::class, 'store'])->name('admin.cultivo-peces.store');
    Route::get('/admin/cultivo-peces/{id}/edit', [App\Http\Controllers\CultivoPezController::class, 'edit'])->name('admin.cultivo-peces.edit');
    Route::put('/admin/cultivo-peces/{id}', [App\Http\Controllers\CultivoPezController::class, 'update'])->name('admin.cultivo-peces.update');
    Route::delete('/admin/cultivo-peces/{id}', [App\Http\Controllers\CultivoPezController::class, 'destroy'])->name('admin.cultivo-peces.destroy');
    Route::post('/admin/cultivo-peces/{id}/toggle-activo', [App\Http\Controllers\CultivoPezController::class, 'toggleActivo'])->name('admin.cultivo-peces.toggle-activo');
    
    // API-like routes (allow direct access for functionality)
    Route::post('/admin/users/store', [AdminController::class, 'store'])->name('admin.users.store');
    Route::put('/dashboard/admin/usuarios/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.users.delete');
    Route::post('/admin/reportes/generar', [App\Http\Controllers\ReporteController::class, 'generar']);
    Route::get('/admin/reportes/exportar', [App\Http\Controllers\ReporteController::class, 'exportar']);
    Route::get('/admin/reportes-manuales/exportar', [App\Http\Controllers\ReporteController::class, 'exportarManuales'])->name('admin.reportes.manuales.exportar');
    Route::get('/admin/reportes-manuales/{id}/descargar', [App\Http\Controllers\ReporteController::class, 'descargarManualIndividual'])->name('admin.reportes.manuales.descargar');
});



/*
|--------------------------------------------------------------------------
| RUTAS OPERARIO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:operario'])->group(function () {
    Route::get('/dashboard/operario', [OperarioController::class, 'dashboardSensores'])->name('dashboard.operario');
    
    // Operario routes without middleware restriction
    Route::get('/operario/reportes', [App\Http\Controllers\ReporteManualesController::class, 'index'])->name('operario.reportes.index');
    Route::get('/operario/reportes/crear', [App\Http\Controllers\ReporteManualesController::class, 'crear'])->name('operario.reportes.crear');
    Route::get('/operario/comparacion', [App\Http\Controllers\ReporteManualesController::class, 'comparacion'])->name('operario.comparacion');
    Route::get('/operario/estanques', [App\Http\Controllers\EstanqueController::class, 'index'])->name('operario.estanques');
    Route::patch('/operario/estanques/{id}/estado', [App\Http\Controllers\EstanqueController::class, 'cambiarEstado'])->name('operario.estanques.estado');
    Route::get('/operario/sensores', [OperarioController::class, 'dashboardSensores'])->name('operario.sensores');
    Route::get('/operario/datos-sensores', [OperarioController::class, 'datosSensores'])->name('operario.datos.sensores');
    
    // API-like routes (allow direct access for functionality)
    Route::post('/operario/reportes', [App\Http\Controllers\ReporteManualesController::class, 'store'])->name('operario.reportes.store');
    Route::get('/operario/reportes/exportar', [App\Http\Controllers\ReporteManualesController::class, 'exportarPDF'])->name('operario.reportes.exportar');
    Route::get('/operario/reportes/{id}/descargar', [App\Http\Controllers\ReporteManualesController::class, 'descargarIndividual'])->name('operario.reportes.descargar');
});

/*
|--------------------------------------------------------------------------
| RUTAS ESTÁNDAR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:estandar'])->group(function () {
    Route::get('/dashboard/estandar', [EstandarController::class, 'dashboardSensores'])->name('dashboard.estandar');
    Route::get('/dashboard/estandar/sensores', [EstandarController::class, 'dashboardSensores'])->name('estandar.sensores');
    Route::get('/estandar/datos-sensores', [EstandarController::class, 'datosSensores'])->name('estandar.datos.sensores');
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

// Rutas de alertas para la plataforma
Route::middleware('auth')->group(function () {
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::post('/alertas/{id}/marcar-leida', [AlertaController::class, 'marcarLeida'])->name('alertas.marcar.leida');
    Route::post('/alertas/marcar-todas-leidas', [AlertaController::class, 'marcarTodasLeidas'])->name('alertas.marcar.todas');
    Route::get('/alertas/no-leidas', [AlertaController::class, 'obtenerNoLeidas'])->name('alertas.no.leidas');
});

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