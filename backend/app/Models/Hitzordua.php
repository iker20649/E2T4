<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hitzordua extends Model
{
    use HasFactory;

    // Nombre real de la tabla en tu DB
    protected $table = 'hitzorduak';

    // Columnas que permitimos rellenar
    protected $fillable = [
        'bezero_id',
        'profesional_id',
        'data',
        'hasiera_ordua',
        'amaiera_ordua',
        'iraupena',
        'oharrak'
    ];
}