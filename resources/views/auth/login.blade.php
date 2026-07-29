<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
  <link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <title>Agendaí! | Bem-vindo</title>
</head>

<body>
  <x-alert-toast/>

  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6">
        <div class="form-custom no-border-bottom form-no-bottom-login mt-5">
          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-letras.png') }}" alt="Logo Agendaí">
          </div>

          <form id="form-login" method="POST" action="{{ route('login') }}">
            @csrf

            <div>
              <label class="fw-medium" for="login">Login:</label>
              <input type="text" class="input-custom" id="login" name="login" required>
            </div>

            <div class="mt-3 position-relative">
              <label for="password" class="fw-medium">Senha:</label>

              <input type="password" id="password" name="password" class="input-custom" placeholder="Mínimo de 8 caracteres" required autocomplete="current-password">

              <i id="togglePassword" class="bi bi-eye-slash" 
              style="
                position: absolute;
                top: 33px;
                right: 10px;
                cursor: pointer;
                color: #374151;">
              </i>
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

          </form>

          <div class="block mt-3">
            <label for="remember_me" class="inline-flex items-center">
              <input id="remember_me" type="checkbox" class="rounded" name="remember">
              <span class="ml-1 text-sm">Lembrar de mim</span>
            </label>
          </div>
  
        </div>

        <div class="form-custom no-border-top form-no-top-login pt-3">
          <div class="d-flex justify-content-end gap-2 pb-3">
            <a href="http://10.10.3.252/glpi/front/ticket.form.php" class="button-grey text-decoration-none" target="_blank">
              <i class="bi bi-question-circle me-1"></i>
              Esqueci minha senha
            </a>

            <button type="submit" form="form-login" class="button-green">
              <i class="bi bi-box-arrow-in-right me-1"></i>
              Entrar
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>
</html>