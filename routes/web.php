<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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

require __DIR__.'/auth.php';
