@extends('layouts.plantilla')

@section('contenido')

<style>
    /* ======== Fondo con textura ======== */
    body {
        background: #f8f3eb url("https://www.transparenttextures.com/patterns/paper-fibers.png");
        background-size: cover;
        transition: background 0.4s ease;
    }

    /* ======== Caja principal ======== */
    .restaurant-box {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 18px;
        padding: 45px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.25);
        animation: fadeInUp 0.8s ease-out;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 215, 180, 0.5);
    }

    /* ======== Luz cálida sutil ======== */
    .restaurant-box::before {
        content: "";
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: radial-gradient(rgba(255,200,120,0.45), transparent 70%);
        filter: blur(40px);
        z-index: -1;
        animation: glowMove 6s ease-in-out infinite alternate;
    }

    @keyframes glowMove {
        0% { transform: translate(0,0); }
        100% { transform: translate(-30px,20px); }
    }

    @keyframes fadeInUp {
        from { opacity:0; transform: translateY(35px); }
        to { opacity:1; transform: translateY(0); }
    }

    /* ======== Icono circular ======== */
    .icon-circle-rest {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b1a1a, #6e0e0e);
        color: #ffe8b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: auto;
        margin-bottom: 18px;
        box-shadow: 0 6px 20px rgba(110, 14, 14, 0.4);
        border: 2px solid rgba(255, 200, 120, 0.3);
    }

    .title-restaurant {
        font-family: "Georgia", serif;
        font-weight: bold;
        color: #332424;
        font-size: 2.4rem;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    }

    .subtitle {
        font-size: 1.15rem;
        color: #5a3a1a;
        font-weight: 700;
    }

    ul li { 
        padding-bottom: 8px;
        color: #2a2a2a !important;
        font-weight: 600;
    }

    .welcome-text {
        color: #cbcbcb;
        font-weight: 600;
    }

    .info-text {
        color: #eeeeee;
        font-weight: 600;
    }

    /* ======== Modo oscuro gourmet ======== */
    @media (prefers-color-scheme: dark) {
        body {
            background: #1c1b19 url("https://www.transparenttextures.com/patterns/wood-pattern.png");
        }

        .restaurant-box {
            background: rgba(55, 44, 39, 0.92);
            color: #f2e8ce;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }

        .title-restaurant {
            color: #f0d9a5;
        }

        .subtitle {
            color: #e4c78f;
        }

        .icon-circle-rest {
            background: #963232;
            color: #ffefc2;
        }

        ul li {
            color: #f1e7cf !important;
        }
    }

</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 restaurant-box text-center">

            <div class="icon-circle-rest">
                <i class="bi bi-egg-fried"></i>
            </div>

            <h2 class="title-restaurant">Bienvenido al Sistema</h2>
            <p class="subtitle mb-4">Panel Administrativo - Restaurante</p>

            <div class="mt-3">
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-dark btn-sm">
                    <i class="bi bi-graph-up me-1"></i> Ver reportes
                </a>
            </div>

            <p class="fs-5 mb-3 welcome-text">
                ¡Hola, <strong>{{ $usuario->nombre_usuario }}</strong>!  
                Te damos la bienvenida a la administración del restaurante 🍷✨
            </p>

            <p class="info-text">
                Desde aquí podrás gestionar:
            </p>

            <ul class="text-start d-inline-block">
                <li><i class="bi bi-person-check me-2 text-danger"></i>Control de personal y usuarios</li>
                <li><i class="bi bi-basket3 me-2 text-warning"></i>Inventario e insumos</li>
                <li><i class="bi bi-journal me-2 text-success"></i>Gestión del menú y platos</li>
                <li><i class="bi bi-receipt-cutoff me-2 text-primary"></i>Pedidos y actividad</li>
            </ul>

            <div class="mt-4 small" style="font-weight: 600; color: #4a4a4a;">
                <i class="bi bi-clock-history me-1"></i>
                Último inicio de sesión: <strong>Hoy</strong>
            </div>

        </div>
    </div>
</div>

@endsection
