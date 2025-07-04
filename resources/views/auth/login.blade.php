<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
  <link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">

  <title>Agendaí! | Seja bem-vindo</title>

</head>

<body>
  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6">
        <div class="form-custom no-border-bottom form-no-bottom mt-5" style="max-width: 410px;">
          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-letras.png') }}" alt="Logo Agendaí">
          </div>

          <form id="form-login" method="POST" action="{{ route('login') }}">
            @csrf

            @if ($errors->has('login'))
              <div class="alert alert-danger d-flex align-items-center shadow-sm rounded p-1" role="alert">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                <span>{{ $errors->first('login') }}</span>
              </div>
            @endif

            <div>
              <label class="fw-bold" for="login">Login:</label>
              <input type="text" class="input-custom" id="login" name="login" required>
            </div>

            <div class="mt-3">
              <label for="password" class="fw-bold">Senha:</label>
              <input type="password" id="password" name="password" class="input-custom"
                placeholder="Mínimo de 8 caracteres" required autocomplete="current-password">

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

        <div class="form-custom no-border-top form-no-top pt-3" style="max-width: 410px;">
          <div class="d-flex justify-content-end gap-2 pb-3">
            <a href="http://10.10.3.252/glpi/front/ticket.form.php" class="button-grey text-decoration-none" target="_blank">Esqueci minha
              senha!</a>
            <button type="submit" form="form-login" class="button-green">Entrar</button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
  function togglePassword(inputId, iconId) {
    let passwordField = document.getElementById(inputId);
    let eyeIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
      passwordField.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");

    } else {
      passwordField.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }
</script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      setTimeout(function () {
        let alertElement = document.querySelector(".alert-danger");
        if (alertElement) {
          alertElement.style.transition = "opacity 0.5s ease-out";
          alertElement.style.opacity = "0";
          setTimeout(() => alertElement.remove(), 500);
        }
      }, 4000);
    });

    // document.getElementById("togglePassword").addEventListener("click", function () {
    //   let passwordField = document.getElementById("password");
    //   let icon = this;

    //   if (passwordField.type === "password") {
    //     passwordField.type = "text";
    //     icon.classList.remove("bi-eye-slash");
    //     icon.classList.add("bi-eye");

    //   } else {
    //     passwordField.type = "password";
    //     icon.classList.remove("bi-eye");
    //     icon.classList.add("bi-eye-slash");
    //   }
    // });

    // document.addEventListener("DOMContentLoaded", function () {
    //   let form = document.getElementById("loginForm");

    //   form.addEventListener("keypress", function (event) {
    //     if (event.key === "Enter") {
    //       event.preventDefault();
    //       form.submit();
    //     }
    //   });
    // });
  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>
</html>