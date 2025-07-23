<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/nav-buttons.css') }}">
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

<nav class="navbar navbar-expand-lg fixed-top" style="background-color: #2d5857; box-shadow: 0 3px 3px rgba(0, 0, 0, 0.1);">
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
          <a class="nav-link nav-buttons" href="{{ route('home') }}">
            <i class="bi bi-house me-1"></i>Início
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-buttons" href="{{ route('reservas.index') }}">
            <i class="bi bi-calendar-check me-1"></i>Lista de Reuniões
          </a>
        </li>

        @if(Auth::check() && Auth::user()->role === 'admin')
        <li class="nav-item">
          <a class="nav-link nav-buttons" href="{{ route('salas') }}">
            <i class="bi bi-door-open me-1"></i>Lista de Salas
          </a>
        </li>
        @endif
      </ul>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-buttons" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Conta
          </a>
          <ul class="dropdown-menu dropdown-meu-start-sm">
            <li class="dropdown-item d-flex align-items-center li-person">
              <i class="bi bi-person-circle" style="font-size: 3rem; margin-right: 10px; color: #394151"></i>
              <div class="text-uppercase">
                <strong style="color: #394151;">{{ Auth::user()->name }}</strong>
                <br>
                <small class="text-muted" style="white-space: normal;">
                  <i class="bi bi-building me-1"></i>
                  {{ Auth::user()->unidade ? Auth::user()->unidade->nome : 'Unidade não encontrada' }}
                </small>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item nav-buttons-dp" href="{{ route('profile.edit') }}">Editar Perfil</a></li>
            @if(Auth::check() && Auth::user()->role === 'admin')
            <li><a class="dropdown-item nav-buttons-dp" href="{{ route('usuarios.index') }}">Usuários</a></li>
            @endif
            <li>
              <form method="POST" action="{{ route('logout') }}" style="margin-bottom: 0;">
                @csrf
                <button class="dropdown-item" type="submit">Sair</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
