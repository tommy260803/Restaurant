@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Movimientos de Inventario</h1>
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="number" name="ingrediente_id" class="form-control" placeholder="ID Ingrediente" value="{{ request('ingrediente_id') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
    <form method="POST" action="{{ route('movimientos-inventario.store') }}" class="mb-4">
        @csrf
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <input type="number" name="ingrediente_id" class="form-control" placeholder="ID Ingrediente" required>
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                    <option value="ajuste">Ajuste</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="cantidad" class="form-control" placeholder="Cantidad" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="motivo" class="form-control" placeholder="Motivo">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Registrar Movimiento</button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Ingrediente</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                <tr>
                    <td>{{ $mov->fecha }}</td>
                    <td>{{ $mov->ingrediente->nombre ?? 'N/A' }}</td>
                    <td>
                        <span class="badge 
                            @if($mov->tipo=='entrada') bg-success 
                            @elseif($mov->tipo=='salida') bg-danger 
                            @else bg-warning text-dark @endif">
                            {{ ucfirst($mov->tipo) }}
                        </span>
                    </td>
                    <td>{{ $mov->cantidad }}</td>
                    <td>{{ $mov->motivo }}</td>
                    <td>
                        <a href="{{ route('movimientos-inventario.show', $mov->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay movimientos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $movimientos->links() }}
</div>
@endsection
