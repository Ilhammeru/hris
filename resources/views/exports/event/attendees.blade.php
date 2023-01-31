<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Bagian</th>
            <th style="width: 10px;">TTD</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->attendant->employee_id }}</td>
                <td>{{ $item->attendant->name }}</td>
                <td>{{ $item->attendant->position->name }}</td>
                <td style="width: 5px;">
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