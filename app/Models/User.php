<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasFactory, Notifiable, AuthenticatesWithLdap;

    protected $fillable = [
        'name',
        'username',
        'login',
        'unidade_fk',
        'guid',
        'domain',
        'auth_provider',
        'is_admin',
        'tutorial_exibido',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'tutorial_exibido' => 'boolean',
        ];
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_fk');
    }
}