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
        <form action="{{ route('recovery.verifyCode') }}" method="POST">
            @csrf
            <div style="gap: 0px;" class="login__content grid">
                <h6 class="text-[13px] text-black mb-6">Ingrese el código que se ha enviado al correo de recuperación <strong>{{ $gmail }}</strong></h6>
                <div class="grid grid-cols-7 gap-[0.5rem]">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="login__box">
                            <input maxlength="1" inputmode="numeric" pattern="[0-9]*" oninput="handleInput(this)"
                                class="w-full text-[20px] text-center login__input" style="padding: 1rem 0.5rem;" name="code[]">
                        </div>
                    @endfor
                    <div class="flex justify-center items-center">
                        <i style="font-size: 1.5rem; color: hsl(220, 15%, 66%);" class="ri-lock-password-fill"></i>
                    </div>
                </div>

                <div style="margin-bottom: 10px" class="error-container">
                    @error('code')
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
                <button type="submit" class="h-10 flex justify-center items-center login__button">Verificar</button>
            </div>
        </form>
    </div>

    <script>
        const inputs = document.querySelectorAll('.login__input');

        function handleInput(el) {
            el.value = el.value.replace(/[^0-9]/g, '').slice(0, 1);

            if (el.value.length === 1) {
                const next = el.closest('.login__box').nextElementSibling;
                if (next) {
                    const input = next.querySelector('input');
                    if (input) input.focus();
                }
            }
        }

        inputs.forEach((input, i) => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && i > 0) {
                    inputs[i - 1].focus();
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".error-text").forEach(elem => {
                if (elem.textContent.trim() !== '') {
                    setTimeout(() => {
                        elem.classList.add("active");
                    }, 50);
                }
            });

            inputs.forEach(input => {
                input.addEventListener("focus", function () {
                    const errorText = document.querySelector('.error-container .error-text');
                    if (errorText && errorText.classList.contains('active')) {
                        errorText.classList.remove('active');
                        errorText.classList.add('exit');

                        setTimeout(() => {
                            errorText.innerHTML = '&nbsp;';
                            errorText.classList.remove('exit');
                        }, 300);
                    }
                });
            });
        });
    </script>
@endsection