<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hitzordua extends Model
{
    use SoftDeletes;
    protected $table = 'hitzorduak';
    protected $guarded = [];

    // Relación: Una cita pertenece a un cliente
    public function bezeroa() {
        return $this->belongsTo(Bezeroa::class, 'bezero_id');
    }
}