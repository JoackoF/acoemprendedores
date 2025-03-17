<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\ProductoFinanciero;
use App\Models\Empleado;
use Illuminate\Http\Request;

class TransaccionController extends Controller
{
    // Mostrar lista de transacciones
    public function index()
    {
        $transacciones = Transaccion::with(['productoFinanciero', 'empleado'])->get();
        return view('transacciones.index', compact('transacciones'));
    }

    // Mostrar formulario para crear una nueva transacción
    public function create()
    {
        $productosFinancieros = ProductoFinanciero::all();
        $empleados = Empleado::all();
        return view('transacciones.create', compact('productosFinancieros', 'empleados'));
    }

    // Guardar una nueva transacción en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'codigo_transaccion' => 'required|unique:transacciones',
            'producto_financiero_id' => 'required|exists:productos_financieros,id',
            'empleado_id' => 'required|exists:empleados,id',
            'monto' => 'required|numeric',
            'fecha_transaccion' => 'required|date',
        ]);

        Transaccion::create($request->all());

        return redirect()->route('transacciones.index')
                         ->with('success', 'Transacción creada exitosamente.');
    }

    // Mostrar detalles de una transacción específica
    public function show(Transaccion $transaccion)
    {
        return view('transacciones.show', compact('transaccion'));
    }

    // Mostrar formulario para editar una transacción existente
    public function edit(Transaccion $transaccion)
    {
        $productosFinancieros = ProductoFinanciero::all();
        $empleados = Empleado::all();
        return view('transacciones.edit', compact('transaccion', 'productosFinancieros', 'empleados'));
    }

    // Actualizar una transacción en la base de datos
    public function update(Request $request, Transaccion $transaccion)
    {
        $request->validate([
            'codigo_transaccion' => 'required|unique:transacciones,codigo_transaccion,' . $transaccion->id,
            'producto_financiero_id' => 'required|exists:productos_financieros,id',
            'empleado_id' => 'required|exists:empleados,id',
            'monto' => 'required|numeric',
            'fecha_transaccion' => 'required|date',
        ]);

        $transaccion->update($request->all());

        return redirect()->route('transacciones.index')
                         ->with('success', 'Transacción actualizada exitosamente.');
    }

    // Eliminar una transacción de la base de datos
    public function destroy(Transaccion $transaccion)
    {
        $transaccion->delete();

        return redirect()->route('transacciones.index')
                         ->with('success', 'Transacción eliminada exitosamente.');
    }
}