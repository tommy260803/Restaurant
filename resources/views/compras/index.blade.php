{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\compras\index.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">Compras</h1>
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="proveedor" class="form-select">
                    <option value="">-- Todos los proveedores --</option>
                    @if(isset($proveedores))
                        @foreach($proveedores as $prov)
                            <option value="{{ $prov->idProveedor }}" {{ request('proveedor') == $prov->idProveedor ? 'selected' : '' }}>
                                {{ $prov->nombre }} {{ $prov->apellidoPaterno }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="desde" class="form-control" value="{{ request('desde') }}" placeholder="Fecha desde">
            </div>
            <div class="col-md-3">
                <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}" placeholder="Fecha hasta">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button>
            </div>
        </div>
    </form>
    <a href="{{ route('compras.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Nueva Compra
    </a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                <tr>
                    <td>{{ $compra->idCompra }}</td>
                    <td>{{ $compra->proveedor->nombre ?? 'N/A' }} {{ $compra->proveedor->apellidoPaterno ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $compra->descripcion }}</td>
                    <td>S/ {{ number_format($compra->total,2) }}</td>
                    <td>
                        <span class="badge 
                            @if($compra->estado=='pendiente') bg-warning text-dark
                            @elseif($compra->estado=='en_transito') bg-info
                            @elseif($compra->estado=='recibida') bg-success
                            @else bg-danger @endif">
                            {{ ucfirst(str_replace('_', ' ', $compra->estado)) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('compras.show', $compra->idCompra) }}" class="btn btn-info btn-sm" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('compras.edit', $compra->idCompra) }}" class="btn btn-warning btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('compras.comprobantePDF', $compra->idCompra) }}" class="btn btn-secondary btn-sm" target="_blank" title="PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <form action="{{ route('compras.destroy', $compra->idCompra) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">No hay compras registradas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $compras->links() }}
</div>
@endsection