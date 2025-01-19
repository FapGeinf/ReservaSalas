<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendaí</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1>Agendaí</h1>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Salas</a></li>
                    <li><a href="#">Reservas</a></li>
                </ul>
            </nav>
            <div class="user-info">
                <span>Bem-vindo, Usuário</span>
            </div>
        </div>

    </header>
    <main class="main">
        <div class="cards-container">
            <div class="card">
                <img src="sala1.jpg" alt="Sala 1">
                <h2>Sala 1</h2>
            </div>
            <div class="card">
                <img src="sala2.jpg" alt="Sala 2">
                <h2>Sala 2</h2>
            </div>
            <div class="card">
                <img src="sala3.jpg" alt="Sala 3">
                <h2>Sala 3</h2>
            </div>
            <div class="card">
                <img src="sala4.jpg" alt="Sala 4">
                <h2>Sala 4</h2>
            </div>
        </div>
    </main>
    <footer class="footer">
        <p>© 2024 Agendaí. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

      </div>
    @endforeach

  <!-- Modal -->
<div class="modal fade" id="criarReservaModal" tabindex="-1" aria-labelledby="criarReservaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarReservaModalLabel">Criar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="sala_fk" id="sala_fk">

          <div class="mb-3">
            <label for="data_reserva" class="form-label">Data</label>
            <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Início</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_termino" class="form-label">Hora Término</label>
            <input type="time" name="hora_termino" id="hora_termino" class="form-control" required>
          </div>

      <!-- Campo oculto para unidade_fk -->
       <!-- Campo oculto para unidade_fk -->
@if (isset($user->unidade_id))
    <input type="hidden" name="unidade_fk" id="unidade_fk" value="{{ $user->unidade_id }}">
@else
    <p>Unidade do usuário não definida.</p>
@endif


        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
      </div>
    </div>
  </div>
</div>


  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <table class="table table-bordered align-middle mb-4 bg-white"> 
  <thead class="bg-light"> 
    <tr> 
      <th colspan="6" class="text-center fs-4">Reservas</th> <!-- Corrigi o colspan para 6 --> 
    </tr> 
     <tr class="text-center"> 
     <th>Sala</th>
     <th>Hora Início</th>
     <th>Hora Término</th> 
     <th>Reservado Por</th> 
     <th>Unidade</th> <!--coluna para mostrar a unidade --> 
     <th>Ações</th> </tr> </thead>

  <tbody>
    @foreach($reservas as $reserva)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/salas/' . $reserva->sala->imagem) }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                    <div class="ms-3">
                        <p class="fw-bold mb-1">{{ $reserva->sala->nome }}</p>
                    </div>
                </div>
            </td>
            <td>
                <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</p>
            </td>
            <td>
                <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i') }}</p>
            </td>
            <td>
                <p class="fw-normal mb-1">{{ $reserva->user ? $reserva->user->name : '' }}</p>
            </td>
            <td>
                <p class="fw-normal mb-1">{{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}</p>
            </td>
            <td>
                <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>





</table>
</div>

<script>
  function selecionarSala(salaId) {
    document.getElementById('sala_fk').value = salaId;
  }
</script>

@endsection

