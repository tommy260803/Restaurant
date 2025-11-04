<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Reserva</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 10px; }
        .section { margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 6px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Reserva</h2>
        <p>Reserva #{{ $reserva->id }}</p>
    </div>

    <div class="details">
        <p><strong>Nombre:</strong> {{ $reserva->nombre_cliente }}</p>
        <p><strong>Correo:</strong> {{ $reserva->email ?? '-' }}</p>
        <p><strong>Teléfono:</strong> {{ $reserva->telefono }}</p>
        <p><strong>Fecha:</strong> {{ $reserva->fecha_formateada }}</p>
        <p><strong>Hora:</strong> {{ $reserva->hora_formateada }}</p>
        <p><strong>Mesa:</strong> {{ optional($reserva->mesa)->numero ?? 'Sin asignar' }}</p>
        <p><strong>Personas:</strong> {{ $reserva->numero_personas }}</p>
    </div>

    @if($reserva->tienePlatosPedidos())
    <div class="section">
        <h4>Platos pre-ordenados</h4>
        <table>
            <thead>
                <tr>
                    <th>Plato</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reserva->platos as $plato)
                <tr>
                    <td>{{ $plato->nombre }}</td>
                    <td>{{ $plato->pivot->cantidad }}</td>
                    <td>S/ {{ number_format($plato->pivot->precio,2) }}</td>
                    <td>S/ {{ number_format($plato->pivot->cantidad * $plato->pivot->precio,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="text-align:right;margin-top:10px"><strong>Total platos: S/ {{ number_format($reserva->subtotal_platos,2) }}</strong></p>
    </div>
    @endif

    <div class="section" style="margin-top:20px">
        <p>Gracias por su reserva. Presente este comprobante en el restaurante.</p>
    </div>
</body>
</html>