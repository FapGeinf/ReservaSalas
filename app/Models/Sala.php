<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reserva;


class Sala extends Model
{
    use HasFactory;
    protected $fillable =['nome', 'descricao'];    


    public function Reserva()
    {
        return $this->hasMany(Reserva::class,'reserva_fk','id');
    }

}
