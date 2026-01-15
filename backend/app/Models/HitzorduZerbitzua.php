<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HitzorduZerbitzua extends Model
{
    use SoftDeletes;
    protected $table = 'hitzordu_zerbitzuak'; // Enlace con la tabla pibot
    protected $guarded = [];
}