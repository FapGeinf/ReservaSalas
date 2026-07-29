$(document).ready(function () {

    const table = $('#tableUsers').DataTable({
        language: {
            url: '/lang/pt-BR.json',
            search: "Procurar:",
            lengthMenu: "Paginação: _MENU_",
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'Sem usuários disponíveis no momento',
            infoFiltered: '(Filtrados do total de _MAX_ registros)',
            zeroRecords: 'Nada encontrado.',
            paginate: {
                next: "Próximo",
                previous: "Anterior"
            }
        },
        scrollCollapse: true,
        paging: true,
        order: [[0, 'desc']]
    });

    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const form = document.getElementById('deleteForm');
            form.action = `/usuarios/${userId}`;
        });
    }

    const flash = document.getElementById("flash-messages");
    if (flash) {
        const success = flash.dataset.success;
        const error = flash.dataset.error;

        if (success && success.trim() !== "") {
            Swal.fire({
                position: "top",
                title: "Sucesso!",
                text: success,
                icon: "success",
                confirmButtonText: "Fechar",
                customClass: { confirmButton: "button-green" }
            });
        }

        if (error && error.trim() !== "") {
            Swal.fire({
                position: "top",
                title: "Ops!",
                text: error,
                icon: "error",
                confirmButtonText: "Fechar",
                customClass: { confirmButton: "button-red" }
            });
        }
    }

    $(document).on('click', '.btn-edit-user', function () {
        const userId = $(this).data('user-id');
        const modalElement = document.getElementById('editUserModal');
        const modal = new bootstrap.Modal(modalElement);

        $('#edit_unidade').html('<option value="">Carregando unidades...</option>');

        $.ajax({
            url: `/usuarios/${userId}/edit`,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                const user = data.user;
                const unidades = data.unidades;


                $('#edit_name').val(user.name);
                $('#edit_login').val(user.login);


                $('#edit_is_admin').val(user.is_admin ? "1" : "0");

                $('#editUserForm').attr('action', `/usuarios/${userId}`);

                let options = '';
                unidades.forEach(unidade => {
                    const selected = (unidade.id === user.unidade_fk) ? 'selected' : '';
                    options += `<option value="${unidade.id}" ${selected}>${unidade.nome}</option>`;
                });
                $('#edit_unidade').html(options);

                $('input[name="password"]').val('');

                modal.show();
            },
            error: function () {
                Swal.fire({
                    title: "Erro!",
                    text: "Não foi possível carregar os dados do usuário.",
                    icon: "error",
                    confirmButtonText: "Fechar",
                    customClass: { confirmButton: "button-red" }
                });
            }
        });
    });
});
