<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RestaurantePro | Acceder y Registrar</title>

    <!-- Bootstrap (estilos) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remixicon (opcional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

    <!-- Tu CSS (si existe) -->
    <link rel="stylesheet" href="/css/login.css">

    <style>
        :root{
            --peru-red: #b91c1c;
            --peru-gold: #facc15;
        }

        /* Layout principal: dos columnas en pantallas md+, una columna en móvil */
        .auth-wrapper{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            width: min(1100px, 95%);
            margin: 3rem auto;
            align-items: center;
        }
        @media (max-width: 767px){
            .auth-wrapper{
                grid-template-columns: 1fr;
                margin: 1.5rem;
            }
        }

        /* Zona izquierda: imagen decorativa */
        .auth-image{
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            min-height: 520px;
            box-shadow: 0 18px 40px rgba(0,0,0,0.12);
        }

        .auth-image img.bg{
            width:100%;
            height:100%;
            object-fit: cover;
            display:block;
            transform-origin:center;
            transition: transform .6s ease;
            filter: saturate(1.05) contrast(1.02);
        }
        .auth-image:hover img.bg{ transform: scale(1.03) translateY(-6px); }

        /* overlay cálido (para contraste con texto blanco si se usa) */
        .auth-image::after{
            content:'';
            position:absolute;
            inset:0;
            background: linear-gradient(180deg, rgba(185,28,28,0.18), rgba(250,250,250,0.05));
            pointer-events:none;
        }

        /* Imágenes flotantes (pequeñas) - usan <img>, no emojis */
        .float-img{
            position:absolute;
            width:76px;
            height:76px;
            border-radius:12px;
            object-fit:cover;
            box-shadow: 0 10px 30px rgba(0,0,0,0.18);
            z-index: 4;
            animation: gentleFloat 8s ease-in-out infinite;
            pointer-events: none;
            border: 3px solid rgba(255,255,255,0.85);
            background: #fff;
        }

        .float-1 { top: 8%; left: 10%; animation-delay: 0s; }
        .float-2 { top: 22%; right: 8%; animation-delay: 2s; }
        .float-3 { bottom: 22%; left: 12%; animation-delay: 4s; }
        .float-4 { bottom: 10%; right: 14%; animation-delay: 6s; }

        @keyframes gentleFloat{
            0%{ transform: translateY(0) rotate(0deg); opacity:1; }
            50%{ transform: translateY(-14px) rotate(6deg); opacity:0.95; }
            100%{ transform: translateY(0) rotate(0deg); opacity:1; }
        }

        /* Zona derecha: tarjeta de login con borde bonito (gradiente difuminado) */
        .card-outer{
            position: relative;
            border-radius: 18px;
            padding: 8px; /* espacio para el borde difuminado */
        }

        /* pseudo-elemento que crea el borde gradiente difuminado */
        .card-outer::before{
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(185,28,28,0.95), rgba(250,204,21,0.95));
            filter: blur(8px);
            opacity: 0.22;
            z-index: 0;
            transform: translateZ(0);
            pointer-events: none;
        }

        /* tarjeta real por encima del efecto */
        .login-card{
            position: relative;
            z-index: 2;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(10,10,10,0.08);
            padding: 2.2rem;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .brand-row{
            display:flex;
            align-items:center;
            gap:.85rem;
            margin-bottom: .7rem;
        }

        .brand-logo{
            width:56px;
            height:56px;
            object-fit:contain;
            border-radius:10px;
            background: #fff;
            padding:6px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .login-title{
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--peru-red);
            margin:0;
        }
        .login-sub{
            color:#6b7280;
            margin-bottom:1.05rem;
            font-size:.97rem;
        }

        /* botones */
        .btn-peru{
            background: linear-gradient(90deg,var(--peru-red),var(--peru-gold));
            color:#fff;
            border: none;
            border-radius: 10px;
            padding: .65rem .9rem;
            font-weight:600;
            box-shadow: 0 8px 22px rgba(185,28,28,0.12);
        }
        .btn-outline-soft{
            border-radius:10px;
            border:1px solid rgba(0,0,0,0.06);
            padding: .5rem .85rem;
        }

        /* pequeño pie */
        .auth-foot {
            margin-top: 0.9rem;
            font-size: .9rem;
            color: #6b7280;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="auth-wrapper">

            <!-- IZQUIERDA: imagen grande + floats (solo visible en md+) -->
            <div class="auth-image d-none d-md-block">
                <!-- Imagen principal (reemplaza por una imagen peruana si deseas) -->
                <img class="bg" src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80" alt="Comida peruana">

                <!-- pequeñas imágenes flotantes (usar solo <img>) -->
                <img class="float-img float-1" src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Ceviche">
                <img class="float-img float-2" src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Pisco">
                <img class="float-img float-3" src="https://images.unsplash.com/photo-1525755662778-989d0524087e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Anticucho">
                <img class="float-img float-4" src="https://images.unsplash.com/photo-1551218808-94e220e084d2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Choclo">

                <!-- opcional: texto de marca sobre la imagen (si deseas) -->
                <!-- <div class="position-absolute bottom-0 start-0 p-4 text-white">
                    <h3 class="mb-0">Sabor peruano</h3>
                    <p class="mb-0 small">Cocina tradicional & moderna</p>
                </div> -->
            </div>

            <!-- DERECHA: tarjeta de login -->
            <div class="d-flex align-items-center justify-content-center">
                <div class="card-outer w-100" style="max-width:520px;">
                    <div class="login-card">

                        <!-- Marca (logo + nombre) -->
                        <div class="brand-row">
                            <!-- sustituye src por tu logo real -->
                            <img src="/images/logo.png" alt="logo" class="brand-logo" onerror="this.style.display='none'">
                            <div>
                                <h2 class="login-title">Bienvenido || Perú</h2>
                                <div class="login-sub">El sabor peruano, ahora digital 🇵🇪</div>
                            </div>
                        </div>

                        <!-- Tu contenido (form login / register) -->
                        @yield('contenido-login')

                        <!-- Pie con enlaces (sin rutas nuevas) -->
                        <div class="auth-foot">
                            ¿Necesitas ayuda? <a href="#" class="login-link">Contactar soporte</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- JS (Bootstrap y tus scripts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/login.js"></script>
    <script src="/js/buscarActa.js"></script>
</body>
</html>
