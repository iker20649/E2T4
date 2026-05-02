<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ikaslea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ikasleak';

    protected $fillable = [
        'izena',
        'abizena',
        'talde_id',
    ];

    public function taldea()
    {
        return $this->belongsTo(Taldea::class, 'talde_id');
    }

    public function hitzorduak()
    {
        return $this->hasMany(Hitzordua::class, 'ikasle_id');
    }

    public function ekipamenduak()
    {
        return $this->belongsToMany(Ekipamendua::class, 'ikasle_ekipamenduak', 'ikasle_id', 'ekipamendu_id')
                    ->withPivot('hasiera_data', 'bukaera_data');
    }

    public function txandak()
    {
        return $this->hasMany(Txanda::class, 'ikasle_id');
    }
}