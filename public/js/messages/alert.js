document.addEventListener("DOMContentLoaded", function () {

    const flash = document.getElementById("flash-messages")

    if (!flash) return

    const success = flash.dataset.success
    const error = flash.dataset.error

    if (success) {
        Swal.fire({
            position: "top",
            title: "Sucesso!",
            text: success,
            icon: "success",
            confirmButtonText: "Fechar",
            customClass: {
                confirmButton: "button-green"
            }
        })
    }

    if (error) {
        Swal.fire({
            position: "top",
            title: "Ops!",
            text: error,
            icon: "error",
            confirmButtonText: "Fechar",
            customClass: {
                confirmButton: "button-red"
            }
        })
    }

})