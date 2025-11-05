<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reserva en Revisión</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f8fb; color:#333; }
        .card { background:#fff; max-width:640px; margin:24px auto; border-radius:12px; padding:24px; box-shadow:0 4px 12px rgba(0,0,0,0.06); }
        .btn { display:inline-block; padding:10px 16px; border-radius:8px; text-decoration:none; }
        .btn-primary { background:#0d6efd; color:#fff; }
        .muted { color:#666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>¡Gracias por tu reserva!</h2>
        <p>Hola {{ $reserva->nombre_cliente }},</p>
        <p>Hemos recibido tu solicitud de reserva y <strong>está en proceso de revisión</strong>. Te enviaremos un correo de confirmación cuando sea validada por nuestro equipo.</p>

        <h3>Resumen</h3>
        <ul>
            <li><strong>Código:</strong> R{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}</li>
            <li><strong>Fecha:</strong> {{ $reserva->fecha_reserva }}</li>
            <li><strong>Hora:</strong> {{ $reserva->hora_reserva }}</li>
            <li><strong>Personas:</strong> {{ $reserva->numero_personas }}</li>
            <li><strong>Mesa:</strong> {{ $reserva->mesa ? ('Mesa ' . $reserva->mesa->numero) : 'Sin asignar' }}</li>
        </ul>

        <p class="muted">Si necesitas cambiar o cancelar tu reserva, contáctanos respondiendo a este correo.</p>

        <p>
            <a class="btn btn-primary" href="{{ route('reservas.consultar') }}">Consultar mi reserva</a>
        </p>

        <p class="muted">Saludos,<br>UMAMI Restaurante</p>
    </div>
</body>
</html>
