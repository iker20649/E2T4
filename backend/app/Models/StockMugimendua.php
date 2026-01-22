<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMugimendua extends Model
{
    protected $table = 'stock_mugimenduak';
    protected $fillable = ['user_id', 'produktua', 'kantitatea', 'ekintza'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}