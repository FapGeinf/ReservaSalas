<head>
  <link rel="stylesheet" href="{{ asset('css/nav-buttons.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

  <style>
    body {
      padding-top: 40px;
    }

    .navbar {
      overflow: visible !important;
    }

    .container-fluid {
      overflow: visible !important;
    }

    .navbar .dropdown-menu {
      z-index: 1055;
    }
  </style>
</head>

<nav class="navbar navbar-expand-lg fixed-top py-0" style="background-color: #314b27;">
  <div class="container-fluid">

    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('/img/logo-letras-white-light.png') }}" alt="Logo" height="30">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-2">
        <li class="nav-item">
          <a class="nav-link nav-buttons fs-12" href="{{ route('home') }}">
            <i class="bi bi-house me-1"></i>
            Início
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-buttons fs-12" href="{{ route('reservas.index') }}">
            <i class="bi bi-calendar-check fs-12 me-1"></i>
            Lista de Reuniões
          </a>
        </li>
        <!-- Para referencias a admin, utilizar o campo is_admin -->
        @if(Auth::check() && Auth::user()->is_admin == 1)
          <li class="nav-item">
            <a class="nav-link nav-buttons fs-12" href="{{ route('salas') }}">
              <i class="bi bi-door-open me-1"></i>
              Salas
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-buttons fs-12" href="{{ route('usuarios.index') }}">
              <i class="bi bi-people me-1"></i>
              Usuários
            </a>
          </li>
        @endif
      </ul>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-buttons fs-12" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{-- <i class="bi bi-gear me-1"></i> --}}
            Conta
          </a>

          <ul class="dropdown-menu dropdown-menu-start-sm">
            <li class="dropdown-item d-flex align-items-center li-person">
              <i class="bi bi-person-circle"
                style="
                font-size: 2rem;
                margin-right: 10px; 
                color: #394151";>
              </i>

              <div class="text-capitalized">
                <strong style="color: #394151; word-break: break-word">{{ Auth::user()->name }}</strong>
                <br>
                <small class="text-muted">
                  <i class="bi bi-building"></i>
                  {{ Auth::user()->unidade ? explode(' ', Auth::user()->unidade->nome)[0] : 'Unidade não encontrada' }}
                </small>
              </div>
            </li>

            <li><hr class="dropdown-divider"></li>
            
            <li class="fs-13">
              <a class="dropdown-item nav-buttons-dp" href="{{ route('profile.edit') }}">
                Editar Perfil
              </a>
            </li>

            <li>
              <form method="POST" action="{{ route('logout') }}" style="margin-bottom: 0;">
                @csrf
                <button class="dropdown-item fs-13" type="submit">Sair</button>
              </form>
            </li>
          </ul>

        </li>
      </ul>

    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
