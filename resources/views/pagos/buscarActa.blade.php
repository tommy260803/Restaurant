@extends('layouts.plantillaPago')

@section('contenido')
    <div class="h-100 d-flex flex-column justify-content-between">
        <!-- Título -->
        <div>
            <h1 class="h4 mb-2 text-primary fw-bold">
                <i class="bi bi-file-earmark-text me-2"></i>
                Tipos de Copias certificadas de Acta
            </h1>
            <p class="text-muted mb-3">Servicio en Línea</p>

            <!-- Info del usuario -->
            <div class="mb-4">
                <h5 class="text-dark"><i class="bi bi-person-circle me-2"></i>Estimado Usuario:</h5>
                <p class="text-muted mb-0">¿Qué tipo de copia certificada de Acta desea generar?</p>
            </div>

            <!-- Paso proceso -->
            <div class="d-flex bg-light border rounded p-3 mb-4 align-items-start">
                <div class="me-3 text-primary fs-4">
                    <i class="bi bi-ui-checks-grid"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Tipo de Acta</h6>
                    <small class="text-muted mb-1 d-block">Paso 2 de 5</small>
                    <p class="mb-0 text-muted small">Seleccione el tipo de acta que desea buscar.</p>
                </div>
            </div>

            <!-- Indicador dinámico del tipo de acta -->
            <div id="mensajeTipoActa" class="alert alert-primary d-flex align-items-center fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div id="textoTipoActa">
                    Actualmente está llenando el formulario de <strong>Nacimiento</strong>.
                </div>
            </div>

            <!-- Botones tipo de acta -->
            <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                <label class="text-white bg-primary p-3 rounded text-center tipo-acta"
                    style="width: 150px; cursor: pointer;">
                    <input type="radio" name="tipo_acta" value="nacimiento" hidden {{ old('tipo_acta', request()->input('tipo_acta', 'nacimiento')) == 'nacimiento' ? 'checked' : '' }}>
                    <img src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/bb.svg"
                        class="mb-2" style="height: 40px;" alt="Nacimiento">
                    <div class="fw-bold">Nacimiento</div>
                </label>

                <label class="text-white bg-primary p-3 rounded text-center tipo-acta"
                    style="width: 150px; cursor: pointer;">
                    <input type="radio" name="tipo_acta" value="matrimonio" hidden {{ old('tipo_acta', request()->input('tipo_acta')) == 'matrimonio' ? 'checked' : '' }}>  
                    <img src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/matri.svg"
                        class="mb-2" style="height: 40px;" alt="Matrimonio">
                    <div class="fw-bold">Matrimonio</div>
                </label>

                <label class="text-white bg-primary p-3 rounded text-center tipo-acta"
                    style="width: 150px; cursor: pointer;">
                    <input type="radio" name="tipo_acta" value="defuncion" hidden {{ old('tipo_acta', request()->input('tipo_acta')) == 'defuncion' ? 'checked' : '' }}>
                    <img src="https://apps.reniec.gob.pe/actascertificadas/resources-1.0/application/img/defucion.svg"
                        class="mb-2" style="height: 40px;" alt="Defunción">
                    <div class="fw-bold">Defunción</div>
                </label>
            </div>

            <!-- Formularios -->
            <div id="formulario-contenido">
                {{-- Formulario Nacimiento --}}
                <form action="{{ route('buscar.nacimiento') }}" method="POST" id="form-nacimiento" class="formulario-acta">
                    @csrf
                    <input type="hidden" name="tipo_acta" value="nacimiento">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-calendar-date"></i></span>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_paterno" class="form-control"
                                    placeholder="Apellido paterno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_materno" class="form-control"
                                    placeholder="Apellido materno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="nombre" class="form-control" placeholder="Nombres" required>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Formulario Matrimonio --}}
                <form action="{{ route('buscar.matrimonio') }}" method="POST" id="form-matrimonio"
                    class="formulario-acta d-none">
                    @csrf
                    <input type="hidden" name="tipo_acta" value="matrimonio">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-calendar-date"></i></span>
                                <input type="date" name="fecha_matrimonio" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <h6 class="text-primary">Cónyuge 1</h6>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_paterno_c1" class="form-control"
                                    placeholder="Apellido paterno" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_materno_c1" class="form-control"
                                    placeholder="Apellido materno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Cónyuge 2</h6>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_paterno_c2" class="form-control"
                                    placeholder="Apellido paterno" required>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_materno_c2" class="form-control"
                                    placeholder="Apellido materno" required>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Formulario Defunción --}}
                <form action="{{ route('buscar.defuncion') }}" method="POST" id="form-defuncion"
                    class="formulario-acta d-none">
                    @csrf
                    <input type="hidden" name="tipo_acta" value="defuncion">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-calendar-date"></i></span>
                                <input type="date" name="fecha_defuncion" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_paterno" class="form-control"
                                    placeholder="Apellido paterno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i
                                        class="bi bi-person-vcard"></i></span>
                                <input type="text" name="apellido_materno" class="form-control"
                                    placeholder="Apellido materno" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="nombre" class="form-control" placeholder="Nombres"
                                    required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('redirectLogin') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Salir
                </a>
                <button type="button" id="btnBuscar" class="btn btn-primary" onclick="mostrarLoaderYContinuar()">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
    </div>

    <script>
        const radios = document.querySelectorAll('input[name="tipo_acta"]');
        const formularios = {
            nacimiento: document.getElementById('form-nacimiento'),
            matrimonio: document.getElementById('form-matrimonio'),
            defuncion: document.getElementById('form-defuncion'),
        };

        const mensaje = document.getElementById('mensajeTipoActa');
        const texto = document.getElementById('textoTipoActa');

        const nombresBonitos = {
            nacimiento: "Nacimiento",
            matrimonio: "Matrimonio",
            defuncion: "Defunción"
        };

        function mostrarFormulario(tipo) {
            Object.values(formularios).forEach(f => f.classList.add('d-none'));
            formularios[tipo].classList.remove('d-none');
            texto.innerHTML = `Actualmente está llenando el formulario de <strong>${nombresBonitos[tipo]}</strong>.`;
        }

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                mostrarFormulario(radio.value);
            });
        }); 

        document.addEventListener("DOMContentLoaded", () => {
            const oldTipo = "{{ old('tipo_acta') }}";

            if (oldTipo !== '') {
                const radio = document.querySelector(`input[name="tipo_acta"][value="${oldTipo}"]`);
                if (radio) {
                    radio.checked = true;
                    mostrarFormulario(oldTipo);
                }
            } else {
                const inicial = document.querySelector('input[name="tipo_acta"]:checked').value;
                mostrarFormulario(inicial);
            }
        });

        function mostrarLoaderYContinuar() {
            const loader = document.getElementById("googleLoader");
            const progress = loader?.querySelector('.loader-progress');

            if (loader && progress) {
                loader.style.display = "block";
                progress.style.width = "0%";
                progress.style.animation = "none";
                void progress.offsetWidth;
                progress.style.animation = "loadBar 2s linear forwards";
            }

            setTimeout(() => {
                const seleccionado = document.querySelector('input[name="tipo_acta"]:checked').value;
                formularios[seleccionado].submit();
            }, 2000);
        }

        console.log(document.querySelector('input[name="tipo_acta"]:checked')?.value);
    </script>
@endsection
