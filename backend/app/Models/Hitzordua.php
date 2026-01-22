<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hitzordua extends Model
{
    use HasFactory;

    protected $table = 'hitzorduak';

    protected $fillable = [
        'bezeroa',
        'data',
        'deskribapena',
        'user_id',
        'finalizatuta'
    ];

    // Hitzordua erabiltzaile (ikasle) batena da
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}