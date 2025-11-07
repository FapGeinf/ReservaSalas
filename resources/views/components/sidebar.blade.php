<style>
  body {
    transition: margin-left 0.3s ease;
    margin-left: 0;
  }

  body.shifted {
    margin-left: 450px; /* mesma largura da sidebar */
  }

  .sidebar {
    width: 400px;
    background-color: #fff;
    height: 100vh;
    position: fixed;
    top: 0;
    left: -400px;
    display: flex;
    flex-direction: column;
    padding: 1rem;
    box-sizing: border-box;
    transition: left 0.3s ease;
    z-index: 1000;
  }

  .sidebar.open {
    left: 0;
  }

  .sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 0.6rem 0;
    border-radius: 6px;
    transition: background 0.2s;
  }

  .toggle-btn {
    position: fixed;
    top: 57px;
    left: 15px;
    transition: all 0.3s ease;
  }

  .toggle-btn.moved {
    left: 415px; /* 450px da sidebar + 15px de espaçamento */
  }
</style>

<button class="toggle-btn button-light-grey" id="toggleSidebar">☰</button>

<div class="sidebar" id="sidebar">
  <div class="ver-reservas-container border rounded shadow-sm d-flex flex-column mt-5"
    style="background-color: #fff; padding: 1rem;" data-help="pesquisa-reservas">

    <h6 class="fw-bold text-center mb-3">Consulta de Reservas</h6>

    <span class="text-muted mb-4 text-center" style="font-size: 13px;">
      Escolha a <span class="fw-semibold" style="color: #374151;">sala</span> e a <span class="fw-semibold" style="color: #374151;">data</span> desejadas para visualizar as reservas já feitas.
    </span>

    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-7">
        <label for="salaSelecionada" class="fw-semibold">Sala:</label>
        <select id="salaSelecionada" class="input-custom form-select pointer w-100">
          <option value="">Selecione uma sala</option>
        </select>
      </div>

      <div class="col-12 col-sm-5">
        <label for="dataSelecionada" class="fw-semibold">Data:</label>
        <input type="date" id="dataSelecionada" class="input-custom w-100">
      </div>
    </div>

    <div id="reservasContainer" class="reservas-container flex-grow-1 overflow-auto"
      style="max-height: 450px;">
      <p class="text-center text-muted">
        <i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...
      </p>
    </div>
  </div>
</div>

<script>
  const btn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');

  btn.addEventListener('click', () => {
    const isOpen = sidebar.classList.toggle('open');
    document.body.classList.toggle('shifted', isOpen);
    btn.classList.toggle('moved', isOpen);
  });
</script>
