<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Sidebar</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>

<body>
  <nav id="menu" class="menu">
    <div class="actionBar">
      <div>
        <button id="menuBtn">
          <i class="fa-solid fa-bars"></i>
        </button>

        <h3 class="menuText">Dashboard</h3>
      </div>
    </div>

    <ul class="optionsBar">
      <li class="menuItem">
        <a href="#" class="menuOption">
          <i class="fa-solid fa-house"></i><h5 class="menuText">Home</h5>
        </a>
      </li>

      <li class="menuBreak">
        <hr>
      </li>

      <li class="menuItem">
        <button id="productManagerBtn" class="menuOption">
          <i class="fa-solid fa-shopping-bag"></i><h5 class="menuText">Product Manager</h5>
        </button>
      </li>

      <li class="menuItem">
        <button id="constantManagerBtn" class="menuOption">
          <i class="fa-solid fa-border-all"></i><h5 class="menuText">Constant Manager</h5>
        </button>
      </li>

      <li class="menuItem">
        <button id="orderManagerBtn" class="menuOption">
          <i class="fa-solid fa-shopping-bag"></i><h5 class="menuText">Order Manager</h5>
        </button>
      </li>

      <li class="menuItem">
        <button id="tagManagerBtn" class="menuOption">
          <i class="fa-solid fa-tag"></i><h5 class="menuText">Tags Manager</h5>
        </button>
      </li>
    </ul>

    <div class="menuUser">
      <a href="#">
        <div>
          <img src="https://i.postimg.cc/5tPgPgyp/user.jpg" alt="image">
        </div>

        <h5 class="username menuText">Alex</h5>
        <p class="menuText"><i class="fa-solid fa-chevron-right"></i></p>
      </a>

      <div class="userInfo">
        <div>
          <h1><i class="fa-solid fa-exclamation-circle"></i></h1>
          <p>User Info</p>
        </div>
      </div>
    </div>

    <div class="themeBar">
      <div>
        <button id="themeChangeBtn"><i class="fa-solid "></i></button>
      </div>
    </div>
  </nav>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="main.js"></script>

  <script>
    const menuBtn = document.getElementById('menuBtn');
    const menu = document.getElementById('menu');
    const menuText = document.querySelectorAll('.menuText');

    menuBtn.addEventListener('click', () => {
      menu.classList.toggle('open');
      menuText.forEach(function(text, index) {
        setTimeout(() => {
          text.classList.toggle('open2');
        }, index * 50);
      })
    })

    $(document).on('click', function(e) {
      if($(e.target).closest('#menu').length === 0) {
        menu.classList.toggle('open');
        menuText.forEach(function(text, index) {
          setTimeout(() => {
            text.classList.toggle('open2');
          }, index * 50);
        })
      }
    })

    const dayNight = document.querySelector('#themeChangeBtn');
    dayNight.addEventListener('click', () => {
      document.body.classList.toggle('dark');
      if(document.body.classList.contains('dark')){
        localStorage.setItem('theme', 'dark');
      }else {
        localStorage.setItem('theme','light');
      }
      updateIcon();
    })

    function themeMode() {
      if(localStorage.getItem('theme') !== null){
        if(localStorage.getItem('theme') === 'light'){
          document.body.classList.remove('dark');
        }else {
          document.body.classList.add('dark');
        }
      }
      updateIcon();
    }
    themeMode();

    function updateIcon() {
      if(document.body.classList.contains('dark')){
        dayNight.querySelector('i').classList.remove('fa-moon');
        dayNight.querySelector('i').classList.add('fa-sun');
      } else {
        dayNight.querySelector('i').classList.remove('fa-sun');
        dayNight.querySelector('i').classList.add('fa-moon');
      }
    }
  </script>
</body>
</html>