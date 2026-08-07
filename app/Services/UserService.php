<?php

namespace App\Services;

use App\Models\User;
use App\Models\NivelAcesso;
use Illuminate\Support\Facades\Hash;

class UserService
{
      public function getUsers()
      {
            return User::with(['nivelAcesso', 'unidade'])
                  ->orderBy('name')
                  ->get();
      }

      public function getNiveisAcesso()
      {
            return NivelAcesso::orderBy('id')->get();
      }
      public function createUser(array $data)
      {
            if (!empty($data['cpf'])) {
                  $existingUser = User::where('cpf', $data['cpf'])->first();
                  if ($existingUser) {
                  return [
                        'success'    => false,
                        'error_type' => 'cpf_error',
                        'message'    => 'O CPF informado já está cadastrado para o usuário: ' . $existingUser->name
                  ];
                  }
            }

            $user = User::create([
                  'name'            => $data['name'],
                  'username'        => $data['username'] ?? null,
                  'unidade_fk'      => $data['unidade_fk'],
                  'login'           => $data['login'],
                  'cpf'             => $data['cpf'] ?? null,
                  'password'        => Hash::make($data['password']),
                  'nivel_acesso_id' => $data['nivel_acesso_id'] ?? 1,
            ]);

            return [
                  'success' => true,
                  'user'    => $user
            ];
      }

      public function updateUser(int $id, array $data)
      {
            $user = User::findOrFail($id);

            if (!empty($data['cpf'])) {
                  $existingUser = User::where('cpf', $data['cpf'])
                        ->where('id', '!=', $id)
                        ->first();
                  if ($existingUser) {
                        return [
                              'success' => false,
                              'error_type' => 'cpf_error',
                              'message' => 'Este CPF já pertence ao usuário: ' . $existingUser->name
                        ];
                  }
            }

            $updateData = [
                  'name'       => $data['name'],
                  'username'   => $data['username'] ?? $user->username,
                  'unidade_fk' => $data['unidade_fk'],
                  'login'      => $data['login'],
                  'cpf'        => $data['cpf'] ?? $user->cpf,
                  'nivel_acesso_id' => $data['nivel_acesso_id'] ?? $user->nivel_acesso_id,
            ];

            if (!empty($data['password'])) {
                  $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            return ['success' => true, 'user' => $user];
      }

      public function updateTutorialStatus(User $user)
      {
            $user->tutorial_exibido = true;
            return $user->save();
      }

      public function getUserById(int $id) : User 
      {
             return User::findOrFail($id);
      }

      public function deleteUser(int $id) : bool 
      {
             $user = $this->getUserById($id);
             return $user->delete();
      }
}