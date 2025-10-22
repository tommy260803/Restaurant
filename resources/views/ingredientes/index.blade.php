{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\ingredientes\index.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Ingredientes</h1>
    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('ingredientes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Ingrediente
        </a>
        <a href="{{ route('ingredientes.bajos') }}" class="btn btn-danger">
            <i class="fas fa-exclamation-triangle"></i> Bajo Stock
        </a>
    </div>
    @if($ingredientes->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Stock Mínimo</th>
                    <th>Costo Promedio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ingredientes as $ingrediente)
                <tr @if($ingrediente->stock < $ingrediente->stock_minimo) style="background:#ffe0e0;" @endif>
                    <td class="fw-bold">{{ $ingrediente->nombre }}</td>
                    <td>{{ $ingrediente->unidad }}</td>
                    <td>
                        {{ $ingrediente->stock }}
                        @if($ingrediente->stock < $ingrediente->stock_minimo)
                            <span class="badge bg-danger ms-2">Bajo</span>
                        @endif
                    </td>
                    <td>{{ $ingrediente->stock_minimo }}</td>
                    <td>S/ {{ number_format($ingrediente->costo_promedio, 2) }}</td>
                    <td>
                        <a href="{{ route('ingredientes.show', $ingrediente->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('ingredientes.destroy', $ingrediente->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar este ingrediente?');">
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
    {{ $ingredientes->links() }}
    @else
        <div class="alert alert-info">No hay ingredientes registrados.</div>
    @endif
</div>
@endsection