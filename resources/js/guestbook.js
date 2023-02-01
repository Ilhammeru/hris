import { Loading } from '../../public/plugins/notiflix/build/notiflix-loading-aio';
import { Notify } from '../../public/plugins/notiflix/build/notiflix-notify-aio';
import { Confirm } from '../../public/plugins/notiflix/build/notiflix-confirm-aio';

$('#search-attend').select2({
    placeholder: "Search ...",
    allowClear: true
});

var signaturePad;

const myModalEl = document.getElementById('modalSignature')
myModalEl.addEventListener('hidden.bs.modal', event => {
    signaturePad.clear()
})
myModalEl.addEventListener('shown.bs.modal', event => {
    showSignaturePad();
})

// $('#btn-search').on('click', (e) => {
//     e.preventDefault();
//     console.log('e',e.id);
//     showSignaturePad();
// });

function searchData(option) {
    if ($('#search-attend').val() != '') {
        if (option == 1) {
            // show modal signature
            showSignaturePad();
        } else {
            // show confirmation box
            Confirm.show(
                i18n.view.save_confirm,
                i18n.view.save_confirmation,
                i18n.view.yes_save,
                i18n.view.no,
                () => {
                    let form = $('#form-check-in');
                    let data = form.serialize();
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: window.location.origin + '/event/check_in',
                        data: data,
                        beforeSend: function() {
                            Loading.hourglass(i18n.view.processing);
                        },
                        success: function(res) {
                            Loading.remove();
                            if (res.status) {
                                Notify.warning(res.message);
                            } else {
                                Notify.success(res.message);
                            }
                            document.getElementById('form-check-in').reset();
                            signaturePad.clear();
                            $('#modalSignature').modal('hide');
                        },
                        error: function(err) {
                            console.log('err',err);
                            Loading.remove();
                            Notify.warning(err.responseJSON ? err.responseJSON.message : i18n.view.failed_communication);
                        }
                    })
                }
            );
        }
    }
}

function submitData() {
    if (signaturePad.isEmpty()) {
        Notify.warning(i18n.view.signature_failed);
    } else {
        var dataSignature = signaturePad.toDataURL('image/png');
        $('#signature_field').val(dataSignature);
    
        Confirm.show(
            i18n.view.save_confirm,
            i18n.view.save_confirmation,
            i18n.view.yes_save,
            i18n.view.no,
            () => {
                let form = $('#form-check-in');
                let data = form.serialize();
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: window.location.origin + '/event/check_in',
                    data: data,
                    beforeSend: function() {
                        Loading.hourglass(i18n.view.processing);
                    },
                    success: function(res) {
                        Loading.remove();
                        if (res.status) {
                            Notify.warning(res.message);
                        } else {
                            Notify.success(res.message);
                        }
                        document.getElementById('form-check-in').reset();
                        $('#search-attend').val(null).trigger('change');
                        $('#modalSignature').modal('hide');
                    },
                    error: function(err) {
                        console.log('err',err);
                        Loading.remove();
                        Notify.warning(err.responseJSON ? err.responseJSON.message : i18n.view.failed_communication);
                    }
                })
            }
        );
    }
        
}

function updateButton(e) {
    let val = e.value;
    if (val == '') {
        $('#btn-search').prop('disabled', true);
    } else {
        $('#btn-search').prop('disabled', false);
    }
}

function showSignaturePad() {
    $('.sign-container').removeClass('d-none');
    var canvas = document.getElementById('signature-pad');

    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
    function resizeCanvas() {
        // When zoomed out to less than 100%, for some very strange reason,
        // some browsers report devicePixelRatio as less than 1
        // and only part of the canvas is cleared then.
        var ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    window.onresize = resizeCanvas;
    resizeCanvas();

    signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
    });

    // document.getElementById('save-png').addEventListener('click', function () {
    //     if (signaturePad.isEmpty()) {
    //         return alert("Please provide a signature first.");
    //     }
        
    //     var data = signaturePad.toDataURL('image/png');
    //         console.log(data);
    //         window.open(data);
    // });

    // document.getElementById('save-jpeg').addEventListener('click', function () {
    // if (signaturePad.isEmpty()) {
    //     return alert("Please provide a signature first.");
    // }

    // var data = signaturePad.toDataURL('image/jpeg');
    //     console.log(data);
    //     window.open(data);
    // });

    // document.getElementById('save-svg').addEventListener('click', function () {
    // if (signaturePad.isEmpty()) {
    //     return alert("Please provide a signature first.");
    // }

    // var data = signaturePad.toDataURL('image/svg+xml');
    // console.log(data);
    // console.log(atob(data.split(',')[1]));
    // window.open(data);
    // });

    document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
    });

    document.getElementById('undo').addEventListener('click', function () {
        var data = signaturePad.toData();
    if (data) {
        data.pop(); // remove the last dot or line
        signaturePad.fromData(data);
    }
    });

    $('#modalSignature').modal('show');
}

window.searchData = searchData;
window.showSignaturePad = showSignaturePad;
window.updateButton = updateButton;
window.submitData = submitData;