$(function () {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-euro-pre": function (a) {
            if ($.trim(a) === '') return 0;

            var parts = a.split(' às ');
            var datePart = parts[0];
            var timePart = parts.length > 1 ? parts[1] : '00:00'; 

            var dateParts = datePart.split('/');
            if (dateParts.length !== 3) return 0; 

            var timeParts = timePart.split(':');
            var hour = parseInt(timeParts[0], 10) || 0;
            var minute = parseInt(timeParts[1], 10) || 0;

            return new Date(
                parseInt(dateParts[2], 10),      
                parseInt(dateParts[1], 10) - 1,  
                parseInt(dateParts[0], 10),       
                hour,
                minute
            ).getTime();
        },

        "date-euro-asc": function (a, b) {
            return a - b;
        },

        "date-euro-desc": function (a, b) {
            return b - a;
        }
    });

    $('#reservas').DataTable({
        order: [[2, 'asc']], 
        columnDefs: [
            { targets: [2], type: 'date-euro' }
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
    });

   
    $('.btn-delete').on('click', function () {
        var deleteUrl = $(this).data('url');
        $('#deleteForm').attr('action', deleteUrl);
    });

    $('#dataSelecionada').on('change', function () {
        var salaId = $('#verReservasModal').data('sala-id');
        if (salaId && typeof carregarReservas === 'function') {
            carregarReservas(salaId);
        }
    });


    $('#verReservasModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var salaId = button.data('sala-id');

        $('#verReservasModal').data('sala-id', salaId);

        var hoje = new Date().toISOString().split('T')[0];
        $('#dataSelecionada').val(hoje);

        if (salaId && typeof carregarReservas === 'function') {
            carregarReservas(salaId);
        }
    });
});