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

        <p>Estimado/a usuario/a,</p>

        <p>Lamentamos informarle que el pago que intentó realizar no ha sido validado correctamente en nuestro sistema.
        </p>

        <div class="detalle">
            <p><strong>Número de pago:</strong> {{ $pago->id_pago }}</p>
            <p><strong>Tipo de acta:</strong> {{ strtoupper(str_replace('_', ' ', $pago->tipo_acta)) }}</p>
            <p><strong>Correo registrado:</strong> {{ $pago->Correo }}</p>
        </div>

        <p>Esto puede deberse a un error en el procesamiento o a una validación fallida. Le recomendamos verificar su
            comprobante de pago o contactar con nuestras oficinas para mayor información.</p>

        <p>Una vez regularizado el pago, podrá acceder al documento oficial correspondiente al trámite solicitado.</p>

        <div class="firma">
            <p>Atentamente,</p>
            <p><strong>Registro Civil</strong><br>Municipalidad / Entidad correspondiente</p>
        </div>

        <div class="footer">
            Este mensaje ha sido generado automáticamente. Por favor, no responda a este correo.
        </div>
    </div>
</body>

</html>
