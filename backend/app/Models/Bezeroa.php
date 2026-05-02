<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bezeroa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bezeroak';

    protected $fillable = [
        'izena',
        'abizenak',
        'telefonoa',
        'email',
        'etxeko_bezeroa',
    ];

    public function hitzorduak()
    {
        return $this->hasMany(Hitzordua::class, 'bezero_id');
    }
}