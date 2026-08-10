$(document).ready(function () {

    const table = $('#tableUsers').DataTable({
        paging: true,
        pageLength: 10,
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
        order: [[1, 'asc']]
    });

    // 2. Configuração do Modal de Exclusão
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const form = document.getElementById('deleteForm');
            form.action = `/usuarios/${userId}`;
        });
    }

    // 3. Exibição de Alertas (SweetAlert2) para Mensagens Flash
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

    // 4. Abertura e Preenchimento do Modal de Edição via AJAX
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
                const niveis = data.niveis_acesso; 

                $('#edit_name').val(user.name);
                $('#edit_login').val(user.login);
                $('#editUserForm').attr('action', `/usuarios/${userId}`);

                
                let optionsUnidades = '';
                unidades.forEach(unidade => {
                    const selected = (unidade.id === user.unidade_fk) ? 'selected' : '';
                    optionsUnidades += `<option value="${unidade.id}" ${selected}>${unidade.nome}</option>`;
                });
                $('#edit_unidade').html(optionsUnidades);

                let optionsNiveis = '';
                if (niveis) {
                    niveis.forEach(nivel => {
                        const selected = (nivel.id === user.nivel_acesso_id) ? 'selected' : '';
                        optionsNiveis += `<option value="${nivel.id}" ${selected}>${nivel.tipo}</option>`;
                    });
                    $('#edit_nivel_acesso_id').html(optionsNiveis);
                }

                $('input[name="password"]').val('');
                $('input[name="password_confirmation"]').val('');

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
