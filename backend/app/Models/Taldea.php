<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taldea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'taldeak';
    protected $fillable = ['izena'];

    public function ikasleak()
    {
        return $this->hasMany(Ikaslea::class, 'talde_id');
    }

    public function ordutegiak()
    {
        return $this->hasMany(Ordutegia::class, 'talde_id');
    }
}