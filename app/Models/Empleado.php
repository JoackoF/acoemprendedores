<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_empleado',
        'nombre_completo',
        'estado_familiar',
        'documento_identidad',
        'fecha_nacimiento',
        'edad',
        'direccion_completa',
        'puesto',
        'departamento',
        'sueldo',
        'profesion',
        'correo_electronico',
        'telefono',
    ];

    // Relación con Transacciones (un Empleado puede tener muchas Transacciones)
    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }
}