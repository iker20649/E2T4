<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'rola', 'taldea_id'];
    public function taldea() { return $this->belongsTo(Taldea::class); }
}