function toggleDropdown(button) {
  const dropdown = button.parentElement;
  dropdown.classList.toggle("open");

  // Fecha o dropdown ao clicar fora dele
  document.addEventListener("click", function closeDropdown(event) {
    if (!dropdown.contains(event.target)) {
      dropdown.classList.remove("open");
      document.removeEventListener("click", closeDropdown);
    }
  });
}