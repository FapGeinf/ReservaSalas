<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Unidade extends Model
{
        use HasFactory;

        protected $fillable =['nome', 'sigla'];

        public function User()
        {
            return $this->hasMany(User::class, 'user_id', 'id');
        }


}
