<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MiActa - Código de Recuperación</title>
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

        .code-box {
            background-color: white;
            color: hsl(208, 88%, 50%);
            display: inline-block;
            padding: 1rem 2rem;
            font-size: 28px;
            font-weight: bold;
            border-radius: 8px;
            letter-spacing: 2px;
            margin-bottom: 2rem;
        }

        .footer {
            color: white;
            font-size: 13px;
            margin-top: 3rem;
        }

        /* Responsive */
        @media only screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 2rem;
                border-radius: 1rem;
            }

            .code-box {
                font-size: 24px;
                padding: 0.8rem 1.5rem;
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

            .code-box {
                font-size: 22px;
                padding: 0.7rem 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="https://drive.google.com/uc?export=view&id=1HQ8YPZbHXfU6MaJ06W4T1_M033W8gyGA" alt="Logo de MiActa" class="logo">

        <h1 class="title">¡Hola!</h1>

        <p class="text">
            Hemos recibido una solicitud para recuperar tu cuenta en <strong>MiActa</strong>.
        </p>

        <p class="instruction">
            Este es tu <strong>código de verificación</strong>. Úsalo para confirmar tu identidad y continuar con el proceso de recuperación:
        </p>

        <div class="code-box">
            {{ $codigo }}
        </div>

        <p class="footer">
            Si no solicitaste este código, puedes ignorar este mensaje con seguridad.
        </p>
    </div>
</body>
</html>