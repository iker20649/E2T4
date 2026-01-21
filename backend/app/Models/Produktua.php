<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produktua extends Model
{
    protected $table = 'produktuak'; // Importante para que encuentre la tabla

    protected $fillable = [
        'izena',
        'marka',
        'stock',
        'stock_minimo',
        'prezioa'
    ];
}
