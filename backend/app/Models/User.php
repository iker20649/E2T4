<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- HAU GEHITU
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    // HasFactory gehitu dugu trait gisa
    use HasApiTokens, Notifiable, HasFactory; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'rola',      
        'argazkia',  
    ];

    protected $hidden = ['password', 'remember_token'];
}