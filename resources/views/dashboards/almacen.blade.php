@extends('layouts.plantilla')

@section('contenido')
    <h1>Dashboard Almacén</h1>
    <p>Bienvenido {{ $usuario->nombre_usuario }}</p>
    <!-- Contenido específico del almacén -->
@endsection