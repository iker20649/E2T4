<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontsumoErregistroa extends Model
{
    use SoftDeletes;
    protected $table = 'kontsumo_erregistroak';
    protected $guarded = [];
}