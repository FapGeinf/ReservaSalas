<link rel="stylesheet" href="{{ asset('css/nav-buttons.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg" style="background-color: #2d5857; box-shadow: 0 3px 3px rgba(0, 0, 0, 0.1);">
  <div class="container-fluid">

    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('/img/logo-letras-white-light.png') }}" alt="Logo" height="30">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">

      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item my-auto">
          <a class="nav-link nav-buttons" aria-current="page" href="{{ route('home') }}">
            <i class="bi bi-house me-1"></i>Início
          </a>
        </li>

        <li class="nav-item my-auto ms-2" style="padding-right: 6rem;">
          <a class="nav-link nav-buttons" href="{{ route('salas') }}">
            <i class="bi bi-door-open me-1"></i>Lista de Salas
          </a>
        </li>
      </ul>

      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <span class="navbar-text fw-bold" style="color: #f1f1f1; font-size: 14px;">
            <i class="fa-regular fa-user"></i>
            {{ Auth::user()->name }}
            
            <span class="mx-2">-</span>
            
            <i class="fa-regular fa-building"></i>
            {{ Auth::user()->unidade->nome }}
          </span>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-buttons" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Conta
          </a>

          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">Editar Perfil</a>
            </li>

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