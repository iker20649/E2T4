<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Produktua extends Model {
    public function maileguak()
{
    return $this->hasMany(Mailegua::class, 'materiala_id');
}

public function azken_mailegua()
{
    return $this->hasOne(Mailegua::class, 'materiala_id')->where('itzulita', false)->latest();
}

// Zutabe berria masiboki kargatzeko baimena eman
protected $fillable = ['izena', 'marka', 'stock', 'stock_minimo', 'prezioa', 'is_material'];
}