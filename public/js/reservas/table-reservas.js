$(function () {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-euro-pre": function (a) {

            if ($.trim(a) !== '') {

                const parts = a.split(' às ')
                const dateParts = parts[0].split('/')
                const timeParts = parts[1].split(':')

                return new Date(
                    dateParts[2],
                    dateParts[1] - 1,
                    dateParts[0],
                    timeParts[0],
                    timeParts[1]
                ).getTime()

            }

            return 0
        },

        "date-euro-asc": function (a, b) {
            return a - b
        },

        "date-euro-desc": function (a, b) {
            return b - a
        }
    })


    $('#reservas').DataTable({

        order: [[0, 'desc']],

        columnDefs: [
    {
        targets: [2],
        type: 'date-euro'
    }
                    ],

        language: {
            decimal: "",
            emptyTable: "Nenhum registro disponível",
            info: "Mostrando _START_ até _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 até 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros no total)",
            thousands: ".",
            lengthMenu: "Mostrar _MENU_ registros por página",
            loadingRecords: "Carregando...",
            processing: "Processando...",
            search: "Procurar:",
            zeroRecords: "Nenhum registro encontrado",

            paginate: {
                first: "Primeira",
                last: "Última",
                next: "Próximo",
                previous: "Anterior"
            },

            aria: {
                sortAscending: ": ativar para ordenar coluna ascendente",
                sortDescending: ": ativar para ordenar coluna descendente"
            }
        },

        scrollCollapse: true,
        responsive: true,
        paging: true,
        searching: true,
        lengthChange: true

    })


    $('.btn-delete').on('click', function () {

        const deleteUrl = $(this).data('url')
        $('#deleteForm').attr('action', deleteUrl)

    })


    $('#dataSelecionada').on('change', function () {

        const salaId = $('#verReservasModal').data('sala-id')
        carregarReservas(salaId)

    })


    $('#verReservasModal').on('show.bs.modal', function (event) {

        const button = $(event.relatedTarget)
        const salaId = button.data('sala-id')

        $('#verReservasModal').data('sala-id', salaId)

        const hoje = new Date().toISOString().split('T')[0]

        $('#dataSelecionada').val(hoje)

        carregarReservas(salaId)

    })

})
