<?php

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoFinancieroController;
use App\Http\Controllers\TransaccionController;

Route::resource('empleados', EmpleadoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('productos-financieros', ProductoFinancieroController::class);
Route::resource('transacciones', TransaccionController::class);