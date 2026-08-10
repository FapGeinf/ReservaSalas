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
        'nivel_acesso_id',
        'guid',
        'domain',
        'auth_provider',
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
            'tutorial_exibido' => 'boolean',
            'nivel_acesso_id' => 'integer'
        ];
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_fk');
    }
    public function nivelAcesso()
    {
        return $this->belongsTo(NivelAcesso::class, 'nivel_acesso_id');
    }

    public function isUser(): bool
    {
        return $this->nivel_acesso_id === 1;
    }

    public function isAdmin(): bool
    {
        return $this->nivel_acesso_id >= 2; 
    }

    public function isRoot(): bool
    {
        return $this->nivel_acesso_id === 3; 
    }
}