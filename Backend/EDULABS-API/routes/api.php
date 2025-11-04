<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivoController; // 👈 agregado

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas de la API. Todas las rutas protegidas
| usan Sanctum para autenticación.
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // 🔐 Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);

    // 📁 Archivos de usuario (controlador nuevo)
    Route::get('/archivos', [ArchivoController::class, 'listar']);
    Route::post('/archivos/subir', [ArchivoController::class, 'upload']);
    Route::delete('/archivos/{id}', [ArchivoController::class, 'eliminar']);

    // 📦 Archivos anteriores (si aún los usas)
    Route::get('/files', [FileController::class, 'index']);
    Route::post('/files', [FileController::class, 'store']);

    // ⚙️ Administración
    Route::get('/admin/settings', [AdminController::class, 'settings']);
    Route::put('/admin/settings', [AdminController::class, 'updateSettings']);

    Route::get('/admin/groups', [AdminController::class, 'groupsIndex']);
    Route::post('/admin/groups', [AdminController::class, 'groupsStore']);
    Route::put('/admin/groups/{grupo}', [AdminController::class, 'groupsUpdate']);
    Route::delete('/admin/groups/{grupo}', [AdminController::class, 'groupsDestroy']);

    Route::put('/admin/users/{user}/group', [AdminController::class, 'assignUserGroup']);
    Route::put('/admin/users/{user}/quota', [AdminController::class, 'setUserQuota']);
});
