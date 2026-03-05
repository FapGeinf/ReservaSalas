<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{

      public function getUsers()
      {
            return User::orderBy('name')->get();
      }

      public function createUser(array $data)
      {
            if (!empty($data['cpf'])) {
                  $existingUser = User::where('cpf', $data['cpf'])->first();
                  if ($existingUser) {
                        return [
                              'success' => false,
                              'error_type' => 'cpf_error',
                              'message' => 'O CPF informado já está cadastrado para o usuário: ' . $existingUser->name
                        ];
                  }
            }

            $user = User::create([
                  'name' => $data['name'],
                  'unidade_fk' => $data['unidade_fk'],
                  'login' => $data['login'],
                  'cpf' => $data['cpf'] ?? null,
                  'password' => Hash::make($data['password']),
                  'role' => $data['role'],
            ]);

            return [
                  'success' => true,
                  'user' => $user
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
                  'name' => $data['name'],
                  'unidade_fk' => $data['unidade_fk'],
                  'login' => $data['login'],
                  'role' => $data['role'],
                  'cpf' => $data['cpf'] ?? $user->cpf,
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
}