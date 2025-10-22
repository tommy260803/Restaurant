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
        <h2>Confirmación de Pago Exitoso</h2>

        <p>Estimado/a usuario/a,</p>

        <p>Le informamos que su pago ha sido validado correctamente y el trámite correspondiente a su acta ha sido
            registrado en nuestro sistema.</p>

        <div class="resaltado">
            <p><strong>Número de pago:</strong> {{ $pago->id_pago }}</p>
            <p><strong>Tipo de acta:</strong> {{ strtoupper(str_replace('_', ' ', $pago->tipo_acta)) }}</p>
            <p><strong>Correo registrado:</strong> {{ $pago->Correo }}</p>
        </div>

        <p>En este correo encontrará adjunto el archivo PDF generado con la información del acta correspondiente.</p>

        <p>Por favor, conserve este documento para cualquier trámite futuro. Si tiene alguna consulta o necesita
            asistencia adicional, no dude en comunicarse con nuestra oficina.</p>

        <div class="firma">
            <p>Atentamente,</p>
            <p><strong>Registro Civil</strong><br>Municipalidad / Entidad correspondiente</p>
        </div>

        <div class="footer">
            Este es un mensaje generado automáticamente. Por favor, no responda a este correo.
        </div>
    </div>
</body>

</html>
