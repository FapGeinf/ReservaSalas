<style>
  .toast-style {
    color: #fff;
    border-radius: 0.375rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    padding: 0 1rem;
    opacity: 0;
    transform: translateY(-20px);
    animation: fadeInDown 0.5s forwards, fadeOutUp 0.5s 4.5s forwards;
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .toast-style-x {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    margin: 0;
    align-self: center;
  }
</style>

<div class="position-fixed top-0 start-50 translate-middle-x mt-5 p-3" style="z-index: 1055;" id="alert-container">
  @if(session('success'))
    <div class="toast toast-style align-items-center border-0 show fade mb-2"
      role="alert" aria-live="assertive" aria-atomic="true"
      style="background-color: #198754;">
      
      <div class="toast-body" style="flex: 1;">{{ session('success') }}</div>
      <button type="button" class="toast-style-x" onclick="this.parentElement.remove()">
        <i class="bi bi-x"></i>
      </button>
    </div>

  @elseif(session('error'))
    <div class="toast toast-style align-items-center border-0 show fade mb-2"
      role="alert" aria-live="assertive" aria-atomic="true"
      style="background-color: #dc3545;">
      
      <div class="toast-body" style="flex: 1;">{{ session('error') }}</div>
      <button type="button" class="toast-style-x" onclick="this.parentElement.remove()">
        <i class="bi bi-x"></i>
      </button>
    </div>

  @elseif($errors->any())
    <div class="toast toast-style align-items-center border-0 show fade mb-2"
      role="alert" aria-live="assertive" aria-atomic="true"
      style="background-color: #dc3545;">
      
      <div class="toast-body" style="flex: 1;">
        <ul class="list-unstyled" style="padding-left: 1rem; margin: 0;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" class="toast-style-x" onclick="this.parentElement.remove()">
        <i class="bi bi-x"></i>
      </button>
    </div>
  @endif
</div>