<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_transaccion',
        'producto_financiero_id',
        'empleado_id',
        'monto',
        'fecha_transaccion',
    ];

    // Relación con Producto Financiero (una Transacción pertenece a un Producto Financiero)
    public function productoFinanciero()
    {
        return $this->belongsTo(ProductoFinanciero::class);
    }

    // Relación con Empleado (una Transacción pertenece a un Empleado)
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}