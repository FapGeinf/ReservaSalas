<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class NivelAcesso extends Model
{
    use HasFactory;

    protected $table = 'niveis_acessos';

    protected $fillable = [
        'tipo',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'nivel_acesso_id');
    }
}
