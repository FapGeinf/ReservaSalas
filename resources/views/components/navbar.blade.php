<style>
  li a:hover {
    text-decoration: underline;
  }
</style>

<div>
  <nav class="navbar navbar-expand-lg" style="background: #245fa3; box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="container-fluid d-flex align-items-center">
      <img class="navbar-brand text-light" src="{{ asset('/img/logo-letras-white.png') }}" style="width: 6%;" alt="Logo Agendaí">

      @auth

  <div class="ms-3  text-light" style="font-size: 0.875rem;">
    Seja bem-vindo(a): {{ Auth::user()->name }}
  </div>

  <div class="ms-3 text-light" style="font-size: 0.875rem;">
    Setor:  @if(Auth::user()->unidade)
      {{ Auth::user()->unidade->nome }}
    @endif
  </div>
@endauth

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav w-100 justify-content-center me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fw-bold text-light" href="{{ route('home') }}">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-bold text-light" href="{{ route('salas') }}">Salas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-bold text-light" href="{{ route('reservas.index') }}">Reservas</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-flex align-items-center">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Conta
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">Editar Perfil</a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form class="d-flex" method="POST" action="{{ route('logout') }}">
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
</div>
