<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Notificación de Pago Fallido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
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
            border-left: 6px solid #e74c3c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #e74c3c;
            margin-bottom: 20px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
        }

        .detalle {
            background-color: #fef0ef;
            padding: 12px;
            border-left: 4px solid #e74c3c;
            margin: 20px 0;
        }

        .firma {
            margin-top: 30px;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Notificación de Pago Fallido</h2>

        <p>Estimado/a {{ $reserva->nombre_cliente }},</p>

        <p>Lamentamos informarle que el pago asociado a su reserva no ha sido validado correctamente en nuestro sistema.</p>

        <div class="detalle">
            <p><strong>Reserva:</strong> #{{ $reserva->id }}</p>
            <p><strong>Fecha:</strong> {{ $reserva->fecha_formateada }}</p>
            <p><strong>Hora:</strong> {{ $reserva->hora_formateada }}</p>
            <p><strong>Mesa:</strong> {{ optional($reserva->mesa)->numero ?? 'Sin asignar' }}</p>
            <p><strong>Correo registrado:</strong> {{ $reserva->email ?? 'No registrado' }}</p>
        </div>

        <p>Esto puede deberse a un error en el procesamiento o a una validación fallida. Le recomendamos verificar su comprobante de pago o contactar con nosotros para mayor información.</p>

        <p>Si desea reintentar el pago o necesita asistencia, por favor comuníquese con el restaurante.</p>

        <div class="firma">
            <p>Atentamente,</p>
            <p><strong>Restaurante</strong></p>
        </div>

        <div class="footer">
            Este mensaje ha sido generado automáticamente. Por favor, no responda a este correo.
        </div>
    </div>
</body>

</html>
