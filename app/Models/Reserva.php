<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['sala_id', 'usuario_id', 'data', 'hora', 'status'];

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
