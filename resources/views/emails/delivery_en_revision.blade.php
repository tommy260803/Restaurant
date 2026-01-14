<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Pedido recibido 🍽️</h2>

    <p>Hola <strong>{{ $pedido->nombre_cliente }}</strong>,</p>

    <p>
        Hemos recibido el comprobante de pago de tu pedido
        <strong>#{{ $pedido->id }}</strong>.
    </p>

    <p>
        En este momento tu pedido se encuentra <strong>en revisión</strong>
        por nuestro equipo.
    </p>

    <p>
        Te avisaremos apenas sea confirmado para iniciar la preparación.
    </p>

    <hr>

    <p>
        📍 Dirección de entrega:<br>
        {{ $pedido->direccion_entrega }}
    </p>

    <p>Gracias por tu preferencia 🙌</p>
</body>
</html>
