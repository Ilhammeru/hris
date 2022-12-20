<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>receipt</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
  <link rel="stylesheet" href="../paper.css">
  <style>
    @page { size: 58mm 100mm }
    body.receipt .sheet { width: 58mm; height: 100mm } /* change height as you like */
    body { background: #e6e6e6; }
    .sheet { background: #fff; }
    table tbody tr td:first-child { font-size: 12px; }
    table tbody tr td:last-child { font-size: 13px; font-weight: bold; }
    table tbody tr td { padding: 5px; }
    /** Padding area **/
    .sheet.padding-10mm { padding: 10mm }
    .sheet.padding-15mm { padding: 15mm }
    .sheet.padding-20mm { padding: 20mm }
    .sheet.padding-25mm { padding: 25mm }
    p { padding: 0; margin: 0; }
    @media print { body.receipt { width: 58mm } } /* this line is needed for fixing Chrome's bug */
  </style>
</head>

<body class="receipt">
    <section class="sheet padding-10mm">
        <div style="text-align: center; margin-bottom: 10px;">
            No #0000111
        </div>
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $name }}</td>
                </tr>
                <tr>
                    <td>Bagian</td>
                    <td>:</td>
                    <td>{{ $division }}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td>{{ $time }}</td>
                </tr>
                <tr>
                    <td>Keperluan</td>
                    <td>:</td>
                    <td>{{ $need }}</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 150px; text-align: end;">
            <p>Disetujui Oleh</p>
            <p style="margin-top:25px;">Mastahtalifun, S.Pd</p>
        </div>
    </section>

    <script>
        window.print();
    </script>
</body>
</html>