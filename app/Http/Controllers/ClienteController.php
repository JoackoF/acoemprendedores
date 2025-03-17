<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Mostrar lista de clientes
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    // Mostrar formulario para crear un nuevo cliente
    public function create()
    {
        return view('clientes.create');
    }

    // Guardar un nuevo cliente en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'documento_identidad' => 'required|unique:clientes',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'direccion_completa' => 'required',
            'estado_familiar' => 'required',
            'profesion' => 'required',
            'correo_electronico' => 'required|email|unique:clientes',
            'telefono' => 'required',
            'lugar_trabajo' => 'required',
            'direccion_trabajo' => 'required',
            'salario_mensual' => 'required|numeric',
            'otros_ingresos' => 'nullable|numeric',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado exitosamente.');
    }

    // Mostrar detalles de un cliente específico
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    // Mostrar formulario para editar un cliente existente
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // Actualizar un cliente en la base de datos
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'documento_identidad' => 'required|unique:clientes,documento_identidad,' . $cliente->id,
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'direccion_completa' => 'required',
            'estado_familiar' => 'required',
            'profesion' => 'required',
            'correo_electronico' => 'required|email|unique:clientes,correo_electronico,' . $cliente->id,
            'telefono' => 'required',
            'lugar_trabajo' => 'required',
            'direccion_trabajo' => 'required',
            'salario_mensual' => 'required|numeric',
            'otros_ingresos' => 'nullable|numeric',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado exitosamente.');
    }

    // Eliminar un cliente de la base de datos
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado exitosamente.');
    }
}
