import { Loading } from '../../public/plugins/notiflix/build/notiflix-loading-aio';
import { Notify } from '../../public/plugins/notiflix/build/notiflix-notify-aio';
import { Confirm } from '../../public/plugins/notiflix/build/notiflix-confirm-aio';

let columns = [
        {data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            } 
        },
        {data: 'name', name: 'name'},
        {data: 'employee_id', name: 'employee_id'},
        {data: 'position_id', name: 'position_id'},
        {data: 'action', name: 'action'},
];
let dt_route = base_url + '/attendant/ajax';
let dt_attendant_list = setDataTable('table-attendant-list', columns, dt_route);

// init filepond
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
);

// Select the file input and use
// create() to turn it into a pond
FilePond.create(
    document.getElementById('filepond'),
    {
        acceptedFileTypes: ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.oasis.opendocument.spreadsheet'],
        storeAsFile: true,
    }
);

// get a reference to the root node
const pond = document.querySelector('.filepond');

// listen for events
pond.addEventListener('FilePond:addfile', (e) => {
    $('#modalImport .btn-save').prop('disabled', false);
});
pond.addEventListener('FilePond:removefile', (e) => {
    $('#modalImport .btn-save').prop('disabled', true);
});

$('#position_id_form').select2({
    dropdownParent: '#modalCreateAttendant'
});

// event modal
const myModalEl = document.getElementById('modalCreateAttendant')
myModalEl.addEventListener('hidden.bs.modal', event => {
    resetForm('form-update');
});

function showFormImport() {
    $('#modalImport').modal('show');
}

function showFormUpdate() {
    $('#modalCreateAttendant').modal('show');
}

function importFile() {
    let form = $('#form-import');
    let data = new FormData($('#form-import')[0]);
    $.ajax({
        type: "POST",
        url: base_url + '/attendant/import',
        data: data,
        contentType: false,
        processData: false,
        beforeSend() {
            Loading.hourglass(i18n.view.processing);
        },
        success: function(res) {
            Loading.remove();
            Notify.success(i18n.view.success_import_data);
            dt_attendant_list.ajax.reload();
            $('#modalImport').modal('hide');
            document.getElementById('form-import').reset();
            $('#modalImport .btn-save').prop('disabled', true);
        },
        error: function(err) {
            Loading.remove();
            if (err.responseJSON) {
                Notify.warning(err.responseJSON.message);
            } else {
                Notify.warning(i18n.view.failed_communication);
            }
        }
    })
}

function saveAttendant() {
    let form = $('#form-update');
    let data = form.serialize();
    let url = base_url + '/attendant';

    let validate = validateForm('form-update', 'error');
    if (validate) {
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            beforeSend: function() {
                Loading.hourglass(i18n.view.processing);
            },
            success: function(res) {
                console.log('res',res);
                Loading.remove();
                Notify.success(res.message);
                toggleModal('modalCreateAttendant', 'hide');
                dt_attendant_list.ajax.reload();
                resetForm('form-update');
            },
            error: function(err) {
                console.log('err',err);
                Loading.remove();
                Notify.warning(err.responseJSON ? err.responseJSON.message : i18n.view.failed_communication);
            }
        });
    }
}

function deleteItem(id) {
    Confirm.show(
        i18n.view.delete_confirm,
        i18n.view.delete_confirmation,
        i18n.view.yes_delete,
        i18n.view.no,
        () => {
            $.ajax({
                type: 'DELETE',
                url: base_url + '/attendant/' + id,
                beforeSend: function() {
                    Loading.hourglass(i18n.view.processing);
                },
                success: function(res) {
                    Loading.remove();
                    Notify.success(res.message);
                    dt_attendant_list.ajax.reload();
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

window.showFormImport = showFormImport;
window.importFile = importFile;
window.showFormUpdate = showFormUpdate;
window.saveAttendant = saveAttendant;
window.deleteItem = deleteItem;