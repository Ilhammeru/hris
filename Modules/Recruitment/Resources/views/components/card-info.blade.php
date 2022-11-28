<style>
    .card-vaccant-info {
        background: #f4f6fe;
        padding: 5px 12px;
        border-radius: 10px;
        margin-top: 15px;
    }

    .card-vaccant-info > .card-body {
        padding: 5px 12px !important;
    }
</style>


<div class="card card-vaccant-info">
    <div class="card-body">
        <p class="m-0 fw-bold text-center">{{ __('recruitment::view.vaccant_information') }}</p>

        <table class="table w-100 mt-5">
            <tr>
                <td class="p-0 m-0">
                    <p class="m-0" style="font-size: 12px;">{{ __('recruitment::view.total_applicant') }}</p>
                </td>
                <td class="pt-0 pb-0 pe-3 ps-2 m-0">:</td>
                <td class="p-0 m-0">
                    <p class="m-0 fw-bold" style="font-size: 12px;">13</p>
                </td>
            </tr>
            <tr>
                <td class="p-0 m-0">
                    <p class="m-0" style="font-size: 12px;">{{ __('recruitment::view.remaining_time') }}</p>
                </td>
                <td class="pt-0 pb-0 pe-3 ps-2 m-0">:</td>
                <td class="p-0 m-0">
                    <p class="m-0 fw-bold" style="font-size: 12px;">{{ $remaining_time }}</p>
                </td>
            </tr>
            <tr>
                <td class="p-0 m-0">
                    <p class="m-0" style="font-size: 12px;">{{ __('recruitment::view.publish_date') }}</p>
                </td>
                <td class="pt-0 pb-0 pe-3 ps-2 m-0">:</td>
                <td class="p-0 m-0">
                    <p class="m-0 fw-bold" style="font-size: 12px;" id="info-publish-date">{{ $data->publish_date ? date('d M Y', strtotime($data->publish_date)) : '-' }}</p>
                </td>
            </tr>
            <tr>
                <td class="p-0 m-0">
                    <p class="m-0" style="font-size: 12px;">{{ __('recruitment::view.publish_by') }}</p>
                </td>
                <td class="pt-0 pb-0 pe-3 ps-2 m-0">:</td>
                <td class="p-0 m-0">
                    <p class="m-0 fw-bold" style="font-size: 12px;" id="info-publish-by">{{ $data->publish_by ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
</div>