<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ekipamendua extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ekipamenduak';
    protected $fillable = ['izena', 'stock', 'stock_minimoa'];

    public function ikasleak()
    {
        return $this->belongsToMany(Ikaslea::class, 'ikasle_ekipamenduak', 'ekipamendu_id', 'ikasle_id')
                    ->withPivot('hasiera_data', 'bukaera_data');
    }
}