<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'documento_identidad',
        'fecha_nacimiento',
        'edad',
        'direccion_completa',
        'estado_familiar',
        'profesion',
        'correo_electronico',
        'telefono',
        'lugar_trabajo',
        'direccion_trabajo',
        'salario_mensual',
        'otros_ingresos',
    ];

    // Relación con Productos Financieros (un Cliente puede tener muchos Productos Financieros)
    public function productosFinancieros()
    {
        return $this->hasMany(ProductoFinanciero::class);
    }
}