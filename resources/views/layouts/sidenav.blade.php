<head>
  <link rel="stylesheet" href="{{ asset('css/sidenav.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>

  <nav id="menu" class="menu">
    <div class="actionBar">
      <div>
        <button id="menuBtn">
          <i class="fa-solid fa-bars"></i>
        </button>

        <h3 class="menuText">
          <img src="{{ asset('/img/logo-letras.png') }}" alt="image" style="width: 100%; height: auto; display: block; padding: 22px;">

        </h3>
      </div>
    </div>

    <ul class="optionsBar">
      <li class="menuItem">
        <a href="{{ route('home') }}" class="menuOption">
          <i class="fa-solid fa-house"></i>
          <h6 class="menuText fw-bold">Home</h6>
        </a>
      </li>
     
      <li class="menuItem">
        <a href="{{ route('salas') }}" class="menuOption">
          <i class="fa-solid fa-cube"></i>
          <h6 class="menuText fw-bold">Salas</h6>
        </a>
      </li>

      <!-- <li class="menuItem">
        <button class="menuOption">
          <i class="fa-solid fa-calendar-alt"></i>
          <h6 class="menuText fw-bold">Reservas</h6>
        </button>
      </li>

      <li class="menuItem">
        <button class="menuOption">
          <i class="fa-solid fa-history"></i>
          <h6 class="menuText fw-bold">Histórico</h6>
        </button>
      </li> -->

    </ul>

    <div class="menuUser">
      <a href="#">
        <div>
          <img src="https://i.postimg.cc/44L0DLbQ/file.jpg" alt="image">
        </div>

        <h5 class="username menuText">Leonardo</h5>
        <p class="menuText mb-0"><i class="fa-solid fa-chevron-right"></i></p>
      </a>

      <div class="userInfo">
        <div>
          <h1><i class="fa-solid fa-exclamation-circle"></i></h1>
          <p>User Info</p>
        </div>
      </div>
    </div>
  </nav>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="main.js"></script>

  <script>
    const menuBtn = document.getElementById('menuBtn');
    const menu = document.getElementById('menu');
    const menuText = document.querySelectorAll('.menuText');

    // Função para abrir/fechar o menu
    menuBtn.addEventListener('click', () => {
      menu.classList.toggle('open');
      menuText.forEach((text, index) => {
        setTimeout(() => {
          text.classList.toggle('open2');
        }, index * 50);
      });
    });

    // Remove o comportamento de clique fora
    $(document).on('click', (e) => {
      if (!$(e.target).closest('#menuBtn').length && !menu.classList.contains('open')) {
        e.stopPropagation();
      }
    });
  </script>