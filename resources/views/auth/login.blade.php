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

          <!-- Registro -->
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

{{-- <x-guest-layout>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div>
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <!-- Password -->
      <div class="mt-4">
          <x-input-label for="password" :value="__('Password')" />

          <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

          <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
          <label for="remember_me" class="inline-flex items-center">
              <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
              <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
          </label>
      </div>

      <div class="flex items-center justify-end mt-4">
          @if (Route::has('password.request'))
              <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                  {{ __('Forgot your password?') }}
              </a>
          @endif

          <x-primary-button class="ms-3">
              {{ __('Log in') }}
          </x-primary-button>
      </div>
  </form>
</x-guest-layout> --}}
