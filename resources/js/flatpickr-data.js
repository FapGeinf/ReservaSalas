import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";

flatpickr("#dataSelecionada", {
  locale: Portuguese,
  altInput: true,
  altFormat: "d/m/Y",
  dateFormat: "Y-m-d"
});

document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modalReserva");
  modal.addEventListener("shown.bs.modal", function () {

    // Corrige conflito de foco do Bootstrap com flatpickr
    modal.removeAttribute("tabindex");

    // Aguarda a animação finalizar
    setTimeout(() => {
      const fpHora = flatpickr("#hora_inicio", {
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i",
        altInput: true,
        altFormat: "H:i",
        locale: Portuguese
      });

      fpHora.open();
    }, 120); // delay pequeno, suficiente para evitar o piscar
  });
});
