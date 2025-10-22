@extends('auth.plantillaLogin')

@section('contenido-login')
<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h1 class="text-2xl font-extrabold text-center text-red-700 mb-6 flex items-center justify-center gap-2">
        <i class="ri-shield-user-line text-3xl"></i>
        Inicia sesión en tu cuenta
    </h1>

    <div class="flex justify-center mb-6">
        <img src="/img/resta.png" alt="Logo Restaurante" class="h-20 w-20 object-contain">
    </div>

    <form id="login__form" action="{{ route('identificacion') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Correo --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-600">Correo Umami</label>
            <div class="relative mt-1">
                <input type="text" id="email" name="email_mi_acta" 
                    value="{{ old('email_mi_acta') }}"
                    class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring focus:ring-red-200 transition p-3 pl-10"
                    placeholder="Ingresa tu correo">
                <i class="ri-mail-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            @error('email_mi_acta')
                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Contraseña</label>
            <div class="relative mt-1">
                <input type="password" id="password" name="password" 
                    value="{{ old('password') }}"
                    class="w-full rounded-xl border-gray-300 focus:border-red-600 focus:ring focus:ring-red-200 transition p-3 pl-10"
                    placeholder="********">
                <i class="ri-lock-2-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            @error('password')
                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Link recuperar --}}
        <div class="flex justify-end">
            <a href="{{ route('recovery.step1') }}" class="text-sm text-red-700 hover:underline">¿Olvidaste tu contraseña?</a>
        </div>

        {{-- Botón --}}
        <button type="submit" 
            class="w-full bg-gradient-to-r from-red-700 to-yellow-500 text-white py-3 rounded-xl font-semibold shadow-md hover:scale-[1.02] transition">
            Ingresar
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
        Un saber diferente a los demás.<br>
        <a href="{{ route('pagos.registrarActa') }}" 
            class="inline-block mt-2 px-4 py-2 bg-yellow-500 text-white font-semibold rounded-xl hover:bg-yellow-600 transition">
            Reservar
        </a>
    </p>
</div>
@endsection
