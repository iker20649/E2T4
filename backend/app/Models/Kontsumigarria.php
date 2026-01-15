<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kontsumigarria extends Model
{
    use SoftDeletes;
    protected $table = 'kontsumigarriak';
    protected $guarded = [];
}