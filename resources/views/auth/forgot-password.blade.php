<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
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
        <div class="form-4-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <x-auth-session-status class="mb-4" :status="session('status')" />

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-box">
              <label for="">Endereço de email:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-envelope-at"></i>
                </span>

                <input type="text" id="email" name="email" class="form-control" :value="old('email')" required placeholder="ex: meuemail@email.com" autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
              </div>
              
              <button type="submit" class="login-btn mt-4">Enviar link para redefinição de senha</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
