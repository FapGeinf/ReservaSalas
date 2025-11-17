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
    modal.removeAttribute("tabindex");

    setTimeout(() => {

      flatpickr("#hora_inicio", {
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i",
        altInput: true,
        altFormat: "H:i",
        locale: Portuguese
      });

      flatpickr("#hora_termino", {
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i",
        altInput: true,
        altFormat: "H:i",
        locale: Portuguese
      });

    }, 120);
  });
});
