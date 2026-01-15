<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bezeroa extends Model
{
    use SoftDeletes;
    protected $table = 'bezeroak'; // IMPORTANTE: Enlaza con la tabla en euskera
    protected $guarded = []; // Permite rellenar todos los campos
}