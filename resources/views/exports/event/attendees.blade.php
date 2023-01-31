<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Bagian</th>
            <th>Vaksin</th>
            <th>TTD</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->attendant->employee_id }}</td>
                <td>{{ $item->attendant->name }}</td>
                <td>{{ $item->attendant->position->name }}</td>
                <td>{{ $item->attendant->vaccine_booster }}</td>
                <td>
                    @if ($item->signature_path)
                        <img src="{{ $item->signature_path }}" style="width: 10px; height: auto;" alt="">
                    @else
                    -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>