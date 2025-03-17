<?php

namespace App\Http\Controllers;

use App\Models\ProductoFinanciero;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ProductoFinancieroController extends Controller
{
    // Mostrar lista de productos financieros
    public function index()
    {
        $productosFinancieros = ProductoFinanciero::with('cliente')->get();
        return view('productos-financieros.index', compact('productosFinancieros'));
    }

    // Mostrar formulario para crear un nuevo producto financiero
    public function create()
    {
        $clientes = Cliente::all();
        return view('productos-financieros.create', compact('clientes'));
    }

    // Guardar un nuevo producto financiero en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:cuenta_ahorro,cuenta_corriente,tarjeta_debito,tarjeta_credito,prestamo,seguro',
            'numero_referencia' => 'required|unique:productos_financieros',
            'fecha_apertura' => 'required|date',
            'monto_apertura' => 'nullable|numeric',
            'fecha_cierre' => 'nullable|date',
            'beneficiarios' => 'nullable|string',
            'limite_monto' => 'nullable|numeric',
            'tipo_red' => 'nullable|string',
            'categoria' => 'nullable|string',
            'tasa_interes' => 'nullable|numeric',
            'costo_membresia' => 'nullable|numeric',
            'plazo_pago_meses' => 'nullable|integer',
            'cuota' => 'nullable|numeric',
            'fecha_limite_pago' => 'nullable|date',
            'cuota_seguro' => 'nullable|numeric',
            'monto_asegurado' => 'nullable|numeric',
            'fecha_contratacion' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'tipo_seguro' => 'nullable|string',
            'renta_diaria_hospitalizacion' => 'nullable|numeric',
            'causas_aplicables' => 'nullable|string',
        ]);

        ProductoFinanciero::create($request->all());

        return redirect()->route('productos-financieros.index')
                         ->with('success', 'Producto Financiero creado exitosamente.');
    }

    // Mostrar detalles de un producto financiero específico
    public function show(ProductoFinanciero $productoFinanciero)
    {
        return view('productos-financieros.show', compact('productoFinanciero'));
    }

    // Mostrar formulario para editar un producto financiero existente
    public function edit(ProductoFinanciero $productoFinanciero)
    {
        $clientes = Cliente::all();
        return view('productos-financieros.edit', compact('productoFinanciero', 'clientes'));
    }

    // Actualizar un producto financiero en la base de datos
    public function update(Request $request, ProductoFinanciero $productoFinanciero)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:cuenta_ahorro,cuenta_corriente,tarjeta_debito,tarjeta_credito,prestamo,seguro',
            'numero_referencia' => 'required|unique:productos_financieros,numero_referencia,' . $productoFinanciero->id,
            'fecha_apertura' => 'required|date',
            'monto_apertura' => 'nullable|numeric',
            'fecha_cierre' => 'nullable|date',
            'beneficiarios' => 'nullable|string',
            'limite_monto' => 'nullable|numeric',
            'tipo_red' => 'nullable|string',
            'categoria' => 'nullable|string',
            'tasa_interes' => 'nullable|numeric',
            'costo_membresia' => 'nullable|numeric',
            'plazo_pago_meses' => 'nullable|integer',
            'cuota' => 'nullable|numeric',
            'fecha_limite_pago' => 'nullable|date',
            'cuota_seguro' => 'nullable|numeric',
            'monto_asegurado' => 'nullable|numeric',
            'fecha_contratacion' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date',
            'tipo_seguro' => 'nullable|string',
            'renta_diaria_hospitalizacion' => 'nullable|numeric',
            'causas_aplicables' => 'nullable|string',
        ]);

        $productoFinanciero->update($request->all());

        return redirect()->route('productos-financieros.index')
                         ->with('success', 'Producto Financiero actualizado exitosamente.');
    }

    // Eliminar un producto financiero de la base de datos
    public function destroy(ProductoFinanciero $productoFinanciero)
    {
        $productoFinanciero->delete();

        return redirect()->route('productos-financieros.index')
                         ->with('success', 'Producto Financiero eliminado exitosamente.');
    }
}