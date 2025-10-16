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
    Route::get('/dashboard/admin/sensores', [AdminController::class, 'dashboardSensores'])->name('admin.sensores');

});

/*
|--------------------------------------------------------------------------
| RUTAS ESTÁNDAR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:estandar'])->group(function () {
    Route::get('/dashboard/estandar', [EstandarController::class, 'index'])->name('dashboard.estandar');
    Route::get('/dashboard/admin/sensores', [AdminController::class, 'dashboardSensores'])->name('admin.sensores');

});

/*
|--------------------------------------------------------------------------
| RUTA TEMPORAL DE RECUPERAR CONTRASEÑA
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return 'Función de recuperar contraseña no habilitada todavía.';
})->name('password.request');
