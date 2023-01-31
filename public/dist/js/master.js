/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./resources/js/master.js ***!
  \********************************/
function resetForm(formId) {
  document.getElementById(formId).reset();
}

function toggleModal(modalId) {
  var toggle = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'show';
  $("#".concat(modalId)).modal(toggle);
}

function addZero(text) {
  var res = text;

  if (text < 10) {
    res = '0' + text;
  }

  return res;
}

function validateForm(formId) {
  var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'error';
  var elem = $('#' + formId + ' .form-control');
  var value, id, name;
  var validate = true;

  for (var a = 0; a < elem.length; a++) {
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
/******/ })()
;