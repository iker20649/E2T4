<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produktua extends Model
{
    protected $table = 'produktuak'; // ¡Cambiado de materialas a produktuak!

    protected $fillable = [
        'izena', 
        'marka', 
        'stock', 
        'stock_minimo', 
        'prezioa', 
        'kategoria_id'
    ];
}
