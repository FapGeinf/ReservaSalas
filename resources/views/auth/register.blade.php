<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <title>Document</title>
</head>
<body>
  <div class="container">
    <div class="row">

      <!-- Lado Esquerdo -->
      <div class="col-lg-6"></div>

      <!-- Lado Direito -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center right-side form-box">
        <div class="form-4-wrapper">

          <div class="logo text-center mb-4">
            <img src="{{ asset('/img/logo-alone.png') }}" alt="Logo Agendaí">
          </div>

          <!-- Botão para abrir o modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
            Registrar Novo Usuário
          </button>

          <!-- Modal -->
          <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="registerModalLabel">Registrar Novo Usuário</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nome -->
                    <div class="mb-3 form-box">
                      <label for="name">Nome completo:</label>

                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-person"></i>
                        </span>

                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="ex: Julliany Souza" autocomplete="name">
                      </div>

                      <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mb-3 form-box">
                      <label for="email">Email:</label>
                      
                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-envelope-at"></i>
                        </span>

                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="ex: meuemail@email.com" autocomplete="username">
                      </div>
                      
                      <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- CPF -->
                    <div class="mb-3 form-box">
                      <label for="cpf">CPF:</label>

                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-person-vcard"></i>
                        </span>

                        <input type="text" id="cpf" name="cpf" class="form-control" required placeholder="ex: 000.000.000-00">
                      </div>
                    </div>

                    <!-- Unidade -->
                    <div class="mb-3 form-box">
                      <label for="unidade_fk">Unidade:</label>

                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-building"></i>
                        </span>
                        
                        <select name="unidade_fk" id="unidade_fk" class="form-control" required>
                            <option value="">Selecione a unidade </option>
                          @foreach($unidades as $unidade)
                          <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <!-- Senha -->
                    <div class="mb-3 form-box">
                      <label for="password">Senha:</label>

                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-shield-lock"></i>
                        </span>

                        <input type="password" id="password" name="password" class="form-control" required placeholder="Mínimo de 8 caracteres" autocomplete="new-password">
                      </div>
                      
                      <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="mb-3 form-box">
                      <label for="password_confirmation">Repita a senha:</label>

                      <div class="input-group">
                        <span class="input-group-text">
                          <i class="bi bi-shield-lock"></i>
                        </span>

                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Repita a senha" autocomplete="new-password">
                      </div>

                      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="text-center">
                      <button type="submit" class="button-blue">Registrar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="text-start d-flex register-link mt-4">
            <p style="margin-right: 5px;">Já possui cadastro?</p>
            <a href="{{ route('login') }}">Fazer login</a>
          </div>

        </div>
      </div>
    </div>

    <div class="row mt-5">
  <div class="col-12">
    <h3 class="text-center fw-bold mb-4">Usuários Cadastrados</h3>
    <div class="table-responsive">
      <table class="table custom-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>CPF</th>
            <th>Unidade</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($usuarios as $usuario)
            <tr>
              <td>{{ $usuario->name }}</td>
              <td>{{ $usuario->email }}</td>
              <td>{{ $usuario->cpf }}</td>
              <td>{{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}</td>
              <td>
                <!-- <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-action btn-sm btn-primary">
                  <i class="bi bi-pencil"></i>
                </a> -->
                <button onclick="confirmarExclusao({{ $usuario->id }})" class="btn btn-action btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
      fetch(`/usuarios/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
      })
      .then(response => {
        if (response.ok) {
          window.location.reload();
        } else {
          alert('Erro ao excluir o usuário.');
        }
      });
    }
  }
</script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

