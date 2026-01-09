<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Pedido - Delivery</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        :root {
            --peru-red: #b91c1c;
        }
        
        body {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .consultar-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .header-section {
            text-center;
            margin-bottom: 2.5rem;
        }
        
        .header-section i {
            font-size: 4rem;
            color: var(--peru-red);
            display: block;
            margin-bottom: 1rem;
        }
        
        .header-section h1 {
            color: var(--peru-red);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .header-section p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        
        .card-body {
            padding: 3rem;
        }
        
        .form-control:focus {
            border-color: var(--peru-red);
            box-shadow: 0 0 0 0.2rem rgba(185,28,28,0.15);
        }
        
        .btn-primary {
            background: var(--peru-red);
            border: none;
            border-radius: 10px;
            padding: 0.9rem;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 8px 22px rgba(185,28,28,0.18);
        }
        
        .btn-primary:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(185,28,28,0.25);
        }
        
        .info-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
        }
        
        .btn-outline-success {
            border-color: #16a34a;
            color: #16a34a;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .btn-outline-success:hover {
            background: #16a34a;
            border-color: #16a34a;
        }
        
        .logo-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-header img {
            height: 70px;
            width: 70px;
        }
        
        .logo-header h3 {
            color: var(--peru-red);
            font-weight: 700;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container consultar-container">
        <!-- Logo -->
        <div class="logo-header">
            <img src="/img/resta.png" alt="Logo">
            <h3>Sabor Peruano</h3>
        </div>

        <!-- Header -->
        <div class="header-section">
            <i class="ri-search-2-line"></i>
            <h1>Consultar Mi Pedido</h1>
            <p>Ingresa tu email o número de pedido para ver el estado</p>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('pedido_id'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="ri-information-line"></i> 
                <strong>Tu número de pedido es: #{{ session('pedido_id') }}</strong><br>
                Guarda este número para consultar tu pedido.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulario -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('delivery.buscar') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5">Buscar por:</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="background: var(--peru-red); color: white; border: none;">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" 
                                   name="busqueda" 
                                   class="form-control @error('busqueda') is-invalid @enderror" 
                                   placeholder="Email o Número de Pedido"
                                   value="{{ old('busqueda') }}"
                                   required
                                   autofocus>
                            @error('busqueda')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="ri-information-line"></i> 
                            Ejemplo: tunombre@email.com o #12345
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-search-line"></i> Buscar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info -->
        <div class="info-card mb-4">
            <h6 class="fw-bold mb-3" style="color: var(--peru-red);">
                <i class="ri-question-line"></i> ¿Necesitas ayuda?
            </h6>
            <p class="mb-2">
                <i class="ri-mail-line"></i> 
                Email: <a href="mailto:delivery@restaurant.com" style="color: var(--peru-red);">delivery@restaurant.com</a>
            </p>
            <p class="mb-0">
                <i class="ri-phone-line"></i> 
                Teléfono: <a href="tel:+51999888777" style="color: var(--peru-red);">999 888 777</a>
            </p>
        </div>

        <!-- Botones -->
        <div class="d-grid gap-2">
            <a href="{{ route('delivery.create') }}" class="btn btn-outline-success btn-lg">
                <i class="ri-add-circle-line"></i> Hacer Nuevo Pedido
            </a>
            <a href="{{ route('presentacion') }}" class="btn btn-outline-secondary">
                <i class="ri-home-line"></i> Volver al Inicio
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>