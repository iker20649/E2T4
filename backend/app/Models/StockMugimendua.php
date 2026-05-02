<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMugimendua extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stock_mugimenduak';
    protected $fillable = ['ekipamendu_id', 'kantitatea', 'mota', 'arrazoia'];

    public function ekipamendua()
    {
        return $this->belongsTo(Ekipamendua::class, 'ekipamendu_id');
    }
}