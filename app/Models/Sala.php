<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use App\Models\Reserva;


// class Sala extends Model
// {
//     use HasFactory;
//     protected $fillable = [ 'nome', 'descricao', 'situacao', 'imagem'];    
//     protected $table = 'salas'; // Nome correto da tabela
//     protected $primaryKey = 'id'; // Certifique-se de que a chave primária está definida corretamente
    
//     // public function setSituacaoAttribute($value) { 
//     //    $this->attributes['situacao'] = ($value === 'ativa') ? 'ativa' : 'inativa';  // Accessor para converter booleano de volta para string 
//     // }
//        // Na model Sala
// public function getSituacaoAttribute($value)
// {
//     return $value === 'ativa' ? 'Ativa' : 'Inativa';
// }
//     public function Reserva()
//     {
//         return $this->hasMany(Reserva::class,'reserva_fk','id');
//     }

// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;

class Sala extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'situacao', // Certifique-se de que este campo existe no banco de dados
        'imagem'
    ];

    protected $table = 'salas'; // Nome correto da tabela
    protected $primaryKey = 'id'; // Chave primária correta

    // Accessor para formatar a situação
    public function getSituacaoAttribute($value)
    {
        return $value === 'ativa' ? 'Ativa' : 'Inativa';
    }

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