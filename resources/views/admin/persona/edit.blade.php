@extends('layouts.plantilla')
@section('title', 'Editar registro')
@section('contenido')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h2 class="mb-0"><i class="fas fa-user-edit"></i> Editar Persona</h2>
                    </div>
                    <div class="card-body bg-light">
                        <form method="POST" action="{{ route('persona.update', $persona->id_persona) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="id" name="id"
                                    value="{{ $persona->id_persona }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni"
                                    name="dni" value="{{ old('dni', $persona->dni) }}">
                                @error('dni')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                                    id="nombres" name="nombres" value="{{ old('nombres', $persona->nombres) }}">
                                @error('nombres')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror"
                                    id="apellido_paterno" name="apellido_paterno"
                                    value="{{ old('apellido_paterno', $persona->apellido_paterno) }}">
                                @error('apellido_paterno')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror"
                                    id="apellido_materno" name="apellido_materno"
                                    value="{{ old('apellido_materno', $persona->apellido_materno) }}">
                                @error('apellido_materno')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    id="fecha_nacimiento" name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento ? \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d') : null) }}">
                                @error('fecha_nacimiento')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <input type="text" class="form-control @error('sexo') is-invalid @enderror"
                                    id="sexo" name="sexo" value="{{ old('sexo', $persona->sexo) }}">
                                @error('sexo')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control @error('nacionalidad') is-invalid @enderror"
                                    id="nacionalidad" name="nacionalidad"
                                    value="{{ old('nacionalidad', $persona->nacionalidad) }}">
                                @error('nacionalidad')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil</label>
                                <select class="form-select @error('estado_civil') is-invalid @enderror" id="estado_civil"
                                    name="estado_civil">
                                    <option value="">Seleccione...</option>
                                    <option value="Soltero"
                                        {{ old('estado_civil', $persona->estado_civil) == 'Soltero' ? 'selected' : '' }}>
                                        Soltero</option>
                                    <option value="Casado"
                                        {{ old('estado_civil', $persona->estado_civil) == 'Casado' ? 'selected' : '' }}>
                                        Casado</option>
                                    <option value="Divorciado"
                                        {{ old('estado_civil', $persona->estado_civil) == 'Divorciado' ? 'selected' : '' }}>
                                        Divorciado</option>
                                </select>
                                @error('estado_civil')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h4 class="mb-3">Información de Ubicación</h4>

                            <div class="form-group">
                                <label for="idRegion">Región</label>
                                <select class="form-control @error('idRegion') is-invalid @enderror" id="idRegion"
                                    name="idRegion">
                                    <option value="">Seleccione una Región</option>
                                    @foreach ($regiones as $region)
                                        <option value="{{ $region->id_region }}"
                                            {{ old('id_region', $persona->distrito->provincia->region->id_region ?? '') == $region->id_region ? 'selected' : '' }}>
                                            {{ $region->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idRegion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="idProvincia">Provincia</label>
                                <select class="form-control @error('idProvincia') is-invalid @enderror" id="idProvincia"
                                    name="idProvincia">
                                    <option value="">Seleccione una Provincia</option>
                                    @foreach ($provincias as $provincia)
                                        <option value="{{ $provincia->id_provincia }}"
                                            {{ old('id_provincia', $persona->distrito->provincia->id_provincia ?? '') == $provincia->id_provincia ? 'selected' : '' }}>
                                            {{ $provincia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idProvincia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="id_distrito">Distrito</label>
                                <select class="form-control @error('id_distrito') is-invalid @enderror" id="id_distrito"
                                    name="id_distrito">
                                    <option value="">Seleccione un Distrito</option>
                                    @foreach ($distritos as $distrito)
                                        <option value="{{ $distrito->id_distrito }}"
                                            {{ old('id_distrito', $persona->distrito->id_distrito ?? '') == $distrito->id_distrito ? 'selected' : '' }}>
                                            {{ $distrito->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_distrito')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success px-4"><i class="fas fa-save"></i>
                                    Guardar</button>
                                <a href="{{ route('persona.index') }}" class="btn btn-outline-danger px-4"><i
                                        class="fas fa-ban"></i> Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
        }

        .card {
            border-radius: 1.5rem;
        }

        .card-header {
            border-radius: 1.5rem 1.5rem 0 0;
        }

        .btn-success {
            background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/ubicacion.js"></script>



@endsection
