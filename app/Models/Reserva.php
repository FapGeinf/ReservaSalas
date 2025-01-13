<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
Use App\Models\Sala;
class Reserva extends Model
{
    use HasFactory;
     
    
    protected $fillable = [
        'data_inicio',
        'data_fim',
        'sala_fk',
        'user_id',
        'unidade_fk',
         ];

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_fk','id', );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_fk','id');
    }

}

