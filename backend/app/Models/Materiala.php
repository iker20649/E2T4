<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiala extends Model
{
    protected $table = 'materialas'; // Su tabla correcta

    // Campos que el Frontend está enviando y que existen en la DB
    protected $fillable = [
        'izena', 
        'etiketa', 
        'stock', 
        'stock_minimo', 
        'libre', 
        'mota'
    ];
}
