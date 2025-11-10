<head>
  <style>
    /* Overlay escurecido */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
      z-index: 900;
      pointer-events: none;
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
    }

    /* Sidebar */
    .sidebar {
      width: 340px;
      background-color: #fff;
      height: 100vh;
      position: fixed;
      top: 0;
      left: -340px;
      display: flex;
      flex-direction: column;
      padding: 1.5rem;
      box-sizing: border-box;
      transition: left 0.3s ease;
      z-index: 950;
      border-right: 1px solid #dee2e6;
      overflow-y: auto;
    }

    .sidebar.open {
      left: 0;
    }

    /* Cabeçalho */
    .sidebar h6 {
      font-weight: 600;
      font-size: 1rem;
      color: #374151;
      margin-bottom: 0.75rem;
    }

    /* Descrição */
    .sidebar .desc {
      color: #6b7280;
      font-size: 13px;
      line-height: 1.4;
      margin-bottom: 1rem;
    }

    /* Seções de filtro */
    .filter-section {
      border-bottom: 1px solid #f0f0f0;
      padding-bottom: 1rem;
      margin-bottom: 1rem;
    }

    .filter-section label {
      display: block;
      font-weight: 500;
      color: #374151;
      font-size: 14px;
    }

    /* Lista de resultados */
    .reservas-container {
      flex-grow: 1;
      overflow-y: auto;
      max-height: 450px;
    }

    .reservas-container p {
      text-align: center;
      font-size: 13px;
      color: #6b7280;
    }

    /* Botão toggle */
    .toggle-btn {
      position: fixed;
      top: 57px;
      left: 15px;
      transition: all 0.3s ease;
      border: 1px solid #ccc;
      z-index: 960;
    }

    .toggle-btn.moved {
      left: 350px;
    }
  </style>
</head>

<!-- Botão -->
<button class="toggle-btn button-light-grey" id="toggleSidebar">☰</button>

<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar" style="padding-top: 4rem;">
  <h6>Filtros de Consulta</h6>
  <p class="desc">Selecione a sala e a data desejadas para visualizar as reservas existentes.</p>

  <div class="mb-3">
    <label for="salaSelecionada" style="
      font-weight: 500;
      color: #374151;
      font-size: 14px;">Sala:</label>
    <select id="salaSelecionada" class="input-custom form-select pointer">
      <option value="">Selecione uma sala</option>
    </select>
  </div>

  <div class="filter-section">
    <label for="dataSelecionada">Data:</label>
    <input type="date" id="dataSelecionada" class="input-custom">
  </div>

  <div class="reservas-container" id="reservasContainer">
    <p><i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...</p>
  </div>
</div>

<script>
  const btn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');

  function toggleSidebar() {
    const isOpen = sidebar.classList.toggle('open');
    btn.classList.toggle('moved', isOpen);
    btn.textContent = isOpen ? '×' : '☰';
    overlay.classList.toggle('active', isOpen);
    document.body.classList.toggle('sidebar-open', isOpen);
  }

  btn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', toggleSidebar);
</script>
