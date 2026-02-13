<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Hau gehitzea ona da

class IkasleEkipoa extends Model
{
    use SoftDeletes;

    protected $table = 'ikasle_ekipoak';
    protected $guarded = [];

    /**
     * Ikasle bakoitza talde bati dagokio (3WAG2, 3PAG2...).
     */
    public function taldea(): BelongsTo
    {
        return $this->belongsTo(Taldea::class, 'taldea_id');
    }
}