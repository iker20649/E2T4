<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Erabiltzailea extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'erabiltzaileak';

    protected $fillable = [
        'izena',
        'email',
        'password',
        'rola',
        'argazkia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    const ROLA_ADMIN = 'admin';
    const ROLA_HARRERA = 'harrera';
    const ROLA_IKASLE = 'ikasle';
}