<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Txanda extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'txandak';
    protected $fillable = ['ikasle_id', 'data', 'hasiera_ordua', 'bukaera_ordua', 'rola', 'oharrak'];

    protected $casts = [
        'data' => 'date',
        'hasiera_ordua' => 'datetime:H:i',
        'bukaera_ordua' => 'datetime:H:i',
    ];

    public function ikaslea()
    {
        return $this->belongsTo(Ikaslea::class, 'ikasle_id');
    }

    public function scopeData($query, $data)
    {
        return $query->whereDate('data', $data);
    }

    public function scopeHarrera($query)
    {
        return $query->where('rola', 'harrera');
    }
}