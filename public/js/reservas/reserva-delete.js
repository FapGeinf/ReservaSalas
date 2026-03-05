window.setDeleteId = function(id) {
    const form = document.getElementById('deleteForm');
    if (form) {
        form.action = `/reservas/${id}`;
    } else {
        console.error("Formulário de exclusão não encontrado!");
    }
};