import { Loading } from '../../public/plugins/notiflix/build/notiflix-loading-aio';
import { Notify } from '../../public/plugins/notiflix/build/notiflix-notify-aio';
import { Confirm } from '../../public/plugins/notiflix/build/notiflix-confirm-aio';

var currentdate = new Date(); 
var datetime = "Last Sync: " + currentdate.getDate() + "-"
                + addZero((currentdate.getMonth()+1))  + "-" 
                + currentdate.getFullYear() + " "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes();

$('#start_date').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    locale: {
        format: 'YYYY-MM-DD HH:mm',
    },
});

var dt_attendant_list;

// datatables
let columns = [
    {data: 'id',
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        } 
    },
    {data: 'name', name: 'name'},
    {data: 'guestbook', name: 'guestbook'},
    {data: 'option_finisher', name: 'option_finisher'},
    {data: 'start_date', name: 'start_date'},
    {data: 'end_date', name: 'end_date'},
    {data: 'action', name: 'action'},
];
let dt_route = base_url + '/event/ajax';
let dt_event_list = setDataTable('table-event-list', columns, dt_route);

// event modal
const myModalEl = document.getElementById('modalEvent')
if (myModalEl) {
    myModalEl.addEventListener('hidden.bs.modal', event => {
        resetForm('form-update-event');
    });
}

function showFormUpdate() {
    toggleModal('modalEvent', 'show');
}

function saveEvent() {
    let form = $('#form-update-event');
    let url = base_url + '/event';
    let data = form.serialize();

    let validate = validateForm('form-update-event', 'error');

    if (validate) {
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            beforeSend: function() {
                Loading.hourglass(i18n.view.processing);
            },
            success: function(res) {
                Loading.remove();
                Notify.success(res.message);
                dt_event_list.ajax.reload();
                toggleModal('modalEvent', 'hide');
                resetForm('form-update-event');
            },
            error: function(err) {
                console.log('err',err);
                Loading.remove();
                Notify.warning(err.responseJSON ? err.responseJSON.message : i18n.view.failed_communication);
            }
        });
    }
}

function editItem(id) {
    $.ajax({
        type: "GET",
        url: base_url + '/event/' + id + '/edit',
        beforeSend: function() {
            showFormUpdate();
        },
        success: function(res) {
            $('#form-update-event #name').val(res.data.name);
            if (res.data.option_finisher == 1) {
                $('#signature-option').prop('checked', true);
            } else {
                $('#confirmation-box-option').prop('checked', true);
            }
            $('#start_date').val(res.data.event_date);
            $('#event_id_field').val(res.data.id);
        }
    })
}

function initListAttendees(id) {
    // datatables
    let columns = [
        {data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            } 
        },
        {data: 'name', name: 'name'},
        {data: 'employee_id', name: 'employee_id'},
        {data: 'position', name: 'position'},
        {data: 'signature', name: 'signature'},
        {data: 'check_in_at', name: 'check_in_at'},
        {data: 'vaccine', name: 'vaccine'},
    ];
    let dt_route = base_url + '/event/ajax/' + id;
    dt_attendant_list = setDataTable('table-detail-event-list', columns, dt_route);
}

window.showFormUpdate = showFormUpdate;
window.saveEvent = saveEvent;
window.editItem = editItem;
window.initListAttendees = initListAttendees;