@extends('layouts.plantilla')

@section('contenido')
    <h1>Dashboard Caja</h1>
    <p>Bienvenido {{ $usuario->nombre_usuario }}</p>
    <!-- Contenido específico del caja -->
@endsection