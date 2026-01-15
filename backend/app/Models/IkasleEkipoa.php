<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IkasleEkipoa extends Model
{
    use SoftDeletes;
    protected $table = 'ikasle_ekipoak';
    protected $guarded = [];
}