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
        <div class="form-3-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-3 form-box">
              <label for="name">Nome completo:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-person"></i>
                </span>

                <input type="text" id="name" name="name" class="form-control" :value="old('name')" required autofocus placeholder="ex: Julliany Souza" autocomplete="name">
              </div>

              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mb-3 form-box">
              <label for="email">Email:</label>
              
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-envelope-at"></i>
                </span>

                <input type="email" id="email" name="email" class="form-control" :value="old('email')" required placeholder="ex: meuemail@email.com" autocomplete="username">
              </div>
              
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- CPF -->
            <div class="mb-3 form-box">
              <label for="cpf">CPF:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-person-vcard"></i>
                </span>

                <input type="text" id="cpf" name="cpf" class="form-control" required placeholder="ex: 000.000.000-00">
              </div>
            </div>


            <div class="mb-3 form-box">
              <label for="unidade_fk">Unidade:</label>

           <div class="input-group">
            <span class="input-group-text">
         <i class="bi bi-building"></i>
        </span>
            <select name="unidade_fk" id="unidade_fk" class="form-select" required>
             <option value="">Selecione a unidade</option>
               @foreach($unidades as $unidade)
               <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
               @endforeach
            </select>
           </div>
         </div>

            <!-- Campo para setor -->
            {{-- <div class="mb-3 form-box">
              <label for="setor" class="form-label">Setor</label>
              <input type="text" name="setor" id="setor" class="form-control" required>
            </div> --}}

            <!-- Senha -->
            <div class="mb-3 form-box">
              <label for="password">Senha:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-shield-lock"></i>
                </span>

                <input type="password" id="password" name="password" class="form-control" required placeholder="Mínimo de 8 caracteres" autocomplete="new-password">
              </div>
              
              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-3 form-box">
              <label for="password_confirmation">Repita a senha:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-shield-lock"></i>
                </span>

                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Repita a senha" autocomplete="new-password">
              </div>

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
