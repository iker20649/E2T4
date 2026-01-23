<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bezeroa extends Model
{
    use HasFactory;
    protected $table = 'bezeroak';
    protected $fillable = ['izena', 'abizenak', 'telefonoa', 'bisita_kopurua', 'azken_bisita', 'deskribapena', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}