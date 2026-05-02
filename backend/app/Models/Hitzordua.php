<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hitzordua extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hitzorduak';

    protected $fillable = [
        'lekua',
        'data',
        'hasiera_ordua',
        'bukaera_ordua',
        'iruzkinak',
        'ikasle_id',
        'bezero_id',
    ];

    public function bezeroa()
    {
        return $this->belongsTo(Bezeroa::class, 'bezero_id');
    }

    public function ikaslea()
    {
        return $this->belongsTo(Ikaslea::class, 'ikasle_id');
    }
}