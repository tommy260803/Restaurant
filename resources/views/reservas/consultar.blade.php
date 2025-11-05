<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar mi Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <style>
        body { background-color: #f6f8fb; }
    </style>
    @php /* Página pública independiente, sin layout, no usa Vite */ @endphp
    </head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 m-0"><i class="ri-search-line"></i> Consultar mi Reserva</h1>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary"><i class="ri-login-circle-line"></i> Iniciar sesión</a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ri-error-warning-line"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('reservas.consultar.buscar') }}" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">Código o ID de reserva</label>
                        <input type="text" name="codigo" class="form-control" placeholder="Ej: R000123 o 123" value="{{ old('codigo') }}" required>
                        <div class="form-text">Si no recuerdas el código, puedes usar el ID mostrado en el comprobante.</div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100"><i class="ri-search-eye-line"></i> Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
