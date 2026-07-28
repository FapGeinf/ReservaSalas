$(document).ready(function() {
    const $horaInicio = $('#hora_inicio');
    const $horaFim = $('#data_fim');
    const $btnSubmit = $('#submit-btn');
    const $alert = $('#hora_alert');

    function validarHorarios() {
        const inicio = $horaInicio.val();
        const fim = $horaFim.val();

        if (inicio && fim) {
            const minInicio = parseInt(inicio.split(':')[0]) * 60 + parseInt(inicio.split(':')[1]);
            const minFim = parseInt(fim.split(':')[0]) * 60 + parseInt(fim.split(':')[1]);

            if (minFim <= minInicio) {
                $alert.removeClass('d-none');
                $btnSubmit.prop('disabled', true);
            } else {
                $alert.addClass('d-none');
                $btnSubmit.prop('disabled', false);
            }
        }
    }

    $horaInicio.on('change', validarHorarios);
    $horaFim.on('change', validarHorarios);
    
    validarHorarios();
});