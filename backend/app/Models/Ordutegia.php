<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ordutegia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordutegiak';
    protected $fillable = ['eguna', 'hasiera_data', 'bukaera_data', 'hasiera_ordua', 'bukaera_ordua', 'talde_id'];

    public function taldea()
    {
        return $this->belongsTo(Taldea::class, 'talde_id');
    }
}