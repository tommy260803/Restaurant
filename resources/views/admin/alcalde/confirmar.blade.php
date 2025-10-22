@extends('layout.plantilla')

@section('contenido')

<div class="card card-success">
    <div class="card-header">
        <h1>Eliminar dato </h1>
    </div>
    <div class="card-body">
        <h3>Desea eliminar registro ? Codigo : {{ $clientes->idClientes }} - Apellidos : {{ $clientes->apellidos }}
            - Nombres : {{ $clientes->nombres }} - Dirección : {{ $clientes->direccion }}
        </h3>
        <form method="POST" action="{{ route('clientes.destroy',$clientes->idClientes)}}">
            @method('delete')
            @csrf
            <button type="submit" class="btn btn-danger"><i class="fas facheck-square"></i> SI</button>
            <a href="{{ route('clientes.cancelar4')}}" class="btn btnprimary"><i class="fas fa-times-circle"></i> NO</button></a>
        </form>
    </div>
</div>

@endsection