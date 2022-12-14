<?php
// dd($perencanaan);
?>
<html>
    <body>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data Pengukuran Logger.xls");
    ?>

    <table>
        <tr>
            <td colspan="4" style="text-align:center">Data Pengukuran Logger</td>
        </tr>
    </table>
    <br>
    <table border="1">
        <tr>
            <th>Tanggal Jam</th>
            <th>value</th>
            <th>Satuan</th>
            <th>Kolam</th>
        </tr>
        @foreach ($log as $key => $item)
            <tr>
                <td>{{$item->tgl_jam}}</td>
                <td>{{$item->value}}</td>
                <td>{{$item->nama_sat.'('.$item->simbol.')'}}</td>
                <td>{{$item->kolam}}</td>
            </tr>
        @endforeach
    </table>
    </body>
</html>
