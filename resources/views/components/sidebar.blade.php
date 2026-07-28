@vite(['resources/js/flatpickr-data.js'])

<head>
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>

<!-- Botão para abrir a sidebar -->
<button class="toggle-btn button-light-grey" id="toggleSidebar">☰</button>

<!-- Botão de ajuda -->
<x-tutorial/>

<div id="overlay" class="overlay"></div>

<div class="sidebar" id="sidebar" style="padding-top: 4rem;">
  <h6>Filtros de Consulta</h6>
  <p class="desc">Selecione a sala e a data desejadas para visualizar as reservas existentes.</p>

  <div class="mb-3">
    <label for="salaSelecionada" style="font-weight: 500; color: #374151; font-size: 14px;">
      Sala:
    </label>
    <select id="salaSelecionada" class="input-custom form-select pointer">
      <option value="">Mostrar todas as salas</option>
    </select>
  </div>

  <div class="filter-section">
    <label for="dataSelecionada">Data:</label>
    <input 
      type="text" 
      id="dataSelecionada" 
      class="input-custom form-select"
      value="{{ $dataSelecionada ?? date('Y-m-d') }}"
    >
  </div>

  <div class="reservas-container" id="reservasContainer">
    <p><i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...</p>
  </div>
</div>

<script>
  const btn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const helpBtn = document.querySelector('.help-btn');

  function toggleSidebar() {
    const isOpen = sidebar.classList.toggle('open');
    btn.classList.toggle('moved', isOpen);
    helpBtn.classList.toggle('moved', isOpen);
    btn.textContent = isOpen ? '×' : '☰';
    overlay.classList.toggle('active', isOpen);
    document.body.classList.toggle('sidebar-open', isOpen);
  }

  btn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', toggleSidebar);
</script>