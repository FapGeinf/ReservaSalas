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
  <title>Agendaí! | Registre-se</title>
</head>
<body>
  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6">
        <div class="form-custom no-border-bottom form-no-bottom-register mt-5">
          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-letras.png') }}" alt="Logo Agendaí">
          </div>

          <form id="form-register" method="POST" action="{{ route('register') }}">
            @csrf

            @if ($errors->has('register'))
              <div class="alert alert-danger d-flex align-items-center shadow-sm rounded p-1" role="alert">
                <i class="bi bi-exclamation-circle-fill me-1"></i>
                <span>{{ $errors->first('register') }}</span>
              </div>
            @endif

            <div class="mt-3">
              <label for="name" class="fw-bold">Nome Completo:</label>
              <input type="text" id="name" name="name" class="input-custom" :value="old('name')" required autofocus autocomplete="name">

              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-3">
              <label for="login" class="fw-bold">Login:</label>
              <input type="text" id="login" name="login" class="input-custom" value="{{ old('login') }}" required placeholder="ex: julliany.souza" autocomplete="username">

              <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

            <div class="mt-3">
              <label for="email" class="fw-bold">Email:</label>
              <input type="email" id="email" name="email" class="input-custom" :value="old('email')" required placeholder="meuemail@email.com" autocomplete="username">
            
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
           
            <div class="mt-3">
              <label for="cpf" class="fw-bold">CPF:</label>
              <input type="text" id="cpf" name="cpf" class="input-custom" required placeholder="000.000.000-00">
            </div>

            <div class="mt-3">
              <label for="unidade_fk" class="fw-bold">Unidade:</label>
              <select name="unidade_fk" id="unidade_fk" class="input-custom form-select pointer" required>
                <option value="">Selecione a unidade </option>
                @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                @endforeach
              </select>
            </div>

            <div class="mt-3">
              <div class="form-line gap-2">
                <div class="form-line-vertical">
                  <label for="password" class="fw-bold mb-0">Nova Senha:</label>
                  <input type="password" id="password" name="password" class="input-custom" required placeholder="Mínimo de 8 caracteres" autocomplete="new-password">

                  <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="form-line-vertical">
                  <label for="password_confirmation" class="fw-bold mb-0">Repita a senha:</label>
                  <input type="password" id="password_confirmation" name="password_confirmation" class="input-custom" required placeholder="Repita a senha" autocomplete="new-password">

                  <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="form-custom no-border-top form-no-top-register pt-3">
          <div class="d-flex justify-content-end gap-2 pb-3">
            <a href="{{ route('login') }}" class="button-grey text-decoration-none">Já possuo acesso</a>
            <button type="submit" form="form-register" class="button-green">Registrar</button>
          </div>
        </div>

      </div>
    </div>
  </div>


      {{-- <div class="col-lg-6 d-flex align-items-center justify-content-center right-side form-box">
        <div class="form-4-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
              <label for="name">Nome completo:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-person"></i>
                </span>

                <input type="text" id="name" name="name" class="form-control" :value="old('name')" required autofocus placeholder="ex: Julliany Souza" autocomplete="name">
              </div>

              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

          
            <div class="mb-3 form-box">
              <label for="login">Login:</label>

              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-person-circle"></i>
                </span>

                <input type="text" id="login" name="login" class="form-control" :value="old('login')" required placeholder="ex: julliany.souza" autocomplete="username">
              </div>

              <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

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
                
                <select name="unidade_fk" id="unidade_fk" class="form-control" required>
                    <option value="">Selecione a unidade </option>
                  @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                  @endforeach
                </select>
              </div>
            </div>

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

            <div class="text-center">
              <button type="submit" class="button-blue">Registrar</button>
            </div>
            

          </form>

          <div class="text-start d-flex register-link mt-4">
            <p style="margin-right: 5px;">Já possui cadastro?</p>
            <a href="{{ route('login') }}">Fazer login</a>
          </div>

        </div>
      </div> --}}
    </div>

  </div>
</body>
</html>
