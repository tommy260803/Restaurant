@extends('auth.plantillaLogin')
@section('contenido-login')
    <!--===== SEARCH ACTA =====-->
    <div class="login__register active">
        <h1 class="login__title">
            <i class="ri-group-line"></i>
            Verificar acta/partida
        </h1>

        <div class="contenedor__logo">
            <div class="logo__wrapper">
                <img id="imagenA" class="logo__icon mostrar" src="img/Logo_Imagen_FA.png" alt="Logo A">
                <img id="imagenB" class="logo__icon" src="img/Logo_Imagen_FB.png" alt="Logo B">
            </div>
        </div>

        <div class="flex justify-center items-center">
            <div style="margin-bottom: 1rem;" class="grid grid-cols-3 h-[80px]">
                <div style="background: hsl(208, 92%, 54%); padding: 0.5rem;" class="border rounded-[20px] w-[150px] block">
                    <div class="flex justify-center">
                        <img style="height: 40px;" src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/bb.svg" alt="Logo bebé">
                    </div>
                    <div class="flex justify-center">
                        <label class="text-white font-bold cursor-pointer">
                            <input type="radio" name="grupo1" value="nacimiento" checked>
                            Nacimiento
                        </label>
                    </div>
                </div>
                <div style="background: hsl(208, 92%, 54%); padding: 0.5rem;" class="border rounded-[20px] w-[150px] block padding-block">
                    <div class="flex justify-center">
                        <img style="height: 40px" src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/matri.svg" alt="Logo bebé">
                    </div>
                    <div class="flex justify-center">
                        <label class="text-white font-bold cursor-pointer">
                            <input type="radio" name="grupo1" value="matrimonio">
                            Matrimonio
                        </label>
                    </div>
                </div>
                <div style="background: hsl(208, 92%, 54%); padding: 0.5rem;" class="border rounded-[20px] w-[150px] block">
                    <div class="flex justify-center">
                        <img style="height: 40px" src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/defucion.svg" alt="Logo bebé">
                    </div>
                    <div class="flex justify-center">
                        <label class="text-white font-bold cursor-pointer">
                            <input type="radio" name="grupo1" value="defuncion">
                            Defución
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!--Formulario de Nacimiento-->
        <div class="login__area">
            <form action="" class="login__form" id="nacimiento">
                <div class="login__content grid">
                    <div class="login__box">
                        <input type="date" placeholder=" "
                            class="login__input" id="fechaNacimiento">
                        <label class="login__label">Fecha de Nacimiento</label>

                        <i style="cursor: pointer; z-index: 3;"
                            class="ri-calendar-todo-line login__icon" id="iconoCalendario"></i>
                    </div>

                    <div class="login__group grid">
                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label class="login__label">Apellido Paterno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label class="login__label">Apellido Materno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>
                    </div>

                    <div class="login__box">
                        <input type="text" required placeholder=" " class="login__input">
                        <label class="login__label">Nombres</label>

                        <i class="ri-mail-fill login__icon"></i>
                    </div>
                </div>
            </form>

            <!--Formulario de Matrimonio-->
            <form action="" class="login__form hidden" id="matrimonio">
                <div class="login__content grid">
                    <div class="grid grid-cols-2">
                        <div class="login__box">
                            <input type="text" placeholder=" " class="login__input"
                                max-length="4" id="añoMatrimonio">
                            <label class="login__label">Año</label>

                            <i style="z-index: 3;" class="ri-calendar-todo-line login__icon"></i>
                        </div>

                        <div class="login__box">
                            <select id="mesMatrimonio" required=" " class="login__input">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <label class="login__label">Mes</label>
                            
                            <i class="ri-calendar-todo-line login__icon"></i>
                        </div>
                    </div>

                    <div class="login__group grid">
                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label class="login__label">Apellido Paterno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label class="login__label">Apellido Materno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>
                    </div>

                    <div class="login__box">
                        <input type="text" required placeholder=" " class="login__input">
                        <label class="login__label">Nombres</label>

                        <i class="ri-mail-fill login__icon"></i>
                    </div>
                </div>
            </form>
            
            <!--Formulario de Defuncion-->
            <form action="" class="login__form hidden" id="defuncion">
                <div class="login__content grid">
                    <div class="login__box">
                        <input type="text" placeholder=" " class="login__input"
                            max-length="4" id="añoDefuncion">
                        <label class="login__label">Año de Defunción</label>

                        <i style="z-index: 3;" class="ri-calendar-todo-line login__icon"></i>
                    </div>

                    <div class="login__group grid">
                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label for="names" class="login__label">Apellido Paterno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="text" required placeholder=" " class="login__input">
                            <label class="login__label">Apellido Materno</label>

                            <i class="ri-id-card-fill login__icon"></i>
                        </div>
                    </div>

                    <div class="login__box">
                        <input type="text" required placeholder=" " class="login__input">
                        <label class="login__label">Nombres</label>

                        <i class="ri-mail-fill login__icon"></i>
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-2 gap-px">
                <!--Salir de la búsqueda-->
                <a href="{{ route('redirectLogin') }}">
                    <button id="loginButtonAccess" style="padding-block: 0.5rem; font-size: 18px;
                        background: white; color: hsl(208, 92%, 54%);
                        border: solid 1px hsl(208, 92%, 54%);" class="login__button">

                        <i style="font-size: 18px; margin-right: 20px;" class="ri-close-large-fill mr-4"></i>
                        Salir
                    </button>
                </a>
                
                <!--Realizar la búsqueda de Acta-->
                <button id="buscarActa" style="padding-block: 0.5rem; font-size: 18px;" type="submit" class="login__button">

                    <i style="font-size: 18px; margin-right: 20px;" class="ri-search-eye-line"></i>Buscar
                </button>
            </div>
        </div>
    </div>

    <script>
    const yearInputs = [document.getElementById('añoMatrimonio'),
        document.getElementById('añoDefuncion')];

    yearInputs.forEach(input => {
        input.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');

        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }

        if (this.value.length === 4) {
            const anio = parseInt(this.value, 10);
            if (anio < 1900 || anio > 2025) {
            this.value = '';
            }
        }
        });
    });
    </script>
@endsection
