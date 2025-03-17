<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoFinancieroController;
use App\Http\Controllers\TransaccionController;

// Rutas para Empleados
Route::resource('empleados', EmpleadoController::class);

// Rutas para Clientes
Route::resource('clientes', ClienteController::class);

// Rutas para Productos Financieros
Route::resource('productos-financieros', ProductoFinancieroController::class);

// Rutas para Transacciones
Route::resource('transacciones', TransaccionController::class);