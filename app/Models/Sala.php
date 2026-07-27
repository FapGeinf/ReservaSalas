<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;

class Sala extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'situacao', 'imagem', 'cor'];

    protected $table = 'salas';
    protected $primaryKey = 'id';

    // Accessor para formatar a situação
    // public function getSituacaoAttribute($value)
    // {
    //     return $value === 'ativa' ? 'Ativa' : 'Inativa';
    // }

    // Mutator para garantir que o valor seja sempre 'ativa' ou 'inativa'
    public function setSituacaoAttribute($value)
    {
        $this->attributes['situacao'] = ($value === 'ativa' || $value === true) ? 'ativa' : 'inativa';
    }

    // Relação com Reserva
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'sala_fk', 'id');
    }
}
