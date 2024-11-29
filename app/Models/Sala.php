<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;


class Sala extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao', 'situacao'];    
    
    public function setSituacaoAttribute($value) { 
       $this->attributes['situacao'] = ($value === 'ativa') ? 1 : 0; } // Accessor para converter booleano de volta para string 
       
       public function getSituacaoAttribute($value) { return $value ? 'ativa' : 'inativa'; } 

    public function Reserva()
    {
        return $this->hasMany(Reserva::class,'reserva_fk','id');
    }

}
