<?php

namespace App\Services;
use App\Models\User;

class UserService{

      public function getUsers()
      {
             return User::with('unidades')->orderBy('name')->get();
      }
}