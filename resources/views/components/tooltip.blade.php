<span data-bs-toggle="tooltip" data-bs-html="true"
  title="
    <div style='display: flex; align-items: center; gap: 6px;'>
      <span style='width: 10px; height: 10px; border-radius: 50%; background-color: #198754; display: inline-block;'></span>
      <span style='font-size: 13px;'>Sala disponível</span>
    </div>
    <div style='display: flex; align-items: center; gap: 6px; margin-top: 4px;'>
      <span style='width: 10px; height: 10px; border-radius: 50%; background-color: #dc3545; display: inline-block;'></span>
      <span style='font-size: 13px;'>Em manutenção</span>
    </div>
  ">

  <i class="bi bi-exclamation-circle text-primary" style="cursor: pointer; text-shadow: 0 0 1px;"></i>
</span>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl, {
        html: true,
        sanitize: false
      });
    });
  });
</script>