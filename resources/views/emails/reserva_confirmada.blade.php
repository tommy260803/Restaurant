<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pago Exitoso</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }

        .resaltado {
            background-color: #eafaf1;
            padding: 10px;
            border-left: 4px solid #2ecc71;
            margin: 20px 0;
        }

        .firma {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Confirmación de Reserva</h2>

        <p>Estimado/a {{ $reserva->nombre_cliente }},</p>

        <p>Le confirmamos que su reserva ha sido registrada correctamente en nuestro sistema. A continuación los
            detalles de su reserva:</p>

        <div class="resaltado">
            <p><strong>Nombre:</strong> {{ $reserva->nombre_cliente }}</p>
            <p><strong>Fecha:</strong> {{ $reserva->fecha_formateada }}</p>
            <p><strong>Hora:</strong> {{ $reserva->hora_formateada }}</p>
            <p><strong>Mesa:</strong> {{ optional($reserva->mesa)->numero ?? 'Sin asignar' }}</p>
            <p><strong>Personas:</strong> {{ $reserva->numero_personas }}</p>
        </div>

        @if($reserva->tienePlatosPedidos())
            <div>
                <h4>Platos pre-ordenados</h4>
                <ul>
                    @foreach($reserva->platos as $plato)
                        <li>{{ $plato->nombre }} — {{ $plato->pivot->cantidad }} x S/ {{ number_format($plato->pivot->precio,2) }}</li>
                    @endforeach
                </ul>
                <p><strong>Subtotal platos:</strong> S/ {{ number_format($reserva->subtotal_platos,2) }}</p>
            </div>
        @endif

        <p>Si necesita modificar o cancelar su reserva, por favor contáctenos o utilice la sección de reservas en nuestro sitio.</p>

        <div class="firma">
            <p>Atentamente,</p>
            <p><strong>Restaurante</strong></p>
        </div>

        <div class="footer">
            Este es un mensaje generado automáticamente. Por favor, no responda a este correo.
        </div>
    </div>
</body>

</html>
