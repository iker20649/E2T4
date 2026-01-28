<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Añade esto
use Illuminate\Database\Eloquent\Model;

class StockMugimendua extends Model
{
    use HasFactory; // Añade esto

    protected $table = 'stock_mugimenduak';
    protected $fillable = ['user_id', 'produktua', 'kantitatea', 'ekintza'];

    public function user()
    {
        // Forzamos la ruta completa de la clase para evitar fallos de carga
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}