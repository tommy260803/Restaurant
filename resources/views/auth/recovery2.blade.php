@extends('auth.recovery')
@section('contenido')
    <!-- Columna Izquierda -->
    <div class="py-10 pl-10 pr-5"> 
        <div class="contenedor__logo w-auto">
            <div class="logo__wrapper">
                <img id="imagenA" class="logo__icon mostrar" src="{{ asset('img/Logo_Imagen_FA.png') }}" alt="Logo A">
                <img id="imagenB" class="logo__icon" src="{{ asset('img/Logo_Imagen_FB.png') }}" alt="Logo B">
            </div>
        </div>
        <h1 class="text-black text-3xl font-bold text-center mb-4">Recuperación de la cuenta</h1>
        <div class="inline-flex items-center cursor-pointer rounded-[15px] p-1 hover:bg-blue-100 transition duration-300 ease-in-out border border-black">
            <i class="text-black border-black h-[24px] w-[24px] text-[17px] border-[3px] rounded-full flex items-center justify-center">
                <span class="relative top-[2px]">
                    <i class="ri-user-fill"></i>
                </span>
            </i>
            <strong class="text-[13px] ml-2 text-black">{{ $email_mi_acta }}</strong>
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="flex flex-col justify-center py-10 pr-10 pl-5">
        <form action="{{ route('recovery.sendCode') }}" method="POST">
            @csrf
            <div style="gap: 0px;" class="login__content grid">

                {{-- Correo --}}
                <h6 class="text-[13px] text-black mb-6">Ingrese su correo de recuperación para verificar que sea usted</h6>
                <div class="login__box">
                    <input class="login__input" type="text" id="email" name="email"
                        placeholder=" " value="{{ old('email_mi_acta') }}">
                    <label for="email" class="login__label">Correo Electrónico</label>
                    <i class="ri-mail-fill login__icon"></i>
                </div>

                <div style="margin-bottom: 10px" class="error-container">
                    @error('email')
                        <span class="error-text">
                            <strong>
                                {{ $message }}
                                <i class="ri-error-warning-line"></i>
                            </strong>
                        </span>
                    @else
                        <span class="error-text">&nbsp;</span>
                    @enderror
                </div>
            </div>
            <div style="margin-top: -30px">
                <button type="submit" class="h-10 flex justify-center items-center login__button">Enviar Código</button>                
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".error-text").forEach(elem => {
            if (elem.textContent.trim() !== '') {
                setTimeout(() => {
                    elem.classList.add("active");
                }, 50);
            }
        });

        const inputs = document.querySelectorAll(".login__input");

        inputs.forEach(input => {
            input.addEventListener("focus", function () {
                const errorContainer = this.closest('.login__box').nextElementSibling;
                if (errorContainer && errorContainer.classList.contains('error-container')) {
                    const errorText = errorContainer.querySelector('.error-text');
                    if (errorText && errorText.classList.contains('active')) {
                    errorText.classList.remove('active');
                    errorText.classList.add('exit');

                    setTimeout(() => {
                        errorText.innerHTML = '&nbsp;';
                        errorText.classList.remove('exit');
                    }, 300);
                    }
                }
            });
        });
        });
    </script>
@endsection