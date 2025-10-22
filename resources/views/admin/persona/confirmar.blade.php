@extends('layouts.plantilla')

@section('titulo', 'Registro Civil - Confirmar Eliminación')

@section('contenido')
<main class="container-fluid py-5 mt-4" style="padding-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 custom-card">
                <div class="card-header bg-gradient-danger text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-trash-alt me-2"></i>Confirmar Eliminación</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="alert alert-warning d-flex align-items-center mb-4 rounded-3 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                        <div>
                            <strong>Atención:</strong> Esta acción es irreversible y eliminará permanentemente el registro.
                        </div>
                    </div>

                    <h5 class="fw-semibold mb-3 text-primary">Detalles de la Persona</h5>
                    <ul class="list-group mb-4 border-0">
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>ID:</strong> {{ $persona->id_persona }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Nombres:</strong> {{ $persona->nombres }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Apellidos:</strong> {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>DNI:</strong> {{ $persona->dni }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Fecha de Nacimiento:</strong> {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Sexo:</strong> 
                            @if($persona->sexo == 'M') Masculino @elseif($persona->sexo == 'F') Femenino @else Otro @endif
                        </li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Nacionalidad:</strong> {{ $persona->nacionalidad ?: 'No especificada' }}</li>
                        <li class="list-group-item bg-light rounded-3 mb-2"><strong>Estado Civil:</strong> {{ $persona->estado_civil ?: 'No especificado' }}</li>
                    </ul>

                    <form method="POST" action="{{ route('persona.destroy', $persona->id_persona) }}" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="confirm-delete" required>
                            <label class="form-check-label fw-semibold" for="confirm-delete">
                                Confirmo que deseo eliminar este registro.
                            </label>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button type="submit" class="btn btn-danger px-5 fw-semibold" id="delete-btn" disabled>
                                <i class="fas fa-trash me-2"></i>Sí, Eliminar
                            </button>
                            <a href="{{ route('persona.index') }}" class="btn btn-outline-secondary px-5 fw-semibold">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-muted text-center rounded-bottom-4 py-3">
                    Registro Civil © {{ date('Y') }}
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    :root {
        --primary-bg: #4e73df;
        --danger-bg: #dc3545;
        --card-bg: #ffffff;
        --text-color: #333333;
        --table-bg: #f8f9fc;
        --input-bg: #ffffff;
        --input-border: #ced4da;
    }

    .dark-mode {
        --primary-bg: #1a3c6d;
        --danger-bg: #a71d2a;
        --card-bg: #1c2526;
        --text-color: #e0e0e0;
        --table-bg: #2c3e50;
        --input-bg: #2c3e50;
        --input-border: #4b5e6f;
    }

    body {
        background-color: var(--table-bg);
        color: var(--text-color);
        transition: all 0.3s ease;
    }

    .custom-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        transition: transform 0.2s, background-color 0.3s;
    }

    .custom-card:hover {
        transform: translateY(-5px);
    }

    .bg-gradient-danger {
        background: linear-gradient(90deg, var(--danger-bg), #ff6b6b);
    }

    .list-group-item {
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--text-color);
    }

    .list-group-item strong {
        color: var(--primary-bg);
        display: inline-block;
        width: 140px;
    }

    .form-check-input:checked {
        background-color: var(--danger-bg);
        border-color: var(--danger-bg);
    }

    .btn-danger {
        background-color: var(--danger-bg);
        border-color: var(--danger-bg);
    }

    .btn-danger.loading::after {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        animation: spin 1s linear infinite;
    }

    .btn-outline-secondary {
        border-color: var(--input-border);
        color: var(--text-color);
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .dark-mode #toggle-dark-mode i::before {
        content: '\f185'; /* Sun icon (FontAwesome) */
    }

    @media (max-width: 768px) {
        .custom-card {
            margin: 0 15px;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .d-flex.gap-3 {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
        }
    }
</style>
<script>

    // Enable delete button on checkbox
    document.getElementById('confirm-delete').addEventListener('change', function () {
        document.getElementById('delete-btn').disabled = !this.checked;
    });

    // Show loading spinner on submit
    document.getElementById('delete-form').addEventListener('submit', function () {
        const btn = document.getElementById('delete-btn');
        btn.classList.add('loading');
        btn.disabled = true;
    });
</script>
@endsection




