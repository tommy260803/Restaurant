<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MiActa - Comprobante de Pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 4rem 0;
            background-color: white;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: hsl(208, 88%, 50%);
            padding: 4rem 4.5rem;
            text-align: center;
            margin: 0 12rem;
            border-radius: 2rem;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-bottom: 1rem;
        }

        .title {
            color: white;
            font-size: 24px;
            margin: 0 0 1rem;
        }

        .text {
            color: white;
            font-size: 16px;
            margin-bottom: 1.5rem;
        }

        .instruction {
            color: white;
            font-size: 15px;
            margin-bottom: 2rem;
        }

        .footer {
            color: white;
            font-size: 13px;
            margin-top: 3rem;
        }

        a {
            color: white;
            font-weight: bold;
            text-decoration: underline;
        }

        @media only screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 2rem;
                border-radius: 1rem;
            }
        }

        @media only screen and (max-width: 480px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .title {
                font-size: 20px;
            }

            .text, .instruction {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://drive.google.com/uc?export=view&id=1HQ8YPZbHXfU6MaJ06W4T1_M033W8gyGA" alt="Logo de MiActa" class="logo">

        <h1 class="title">¡Gracias por tu pago!</h1>

        <p class="text">
            Estimado(a) {{ $pago->nombre ?? 'usuario' }}, hemos procesado tu pago correctamente en <strong>MiActa</strong>.
        </p>

        <p class="instruction">
            En este correo te enviamos el comprobante correspondiente como archivo adjunto en formato PDF.
        </p>

        @if(isset($url_pdf))
        <p class="instruction">
            También puedes <a href="{{ $url_pdf }}" target="_blank">descargar el comprobante aquí</a>.
        </p>
        @endif

        <p class="footer">
            Si tienes alguna duda, no dudes en contactarnos. <br>
            © {{ date('Y') }} MiActa. Todos los derechos reservados.
        </p>
    </div>
</body>
</html>
