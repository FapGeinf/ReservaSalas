<x-app-layout>
</x-app-layout> 

<div class="container">
  <h2>Editar Perfil</h2>
  <div class="row">

    <!-- Lado Esquerdo -->
    <div class="col-lg-6"></div>

    <!-- Lado Direito -->
    <div class="col-lg-6 d-flex align-items-center justify-content-center right-side form-box">
      <div class="form-3-wrapper">

        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PATCH')

          <!-- Nome -->
          <div class="mb-3 form-box">
            <label for="name">Nome completo:</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-person"></i>
              </span>
              <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus placeholder="ex: Julliany Souza" autocomplete="name">
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
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required placeholder="ex: meuemail@email.com" autocomplete="username">
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
              <input type="text" id="cpf" name="cpf" class="form-control" value="{{ old('cpf', auth()->user()->cpf) }}" required placeholder="ex: 000.000.000-00">
            </div>
          </div>

          <!-- Unidade -->
          <div class="mb-3">
            <label for="unidade_fk">Unidade:</label>
            <select name="unidade_fk" id="unidade_fk" class="form-select" required>
              <option value="">Selecione a unidade</option>
              @foreach($unidades as $unidade)
                <option value="{{ $unidade->id }}" {{ auth()->user()->unidade_fk == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
              @endforeach
            </select>
          </div>

          <!-- Senha -->
          <div class="mb-3 form-box">
            <label for="password">Senha:</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-shield-lock"></i>
              </span>
              <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo de 8 caracteres" autocomplete="new-password">
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
              <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repita a senha" autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
          </div>

          <button type="submit" class="login-btn w-50 mt-4 mb-3">Atualizar Perfil</button>

        </form>

        <div class="text-start d-flex register-link mt-3">
          <p style="margin-right: 5px;">Deseja sair?</p>
          <a href="{{ route('logout') }}">Sair</a>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+3i5Qm0I1RVuA+PmSTsz/K68vbdEj" crossorigin="anonymous"></script>
</body>
</html>
