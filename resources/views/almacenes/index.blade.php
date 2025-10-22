{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\almacenes\index.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Almacenes</h1>
    <a href="{{ route('almacenes.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Nuevo Almacén
    </a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Responsable</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($almacenes as $almacen)
                <tr>
                    <td class="fw-bold">{{ $almacen->nombre }}</td>
                    <td>{{ $almacen->ubicacion }}</td>
                    <td>{{ $almacen->responsable }}</td>
                    <td>
                        <a href="{{ route('almacenes.show', $almacen->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver Stock / Transferir
                        </a>
                        <a href="{{ route('almacenes.edit', $almacen->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('almacenes.destroy', $almacen->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar este almacén?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection