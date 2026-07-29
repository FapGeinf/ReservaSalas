$(document).ready(function() {
    $('#tableSalas').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        language: {
            url: '/lang/pt-BR.json',
        }
    });
    const successMsg = $('#success-message').data('message');
    if (successMsg) {
        Swal.fire({
            position: 'top',
            title: 'Sucesso!',
            text: successMsg,
            icon: 'success',
            confirmButtonText: 'Fechar',
            customClass: { confirmButton: 'button-green' }
        });
    }
});
