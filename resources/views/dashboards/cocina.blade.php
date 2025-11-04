@extends('layouts.plantilla')

@section('contenido')
    <h1>Dashboard cocina</h1>
    <p>Bienvenido {{ $usuario->nombre_usuario }}</p>
    <!-- Contenido específico del cocina -->
@endsection