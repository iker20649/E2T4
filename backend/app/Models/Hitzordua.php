<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hitzordua extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'hitzorduak';

    // Campos que permitimos guardar (¡Añadido finalizatuta!)
    protected $fillable = [
        'data',
        'ordua',
        'bezero_id',
        'finalizatuta'
    ];

    /**
     * Relación: Una cita pertenece a un usuario (cliente).
     */
    public function bezeroa()
    {
        return $this->belongsTo(User::class, 'bezero_id');
    }
}