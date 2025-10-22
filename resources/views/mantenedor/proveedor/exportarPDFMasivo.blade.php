<!-- filepath: vsls:/resources/views/mantenedor/matrimonio/exportarPDFMasivo.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Matrimonios</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 0 auto; }
        th, td { border: 1px solid #222; padding: 6px 4px; }
        th { background-color: #004085; color: #fff; font-size: 12px; }
        tr:nth-child(even) td { background-color: #f2f2f2; }
        td { font-size: 11px; }
    </style>
</head>
<body>
    <h2>Reporte de Actas de Matrimonio</h2>
    <table>
        <thead>
            <tr>
                <th>ID Acta</th>
                <th>N° Libro</th>
                <th>N° Folio</th>
                <th>DNI Cónyuge 1</th>
                <th>Nombre Cónyuge 1</th>
                <th>DNI Cónyuge 2</th>
                <th>Nombre Cónyuge 2</th>
                <th>Fecha Matrimonio</th>
                <th>Régimen</th>
                <th>Registrador</th>
                <th>Alcalde</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrimonios as $item)
            <tr>
                <td>{{ $item->id_acta_matrimonio }}</td>
                <td>{{ $item->folio->libro->numero_libro ?? '' }}</td>
                <td>{{ $item->folio->numero_folio ?? '' }}</td>
                <td>{{ $item->dni_conyuge1 }}</td>
                <td>{{ $item->conyuge1->nombres ?? '' }} {{ $item->conyuge1->apellido_paterno ?? '' }}</td>
                <td>{{ $item->dni_conyuge2 }}</td>
                <td>{{ $item->conyuge2->nombres ?? '' }} {{ $item->conyuge2->apellido_paterno ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_matrimonio)->format('d/m/Y') }}</td>
                <td>{{ $item->regimen_matrimonial }}</td>
                <td>{{ $item->usuario->nombre_usuario ?? $item->id_usuario }}</td>
                <td>{{ $item->alcalde->persona->nombres ?? '' }} {{ $item->alcalde->persona->apellido_paterno ?? $item->id_alcalde }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>