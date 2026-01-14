<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>¡Tu pedido ha sido confirmado! 🍽️</h2>

    <p>Hola <strong>{{ $pedido->nombre_cliente }}</strong>,</p>

    <p>
        Tu pedido <strong>#{{ $pedido->id }}</strong> ha sido confirmado correctamente.
        En breve será enviado a cocina.
    </p>

    <p><strong>Dirección:</strong> {{ $pedido->direccion_entrega }}</p>
    <p><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>

    <hr>

    <h4>Detalle del pedido:</h4>
    <ul>
        @foreach($pedido->platos as $item)
            <li>
                {{ $item->cantidad }} x {{ $item->plato->nombre }}
                - S/ {{ number_format($item->precio, 2) }}
            </li>
        @endforeach
    </ul>

    <p><strong>Total:</strong>
        S/ {{ $pedido->platos->sum(fn($i) => $i->precio * $i->cantidad) }}
    </p>

    <p>Gracias por tu preferencia ❤️</p>
</body>
</html>
