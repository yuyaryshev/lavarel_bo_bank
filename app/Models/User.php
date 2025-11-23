<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'is_system',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
		'birth_date' => 'date:Y-m-d',
		'is_system'  => 'boolean',
		'balance'    => 'decimal:2',
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
    ];
}
