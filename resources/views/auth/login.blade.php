@extends('auth.plantillaLogin')

@section('contenido-login')
    <h1 class="text-center mb-3 d-flex align-items-center justify-content-center gap-2" style="color: var(--peru-red); font-weight: 700; font-size: 1.5rem;">
        <i class="ri-shield-user-line" style="font-size: 1.8rem;"></i>
        Inicia sesión en tu cuenta
    </h1>

    <div class="text-center mb-4">
        <img src="/img/resta.png" alt="Logo Restaurante" style="height: 80px; width: 80px; object-fit: contain;">
    </div>

    <form id="login__form" action="{{ route('login') }}" method="POST">
        @csrf

        {{-- Correo --}}
        <div class="mb-3">
            <label for="email" class="form-label" style="font-weight: 500; color: #4b5563;">Correo Electrónico</label>
            <div class="position-relative">
                <input type="text" id="email" name="email_mi_acta" 
                    value="{{ old('email_mi_acta') }}"
                    class="form-control ps-5"
                    style="border-radius: 10px; padding: 0.7rem 0.7rem 0.7rem 2.5rem;"
                    placeholder="ejemplo@restaurante.com">
                <i class="ri-mail-fill position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
            </div>
            @error('email_mi_acta')
                <p class="text-danger small mt-1 d-flex align-items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label" style="font-weight: 500; color: #4b5563;">Contraseña</label>
            <div class="position-relative">
                <input type="password" id="password" name="contrasena" 
                    class="form-control ps-5"
                    style="border-radius: 10px; padding: 0.7rem 0.7rem 0.7rem 2.5rem;"
                    placeholder="********">
                <i class="ri-lock-2-fill position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
            </div>
            @error('contrasena')
                <p class="text-danger small mt-1 d-flex align-items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Link recuperar --}}
        <div class="text-end mb-3">
            <a href="{{ route('recovery.step1') }}" class="login-link" style="font-size: 0.9rem; color: var(--peru-red); text-decoration: none;">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn w-100 text-white d-inline-flex align-items-center justify-content-center gap-2"
            style="background: var(--peru-red); border-radius: 10px; padding: 0.7rem; font-weight: 600; box-shadow: 0 8px 22px rgba(185,28,28,0.18);">
            Ingresar
        </button>
    </form>

    <div class="text-center mt-4" style="color: #6b7280; font-size: 0.9rem;">
        <p class="mb-3">¿Eres cliente? Haz tu reserva o consulta tu reserva sin necesidad de crear una cuenta.</p>
        <div class="d-flex flex-column align-items-center gap-2">
            <a href="{{ route('reservas.create') }}" 
                class="btn text-white d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm"
                style="background: var(--peru-red); border-radius: 10px; font-weight: 600; max-width: 300px;">
                <i class="ri-calendar-check-line"></i>
                Reservar Mesa
            </a>
            <a href="{{ route('reservas.consultar') }}" 
                class="btn btn-outline-soft d-inline-flex align-items-center gap-2 px-4 py-2"
                style="max-width: 300px; font-weight: 600; border-color: var(--peru-red); color: var(--peru-red);">
                <i class="ri-search-eye-line"></i>
                Consultar mi Reserva
            </a>

            {{-- 🔥 DELIVERY --}}
    <a href="{{ route('delivery.create') }}" 
        class="btn text-white d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm"
        style="background: #16a34a; border-radius: 10px; font-weight: 600; max-width: 300px;">
        <i class="ri-e-bike-2-line"></i>
        Pide tu Delivery
    </a>
    <a href="{{ route('delivery.consultar') }}" 
        class="btn text-white d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm"
        style="background: #168ea3; border-radius: 10px; font-weight: 600; max-width: 300px;">
        <i class="ri-e-bike-2-line"></i>
        Consulta tu Delivery
    </a>
        </div>
    </div>
@endsection
