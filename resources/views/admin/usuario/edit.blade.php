@extends('layouts.plantilla')

@section('titulo', 'Editar Perfil')

@section('contenido')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Editar Perfil</h5>
                <a class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('usuarios.perfil.update', $usuario->id_usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="dni_usuario">DNI</label>
                        <input type="text" class="form-control @error('dni_usuario') is-invalid @enderror"
                            name="dni_usuario" id="dni_usuario" value="{{ $usuario->dni_usuario }}">
                        @error('dni_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="nombre_usuario">Nombre de Usuario</label>
                        <input type="text" class="form-control @error('nombre_usuario') is-invalid @enderror"
                            name="nombre_usuario" id="nombre_usuario" value="{{ $usuario->nombre_usuario }}">
                        @error('nombre_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email_mi_acta">Email Principal</label>
                        <input type="email" class="form-control @error('email_mi_acta') is-invalid @enderror"
                            name="email_mi_acta" id="email_mi_acta"
                            value="{{ old('email_mi_acta', $usuario->email_mi_acta) }}">
                        @error('email_mi_acta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email_respaldo">Email de Respaldo (opcional)</label>
                        <input type="email" class="form-control @error('email_respaldo') is-invalid @enderror"
                            name="email_respaldo" id="email_respaldo"
                            value="{{ old('email_respaldo', $usuario->email_respaldo) }}">
                        @error('email_respaldo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="rol">Rol</label>
                        <select name="rol" id="rol" class="form-select @error('rol') is-invalid @enderror" required>
                            <option value="">Seleccione un rol</option>
                            <option value="administrador" {{ old('rol', $usuario->getRoleNames()->first()) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="cocinero" {{ old('rol', $usuario->getRoleNames()->first()) == 'cocinero' ? 'selected' : '' }}>Cocinero</option>
                            <option value="almacenero" {{ old('rol', $usuario->getRoleNames()->first()) == 'almacenero' ? 'selected' : '' }}>Almacenero</option>
                            <option value="cajero" {{ old('rol', $usuario->getRoleNames()->first()) == 'cajero' ? 'selected' : '' }}>Cajero</option>
                        </select>
                        @error('rol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
