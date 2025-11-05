<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <style>
        body { background-color: #f6f8fb; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3"><i class="ri-file-text-line"></i> Detalle de Reserva</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('reserva.pdf', $reserva->id) }}" class="btn btn-outline-secondary"><i class="ri-file-pdf-line"></i> PDF</a>
                <form action="{{ route('reserva.reenviar-email', $reserva->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-primary"><i class="ri-send-plane-line"></i> Reenviar Email</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div><strong>Código</strong>: {{ $reserva->codigo_confirmacion }}</div>
                        <div><strong>Cliente</strong>: {{ $reserva->nombre_cliente }}</div>
                        <div><strong>Teléfono</strong>: {{ $reserva->telefono }}</div>
                        <div><strong>Email</strong>: {{ $reserva->email ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div><strong>Fecha</strong>: {{ $reserva->fecha_reserva }}</div>
                        <div><strong>Hora</strong>: {{ $reserva->hora_reserva }}</div>
                        <div><strong>Personas</strong>: {{ $reserva->numero_personas }}</div>
                        <div><strong>Mesa</strong>: {{ $reserva->mesa ? ('Mesa ' . $reserva->mesa->numero) : 'Sin asignar' }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($reserva->platos && $reserva->platos->count())
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Pre-órdenes</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Plato</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($reserva->platos as $p)
                                    @php $sub = $p->pivot->cantidad * $p->pivot->precio; $total += $sub; @endphp
                                    <tr>
                                        <td>{{ $p->nombre }}</td>
                                        <td class="text-center">{{ $p->pivot->cantidad }}</td>
                                        <td class="text-end">S/ {{ number_format($p->pivot->precio, 2) }}</td>
                                        <td class="text-end">S/ {{ number_format($sub, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>S/ {{ number_format($total, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('reservas.consultar') }}" class="btn btn-outline-secondary"><i class="ri-arrow-left-line"></i> Volver</a>
            <a href="{{ route('login') }}" class="btn btn-outline-dark"><i class="ri-login-circle-line"></i> Iniciar sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
