<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CalendarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de inicio
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('cliente.dashboard');
    }
    return redirect()->route('login');
});

// Autenticación (con rate limiting en login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login'); // ← AGREGADO: Rate limiting
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1'); // ← AGREGADO: Rate limiting
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Calendario público (accesible para todos los autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/eventos', [CalendarioController::class, 'eventos'])->name('calendario.eventos');
    Route::post('/calendario/verificar-disponibilidad', [CalendarioController::class, 'verificarDisponibilidad'])->name('calendario.verificar');
});

// ← NUEVO: API para verificar disponibilidad con AJAX
Route::middleware(['auth', 'throttle:api'])->prefix('api')->name('api.')->group(function () {
    Route::post('/verificar-disponibilidad', [CitaController::class, 'verificarDisponibilidadApi'])
        ->name('verificar-disponibilidad');
});

// Rutas de Admin
Route::middleware(['auth', App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de clientes
    Route::get('/clientes', [AdminController::class, 'clientes'])->name('clientes');
    Route::post('/clientes/{user}/aprobar', [AdminController::class, 'aprobarCliente'])->name('clientes.aprobar');
    
    // Gestión de citas
    Route::get('/citas', [AdminController::class, 'citas'])->name('citas');
    Route::post('/citas/{cita}/aprobar', [AdminController::class, 'aprobarCita'])->name('citas.aprobar');
    Route::post('/citas/{cita}/rechazar', [AdminController::class, 'rechazarCita'])->name('citas.rechazar');
    
    // Gestión de servicios
    Route::get('/servicios', [AdminController::class, 'servicios'])->name('servicios');
    Route::post('/servicios', [AdminController::class, 'crearServicio'])->name('servicios.crear');
    Route::put('/servicios/{servicio}', [AdminController::class, 'actualizarServicio'])->name('servicios.actualizar');
    Route::post('/servicios/{servicio}/toggle', [AdminController::class, 'toggleServicio'])->name('servicios.toggle');
});

// Rutas de Cliente (con rate limiting para citas)
Route::middleware(['auth', 'throttle:citas'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [CitaController::class, 'dashboard'])->name('dashboard');
    
    // Citas
    Route::get('/citas', [CitaController::class, 'misCitas'])->name('mis-citas');
    Route::get('/citas/crear', [CitaController::class, 'crear'])->name('citas.crear');
    Route::post('/citas', [CitaController::class, 'guardar'])->name('citas.guardar');
    Route::post('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    
    // Historial (solo clientes permanentes)
    Route::get('/historial', [CitaController::class, 'historial'])
        ->name('historial')
        ->middleware(App\Http\Middleware\ClientePermanenteMiddleware::class);
});