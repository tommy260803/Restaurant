@extends('layouts.plantilla')

@section('titulo', 'Usuarios')

@section('contenido')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Lista de Usuarios</h5>
                <a href="{{ route('usuarios.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nombre de Usuario</th>
                                <th>Email Mi Acta</th>
                                <th>Email de Respaldo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $usuario->nombre_usuario }}</td>
                                    <td>{{ $usuario->email_mi_acta }}</td>
                                    <td>{{ $usuario->email_respaldo ?? 'No especificado' }}</td>
                                    <td>{{ $usuario->rol ?? 'No asignado' }}</td>
                                    <td>
                                        @if ($usuario->estado == '1')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
                                            class="btn btn-info btn-sm" title="Ver">
                                            <i class='bx bx-meh-alt'></i> Ver/Editar
                                        </a>
                                        <button
                                            onclick="eliminarUsuario({{ $usuario->id_usuario }}, '{{ $usuario->nombre_usuario }}')"
                                            class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class='bx bx-trash'></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Éxito",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    <script>
        function eliminarUsuario(id, nombre) {
            Swal.fire({
                title: '¿Eliminar Usuario?',
                text: `ID: ${id} - ${nombre}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/usuarios/eliminar/${id}`;
                }
            });
        }
    </script>
@endpush
