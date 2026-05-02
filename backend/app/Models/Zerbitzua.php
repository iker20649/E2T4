<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zerbitzua extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zerbitzuak';
    protected $fillable = ['izena', 'prezioa', 'etxeko_prezioa', 'iraupena'];

    protected $casts = [
        'prezioa' => 'decimal:2',
        'etxeko_prezioa' => 'decimal:2',
    ];
}