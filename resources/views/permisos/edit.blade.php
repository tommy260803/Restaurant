@extends('layouts.plantilla')
@section('contenido')
    <div class="container">
        <h2>Editar Permiso</h2>
        <form action="{{ route('permisos.update', $permiso->id) }}" method="POST"> @csrf @method('PUT') <div
                class="mb-3"> <label>Nombre</label> <input type="text" name="name" class="form-control"
                    value="{{ $permiso->name }}" required> @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div> <button class="btn btn-success">Actualizar</button> </form>
    </div>
@endsection
