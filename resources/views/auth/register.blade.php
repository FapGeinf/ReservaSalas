<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center right-side form-box">
        <div class="form-3-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-3 form-box">
              <label for="name">Nome completo:</label>
              <input type="text" id="name" name="name" class="form-control" :value="old('name')" required autofocus placeholder="Julliany Souza" autocomplete="name">

              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <!-- Email -->
            <div class="mb-3 form-box">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" class="form-control" :value="old('email')" required placeholder="meuemail@email.com" autocomplete="username">

              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- CPF -->
            <div class="mb-3 form-box">
              <label for="cpf">CPF:</label>
              <input type="text" id="cpf" name="cpf" class="form-control" required placeholder="000.000.000-00">
            </div>

            <!-- Senha -->
            <div class="mb-3 form-box">
              <label for="password">Senha:</label>
              <input type="password" id="password" name="password" class="form-control" required placeholder="Mínimo de 8 caracteres" autocomplete="new-password">

              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-3 form-box">
              <label for="password_confirmation">Repita a senha:</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Repita a senha" autocomplete="new-password">

              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="login-btn w-50 mt-4 mb-3">Registrar</button>

          </form>

          <div class="text-start d-flex register-link mt-3">
            <p style="margin-right: 5px;">Já possui cadastro?</p>
            <a href="{{ route('login') }}">Fazer login</a>
          </div>

        </div>
      </div>
    </div>

  </div>
</body>
</html>