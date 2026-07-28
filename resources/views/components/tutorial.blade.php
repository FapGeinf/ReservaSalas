<!-- BOTÃO PARA ABRIR O TUTORIAL -->
<button 
  class="help-btn"
  data-bs-toggle="modal" 
  data-bs-target="#modalTutorialReservas">
  <i class="bi bi-question-circle"></i>
</button>

<!-- MODAL TUTORIAL -->
<div class="modal fade" id="modalTutorialReservas" tabindex="-1" aria-labelledby="tutorialLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="tutorialLabel">Tutorial de Uso — Reservar Salas</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body p-4">
        <!-- PASSOS -->
        <div id="tutorialStep1" class="tutorial-step fs-13" style="color: #374151;">
          <p class="fw-medium mb-2">1. Acesse o painel lateral</p>

          <p>No lado esquerdo da tela, localize o painel <span class="fw-medium">“Reservar Salas”</span>. 
          Ele mostra todas as salas e seus status:</p>
          <ul class="list-unstyled">
            <li>
              <span class="text-danger fw-medium">
                <span class="rounded-circle bg-danger status-ball" style="width: 7px; height: 7px; display: inline-block;"></span>
                Vermelho
              </span>
              : Sala inativa
            </li>

            <li>
              <span class="text-success fw-medium">
                <span class="rounded-circle bg-success status-ball" style="width: 7px; height: 7px;"></span> 
                Verde
              </span>
              : Sala ativa
            </li>
          </ul>
        </div>

        <div id="tutorialStep2" class="tutorial-step d-none fs-13" style="color: #374151;">
          <p class="fw-medium mb-2">2. Escolha a sala</p>

          <p>Cada sala aparece em um bloco com nome e ícone <i class="bi bi-building text-secondary"></i>. 
          Clique em <span class="fw-medium">“Reservar”</span> para iniciar a reserva.</p>

          <p class="text-muted">
            <i class="bi bi-lightbulb-fill text-warning"></i>
            Se o botão não aparecer, a sala está inativa.
          </p>
        </div>

        <div id="tutorialStep3" class="tutorial-step d-none fs-13" style="color: #374151;">
          <p class="fw-medium mb-2">3. Abrir o modal de reserva</p>

          <p>Ao clicar em <span class="fw-medium">“Reservar”</span>, será aberta a janela <span class="fw-medium">“Nova Reserva”</span>. 
          Preencha os dados e clique em <span class="fw-medium">“Salvar Reserva”</span>.</p>
        </div>

        <div id="tutorialStep4" class="tutorial-step d-none fs-13" style="color: #374151;">
          <p class="fw-medium mb-2">4. Visualizar no calendário</p>

          <p>No lado direito está o <span class="fw-medium">calendário principal</span> para consulta. 
          Após salvar, a reserva aparecerá automaticamente com nome e horário.</p>
        </div>

        <div id="tutorialStep5" class="tutorial-step d-none fs-13" style="color: #374151;">
          <p class="fw-medium mb-2">5. Consultar ou cancelar</p>

          <p>Clique em qualquer evento do calendário para abrir os detalhes da reserva. 
          Lá é possível ver informações completas ou excluir a reserva.</p>
        </div>
      </div>

      {{-- <div class="modal-footer d-flex justify-content-between py-2"> --}}
      <div class="modal-footer bg-modal-footer d-flex py-2">
        {{-- <button type="button" class="button-grey" data-bs-dismiss="modal">Fechar</button> --}}

        <div class="justify-content-end">
          <button type="button" class="button-grey btn-prev" disabled>Voltar</button>
          <button type="button" class="button-blue btn-next">Próximo</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPT DO TUTORIAL -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".tutorial-step");
    const btnNext = document.querySelector(".btn-next");
    const btnPrev = document.querySelector(".btn-prev");
    let currentStep = 0;

    function showStep(index) {
      steps.forEach((step, i) => step.classList.toggle("d-none", i !== index));
      btnPrev.disabled = index === 0;
      btnNext.textContent = index === steps.length - 1 ? "Concluir" : "Próximo";
    }

    btnNext.addEventListener("click", () => {
      if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
      } else {
        // Último passo → usa o mesmo mecanismo nativo do Bootstrap
        const modalEl = document.getElementById("modalTutorialReservas");
        modalEl.querySelector('[data-bs-dismiss="modal"]').click();
      }
    });

    btnPrev.addEventListener("click", () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
      }
    });

    // Reinicia no passo 1 toda vez que o modal abrir
    const modalEl = document.getElementById("modalTutorialReservas");
    modalEl.addEventListener("show.bs.modal", () => {
      currentStep = 0;
      showStep(currentStep);
    });

    showStep(currentStep);
  });
</script>