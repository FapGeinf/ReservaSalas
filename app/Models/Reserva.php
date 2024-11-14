<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
Use App\Models\Sala;
class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'data_inicio', 'data_fim', 'unidade_fk', 'sala_fk'];

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_fk','id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }


}

