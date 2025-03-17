<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoFinanciero extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'numero_referencia',
        'fecha_apertura',
        'monto_apertura',
        'fecha_cierre',
        'beneficiarios',
        'limite_monto',
        'tipo_red',
        'categoria',
        'tasa_interes',
        'costo_membresia',
        'plazo_pago_meses',
        'cuota',
        'fecha_limite_pago',
        'cuota_seguro',
        'monto_asegurado',
        'fecha_contratacion',
        'fecha_finalizacion',
        'tipo_seguro',
        'renta_diaria_hospitalizacion',
        'causas_aplicables',
    ];

    // Relación con Cliente (un Producto Financiero pertenece a un Cliente)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con Transacciones (un Producto Financiero puede tener muchas Transacciones)
    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }
}