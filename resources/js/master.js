function resetForm(formId) {
    document.getElementById(formId).reset();
}

function toggleModal(modalId, toggle = 'show') {
    $(`#${modalId}`).modal(toggle);
}

function addZero(text) {
    let res = text;
    if (text < 10) {
        res = '0' + text;
    }

    return res;
}

function validateForm(formId, type = 'error') {
    let elem = $('#' + formId + ' .form-control');
    let value,id,name;
    let validate = true;
    for (let a = 0; a < elem.length; a++) {
        id = elem[a].id;
        value = elem[a].value;
        if (id) {
            if (type == 'error') {
                if (value == '' || !value) {
                    name = $('#' + id).data('name');
                    $('#' + id).addClass('is-invalid');
                    $('#' + id + '_err').html(name + ' field is required');
                    validate = false;
                } else {
                    $('#' + id).removeClass('is-invalid');
                    $('#' + id + '_err').html('');
                }
            } else {
                $('#' + id).removeClass('is-invalid');
                $('#' + id + '_err').html('');
            }
        }
    }

    return validate;
}

window.resetForm = resetForm;
window.toggleModal = toggleModal;
window.validateForm = validateForm;
window.addZero = addZero;