import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";

flatpickr("#dataSelecionada", {
    locale: Portuguese,
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d"
});
