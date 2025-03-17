<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    // Mostrar lista de empleados
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    // Mostrar formulario para crear un nuevo empleado
    public function create()
    {
        return view('empleados.create');
    }

    // Guardar un nuevo empleado en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'codigo_empleado' => 'required|unique:empleados',
            'nombre_completo' => 'required',
            'documento_identidad' => 'required|unique:empleados',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'direccion_completa' => 'required',
            'puesto' => 'required',
            'departamento' => 'required',
            'sueldo' => 'required|numeric',
            'profesion' => 'required',
            'correo_electronico' => 'required|email|unique:empleados',
            'telefono' => 'required',
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado creado exitosamente.');
    }

    // Mostrar detalles de un empleado específico
    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    // Mostrar formulario para editar un empleado existente
    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    // Actualizar un empleado en la base de datos
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'codigo_empleado' => 'required|unique:empleados,codigo_empleado,' . $empleado->id,
            'nombre_completo' => 'required',
            'documento_identidad' => 'required|unique:empleados,documento_identidad,' . $empleado->id,
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer',
            'direccion_completa' => 'required',
            'puesto' => 'required',
            'departamento' => 'required',
            'sueldo' => 'required|numeric',
            'profesion' => 'required',
            'correo_electronico' => 'required|email|unique:empleados,correo_electronico,' . $empleado->id,
            'telefono' => 'required',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado actualizado exitosamente.');
    }

    // Eliminar un empleado de la base de datos
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado eliminado exitosamente.');
    }
}