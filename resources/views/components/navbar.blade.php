<link rel="stylesheet" href="{{ asset('css/nav-buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg fixed-top py-0" style="background-color: #314b27;">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('/img/logo-letras-white-light.png') }}" alt="Logo" height="30">
    </a>

    <button class="navbar-toggler" type="button" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
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
          <a class="nav-link nav-buttons fs-12" href="{{ route('reservas.lista-reunioes') }}">
            <i class="bi bi-calendar-check fs-12 me-1"></i>
            Lista de Reuniões
          </a>
        </li>

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

          <!-- Novo Link de Logs (Visível apenas para administradores) -->
          <li class="nav-item">
            <a class="nav-link nav-buttons fs-12" href="{{ url('/log-viewer') }}" target="_blank">
              <i class="bi bi-journal-text me-1"></i>
              Logs do Sistema
            </a>
          </li>
        @endif
      </ul>

      @php
        use Illuminate\Support\Str;

        $nomeFormatado = collect(explode(' ', Auth::user()->name))
          ->map(function ($parte) {
            return in_array(Str::lower($parte), ['da', 'do', 'de'])
            ? Str::lower($parte)
            : $parte;
          })
        ->implode(' ');
      @endphp

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item d-flex align-items-center nav-account">
          <span class="nav-user-name">
            {{ $nomeFormatado }}
            •
            {{ Auth::user()->unidade ? explode(' ', Auth::user()->unidade->nome)[0] : 'Unidade não encontrada' }}
          </span>

          <span class="nav-separator">|</span>

          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="button-garden-nav" style="padding: 4px 9px;">
              <i class="bi bi-box-arrow-right me-1"></i>
              Sair
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const navbar = document.getElementById('navbarContent');
  const toggler = document.querySelector('.navbar-toggler');

  const collapse = bootstrap.Collapse.getOrCreateInstance(navbar, { toggle: false });

  toggler.addEventListener('click', () => collapse.toggle());

  // fecha ao clicar em links/botões do menu (opcional)
  document.querySelectorAll('#navbarContent .nav-link, #navbarContent button').forEach(el => {
    el.addEventListener('click', () => collapse.hide());
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>