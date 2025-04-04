<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

  <title>Agendaí! | Seja bem-vindo</title>
   
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
            <label for="login">Login:</label>
            
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="login" name="login" placeholder="Login" required>
            </div>
          </div>

            <label for="password">Senha:</label>
            <div class="input-group mb-3">
              <span class="input-group-text">@</span>
              <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo de 8 caracteres" required autocomplete="current-password">

              <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Lembrar de Mim -->
            <div class="block mt-0">
              <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded" name="remember">
                <span class="ml-1 text-sm">Lembrar de mim</span>
              </label>
            </div>

            <div class="d-flex justify-content-center mt-3">
              <button type="submit" class="button-blue">Entrar</button>
            </div>
          </form>

          <p class="mt-1">
           <a href="http://10.10.3.252/glpi/front/ticket.form.php" class="" target="_blank">Esqueceu a senha?</a>
          </p>
       </div>


          <!-- <div class="text-start register-link mt-4">
            <a href="{{ route('password.request') }}" class="">Esqueceu a senha?</a>
            <p class="mt-1">Primeira vez usando o Agendaí? <a href="{{ route('register') }}" class="">Cadastre-se</a></p>
          </div> -->
          
        </div>
      </div>
    </div>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
  </div>
</body>

</html>