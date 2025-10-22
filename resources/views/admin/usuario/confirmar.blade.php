@extends('layouts.plantilla')

@section('titulo', 'Usuarios - Confirmar Eliminación')

@section('contenido')
    <main class="container-fluid py-5 mt-4" style="padding-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 custom-card">
                    <div
                        class="card-header bg-danger text-white d-flex justify-content-between align-items-center rounded-top-4">
                        <h3 class="mb-0 fw-bold"><i class="fas fa-user-slash me-2"></i>Confirmar Eliminación de Usuario</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="alert alert-warning d-flex align-items-center mb-4 rounded-3 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                            <div>
                                <strong>Atención:</strong> Esta acción es irreversible y eliminará permanentemente al
                                usuario.
                            </div>
                        </div>

                        <h5 class="fw-semibold mb-3 text-primary">Detalles del Usuario</h5>
                        <ul class="list-group mb-4 border-0">
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>ID:</strong>
                                {{ $usuario->id_usuario }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>DNI:</strong>
                                {{ $usuario->dni_usuario }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>Nombre de Usuario:</strong>
                                {{ $usuario->nombre_usuario }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>Email Principal:</strong>
                                {{ $usuario->email_mi_acta }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>Email Respaldo:</strong>
                                {{ $usuario->email_respaldo ?: 'No especificado' }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>Rol:</strong>
                                {{ ucfirst($usuario->rol) }}</li>
                            <li class="list-group-item bg-light rounded-3 mb-2"><strong>Estado:</strong>
                                {{ $usuario->estado ? 'Activo' : 'Inactivo' }}</li>
                        </ul>

                        <form method="POST" action="{{ route('usuarios.destroy', $usuarios->id_usuario) }}" id="delete-form">
                            @csrf
                            @method('DELETE')
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="confirm-delete" required>
                                <label class="form-check-label fw-semibold" for="confirm-delete">
                                    Confirmo que deseo eliminar este usuario.
                                </label>
                            </div>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <button type="submit" class="btn btn-danger px-5 fw-semibold" id="delete-btn" disabled>
                                    <i class="fas fa-trash me-2"></i>Sí, Eliminar
                                </button>
                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-5 fw-semibold">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center rounded-bottom-4 py-3">
                        Sistema de Usuarios &copy; {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('confirm-delete').addEventListener('change', function () {
            document.getElementById('delete-btn').disabled = !this.checked;
        });

        document.getElementById('delete-form').addEventListener('submit', function () {
            const btn = document.getElementById('delete-btn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
@endsection