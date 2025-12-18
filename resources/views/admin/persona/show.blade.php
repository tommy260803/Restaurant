@extends('layouts.plantilla')

@section('titulo', 'Detalles del Personal')

@section('contenido')
    <div class="container py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-user me-2"></i> Detalles del Personal</h4>
                <a href="{{ route('persona.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $persona->id_persona }}</td>
                    </tr>
                    <tr>
                        <th>Nombres</th>
                        <td>{{ $persona->nombres }}</td>
                    </tr>
                    <tr>
                        <th>Apellido Paterno</th>
                        <td>{{ $persona->apellido_paterno }}</td>
                    </tr>
                    <tr>
                        <th>Apellido Materno</th>
                        <td>{{ $persona->apellido_materno }}</td>
                    </tr>
                    <tr>
                        <th>DNI</th>
                        <td>{{ $persona->dni }}</td>
                    </tr>
                    <tr>
                        <th>Sexo</th>
                        <td>{{ $persona->sexo == 'M' ? 'Masculino' : 'Femenino' }}</td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td>{{ $persona->direccion ?: 'No especificada' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
