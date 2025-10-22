@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

    <div style="max-width: 800px;" class="card card-success mx-auto">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0">EDITAR ALCALDE</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alcalde.update', $alcalde->id_alcalde) }}" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- DATOS PERSONALES --}}
                <h6 class="text-primary border-bottom pb-2 mb-3"><i class="ri-user-3-fill me-2"></i>I. Datos Personales</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" class="form-control"
                            value="{{ old('nombres', $alcalde->persona->nombres) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido_paterno">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control"
                            value="{{ old('apellido_paterno', $alcalde->persona->apellido_paterno) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido_materno">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control"
                            value="{{ old('apellido_materno', $alcalde->persona->apellido_materno) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nacionalidad">Nacionalidad</label>
                        <input type="text" name="nacionalidad" class="form-control"
                            value="{{ old('nacionalidad', $alcalde->persona->nacionalidad) }}">
                    </div>
                </div>

                {{-- UBICACIÓN --}}
                <h6 class="text-warning border-bottom pb-2 mb-3"><i class="ri-map-pin-line me-2"></i>II. Ubicación</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="idRegion">Región</label>
                        <select name="idRegion" id="idRegion" class="form-control">
                            <option value="">Seleccione</option>
                            @foreach ($regiones as $region)
                                <option value="{{ $region->id_region }}"
                                    {{ old('idRegion', $alcalde->persona->distrito->provincia->region->id_region) == $region->id_region ? 'selected' : '' }}>
                                    {{ $region->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="idProvincia">Provincia</label>
                        <select name="idProvincia" id="idProvincia" class="form-control">
                            @foreach ($provincias as $provincia)
                                <option value="{{ $provincia->id_provincia }}"
                                    {{ old('idProvincia', $alcalde->persona->distrito->provincia->id_provincia) == $provincia->id_provincia ? 'selected' : '' }}>
                                    {{ $provincia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="idDistrito">Distrito</label>
                        <select name="idDistrito" id="idDistrito" class="form-control">
                            @foreach ($distritos as $distrito)
                                <option value="{{ $distrito->id_distrito }}"
                                    {{ old('idDistrito', $alcalde->persona->distrito->id_distrito) == $distrito->id_distrito ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estadoCivil">Estado Civil</label>
                        <select name="estadoCivil" class="form-control">
                            @foreach (['Soltero', 'Casado', 'Viudo', 'Divorciado'] as $estado)
                                <option value="{{ $estado }}"
                                    {{ old('estadoCivil', $alcalde->persona->estado_civil) == $estado ? 'selected' : '' }}>
                                    {{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- DATOS DE ALCALDE --}}
                <h6 class="text-danger border-bottom pb-2 mb-3"><i class="ri-government-line me-2"></i>III. Datos de
                    Alcaldía</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control"
                            value="{{ old('fecha_inicio', $alcalde->fecha_inicio) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" class="form-control"
                            value="{{ old('fecha_fin', $alcalde->fecha_fin) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado">Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $alcalde->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $alcalde->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Administrador que lo registró</label>
                        <input type="text" class="form-control bg-light"
                            value="{{ optional($alcalde->administrador->usuario->persona)->nombres .
                                ' ' .
                                optional($alcalde->administrador->usuario->persona)->apellido_paterno .
                                ' ' .
                                optional($alcalde->administrador->usuario->persona)->apellido_materno ??
                                'Sin registrar' }}"
                            readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="foto" class="form-label fw-semibold">
                        <i class="fas fa-image text-primary me-1"></i> Foto de Perfil
                    </label>

                    @if ($alcalde->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $alcalde->foto) }}" alt="Foto actual"
                                class="rounded-circle img-thumbnail"
                                style="width: 120px; height: 120px; object-fit: cover;">
                            <p class="text-muted small mt-1">Foto actual del alcalde</p>
                        </div>
                    @endif

                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                        accept="image/*">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Puedes reemplazar la foto actual. Tamaño máximo: 3MB. Formatos: JPG, JPEG, PNG, WEBP.
                    </small>
                </div>

                {{-- BOTONES --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success"><i class="ri-save-3-fill"></i> Guardar
                        Cambios</button>
                    <a href="{{ route('alcalde.index') }}" class="btn btn-danger"><i class="ri-close-fill"></i>
                        Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    {{-- JS dinámico --}}
    <script>
        $('#idRegion').on('change', function() {
            let id = $(this).val();
            $('#idProvincia').html('<option>Cargando...</option>');
            $.get(`/provincias/${id}`, function(data) {
                let html = '<option value="">Seleccione</option>';
                data.forEach(p => html += `<option value="${p.id_provincia}">${p.nombre}</option>`);
                $('#idProvincia').html(html);
                $('#idDistrito').html('<option value="">Seleccione</option>');
            });
        });

        $('#idProvincia').on('change', function() {
            let id = $(this).val();
            $('#idDistrito').html('<option>Cargando...</option>');
            $.get(`/distritos/${id}`, function(data) {
                let html = '<option value="">Seleccione</option>';
                data.forEach(d => html += `<option value="${d.id_distrito}">${d.nombre}</option>`);
                $('#idDistrito').html(html);
            });
        });
    </script>
@endsection
