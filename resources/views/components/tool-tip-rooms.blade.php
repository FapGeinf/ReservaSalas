<span data-bs-toggle="tooltip2" data-bs-html="true"
  style="margin-right: 30px;"
  title="
    <div style='display: flex; align-items: center; gap: 6px; margin-top: 4px;'>
      <span style='width: 10px; height: 10px; border-radius: 50%; background-color: #dc3545; display: inline-block;'></span>
      <span style='font-size: 13px;'>Em manutenção</span>
    </div>
  ">

  <i class="bi bi-exclamation-triangle text-danger" style="cursor: pointer; text-shadow: 0 0 1px;"></i>
</span>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip2"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl, {
        html: true,
        sanitize: false
      });
    });
  });
</script>