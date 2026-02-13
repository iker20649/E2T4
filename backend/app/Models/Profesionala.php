<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profesionala extends Model
{
    use SoftDeletes;

    // LERRO HAU DA GAKOA:
    protected $table = 'profesionalak'; 

    protected $guarded = [];
}