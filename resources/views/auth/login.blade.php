<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center right-side form-box">
        <div class="form-2-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3 form-box">
              <label for="email">Email:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-envelope-at"></i>
                </span>

                <input type="email" id="email" name="email" class="form-control" :value="old('email')" placeholder="meuemail@email.com" autofocus required autocomplete="username">
              </div>
              
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div class="mb-3">
              <label for="password">Senha:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-shield-lock"></i>
                </span>

                <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo de 8 caracteres" required autocomplete="current-password">
              </div>
              
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Lembrar de Mim -->
            <div class="block mt-0">
              <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded" name="remember">
                <span class="ml-1 text-sm">Lembrar de mim</span>
              </label>
            </div>

            <button type="submit" class="login-btn w-50 mt-4 mb-3">Entrar</button>
          </form>

          <!-- Registrar-se -->
          <div class="text-start register-link mt-4">
            <a href="{{ route('password.request') }}" class="">Esqueceu a senha?</a>
            <p class="mt-1">Primeira vez usando o Agendaí? <a href="{{ route('register') }}" class="">Cadastre-se</a></p>
          </div>

        </div>
      </div>
    </div>
  </div>
</body>

</html>