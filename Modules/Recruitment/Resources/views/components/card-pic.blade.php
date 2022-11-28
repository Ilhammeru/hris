<style>
    .card-pic {
        background: #f4f6fe;
        padding: 5px 12px;
        border-radius: 10px;
        margin-top: 15px;
    }

    .card-pic > .card-body {
        padding: 5px 12px !important;
    }

    .pic-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .pic-message {
        background: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        margin-top: 4px;
    }

    .pic-message > .message {
        color: #000;
        font-size: 12px;
        text-align: left;
    }

    .btn-drop-line {
        width: 100%;
        background: #e2e5fe;
        color: #000;
        font-weight: bold;
        font-size: 12px;
        margin-top: 10px;
    }
</style>

<div class="card card-pic">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/150-3.jpg') }}" class="pic-icon me-4" alt="pic">

            <div>
                <p class="fw-bold m-0" style="font-size: 13px;">Monica Black</p>
                <p class="text-secondary m-0 fw-bold" style="font-size: 11px;">Contact Person</p>
            </div>
        </div>
        <div class="pic-message">
            <p class="message">
                Hi! </br>
                My Name is Monica, I'm Recruiter from Ampersand. </br></br>
                Drop us a line, if you have questions or didn't find what your were looking for.
            </p>
        </div>
        <div class="drop-button">
            <button class="btn btn-sm btn-drop-line" type="button" onclick="openModal()">{{ __('recruitment::view.drop_line') }}</button>
        </div>
    </div>
</div>

{{-- modal drop a line --}}
<div class="modal fade" id="DropMessage" tabindex="-1" aria-labelledby="DropMessageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="DropMessageLabel">Drop us a Line!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee.recruitment.send.message', $data->id) }}" method="POST" id="form-drop-message">
                    <div class="form-group mb-3">
                        <label for="email" class="col-form-label">{{ __('recruitment::view.email') }}</label>
                        <input type="email" name="email" id="email" placeholder="john@doe.com" class="form-control form-control-sm">
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone" class="col-form-label">{{ __('recruitment::view.phone') }}</label>
                        <input type="number" class="form-control form-control-sm" name="phone" placeholder="0899911111">
                    </div>
                    <div class="form-group mb-3">
                        <label for="message" class="col-form-label">{{ __('recruitment::view.message') }}</label>
                        <textarea name="message" id="message" cols="3" rows="4" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <button id="btn-send-message" class="btn btn-sm btn-success" type="button" onclick="dropMessage()">{{ __('recruitment::view.send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // event when this modal is closed
        const modalMessage = document.getElementById('DropMessage')
        modalMessage.addEventListener('hidden.bs.modal', event => {
            document.getElementById('form-drop-message').reset();
        });

        function openModal() {
            $('#DropMessage').modal('show');
        }

        function dropMessage() {
            let form = $('#form-drop-message');
            let url = form.attr('action');
            let method = form.attr('method');
            let data = form.serialize();
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-send-message', true);
                },
                success: function(res) {
                    setLoading('btn-send-message', false);
                    resetForm('form-drop-message');
                    setNotif(false, res.message);
                    $('#DropMessage').modal('hide');
                },
                error: function(err) {
                    setLoading('btn-send-message', false);
                    setNotif(true, err.responseJSON ? err.responseJSON.message : 'Sever error');
                }
            })
        }
    </script>
@endpush