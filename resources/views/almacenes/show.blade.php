{{-- filepath: e:\CICLO 6\ING. REQUERIMIENTOS\RESTAURANT\resources\views\almacenes\show.blade.php --}}
@extends('layouts.plantilla')
@section('contenido')
<div class="container">
    <h1 class="mb-4">{{ $almacen->nombre }}</h1>
    <p><strong>Ubicación:</strong> {{ $almacen->ubicacion }}</p>
    <p><strong>Responsable:</strong> {{ $almacen->responsable }}</p>
    <h4>Stock por Ingrediente</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ingrediente</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($almacen->ingredientes as $ingrediente)
                <tr>
                    <td>{{ $ingrediente->nombre }}</td>
                    <td>{{ $ingrediente->pivot->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h4>Transferir Stock</h4>
    <form method="POST" action="{{ route('almacenes.transferirStock') }}">
        @csrf
        <input type="hidden" name="origen_id" value="{{ $almacen->id }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="destino_id" class="form-label">Almacén destino</label>
                <select name="destino_id" id="destino_id" class="form-select" required>
                    @foreach(\App\Models\Inventario\Almacen::where('id', '!=', $almacen->id)->get() as $destino)
                        <option value="{{ $destino->id }}">{{ $destino->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="ingrediente_id" class="form-label">Ingrediente</label>
                <select name="ingrediente_id" id="ingrediente_id" class="form-select" required>
                    @foreach($almacen->ingredientes as $ingrediente)
                        <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" min="0.01" step="0.01" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Transferir</button>
            </div>
        </div>
    </form>
    <a href="{{ route('almacenes.index') }}" class="btn btn-secondary mt-4">Volver</a>
</div>
@endsection