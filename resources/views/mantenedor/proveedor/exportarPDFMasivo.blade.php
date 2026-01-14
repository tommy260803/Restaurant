<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Proveedores</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin: 0 auto; }
        th, td { border: 1px solid #222; padding: 6px 4px; }
        th { background-color: #2c3e50; color: #fff; font-size: 12px; }
        tr:nth-child(even) td { background-color: #f2f2f2; }
        td { font-size: 11px; }
    </style>
</head>
<body>
    <h2>Reporte de Proveedores</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>RUC</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Calificación</th>
                <th>Incumplimientos</th>
            </tr>
        </thead>
        <tbody>
            @php $contador = 1; @endphp
            @foreach($proveedores as $item)
            <tr>
                <td>{{ $contador++ }}</td>
                <td>{{ $item->nombre }} {{ $item->apellidoPaterno }}</td>
                <td>{{ $item->telefono ?: '-' }}</td>
                <td>{{ $item->email ?: '-' }}</td>
                <td>{{ $item->rucProveedor ?: '-' }}</td>
                <td>{{ $item->direccion ?: '-' }}</td>
                <td>{{ ucfirst($item->estado) }}</td>
                <td>{{ $item->calificacion > 0 ? $item->calificacion . '/5' : '-' }}</td>
                <td>{{ $item->incumplimientos }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>