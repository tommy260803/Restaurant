@extends('layouts.plantilla')
@section('contenido')
<div class="container-fluid px-4 mt-4 animate__animated animate__fadeIn">
    <div class="text-center mb-4">
        <h3 class="display-5 fw-bold text-primary">
            <span class="bg-gradient-text bg-clip-text" style="background-image: linear-gradient(to right, #4f46e5, #8b5cf6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <i class="fas fa-list me-2"></i>Actas de Defunción
            </span>
        </h3>
        <p class="text-muted mb-0">Gestión y consulta de registros</p>
    </div>

    @if(session('success'))
        <div id="mensaje" class="alert alert-success alert-dismissible fade show mb-4 p-3 border-2 rounded-3 shadow-sm animate__animated animate__fadeIn" style="background-image: linear-gradient(to bottom right, #dcfce7, #bbf7d0); border-color: #86efac;">
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('defuncion.index') }}" class="d-flex gap-2">
                <input type="text" name="buscar" class="form-control me-2 px-3 py-2 rounded-3 border-2 shadow-sm" placeholder="Buscar por DNI" value="{{ request('buscar') }}">
                <button class="btn btn-success bg-gradient px-4 py-2 shadow-sm d-flex align-items-center gap-2" type="submit" style="background-image: linear-gradient(to bottom right, #166534, #4ade80, #14532d);">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('defuncion.create') }}" class="btn btn-primary bg-gradient shadow-sm d-flex align-items-center gap-2 px-4 py-2 fw-semibold" style="background-image: linear-gradient(to bottom right, #1e3a8a, #22d3ee); transition: all 0.3s;">
                <i class="fas fa-plus me-1"></i>Nueva Acta
            </a>
        </div>
    </div>

    <div class="card shadow border-0 bg-white rounded-3 shadow-lg">
        <div class="table-responsive">
            <table class="table table-hover mb-0 table-sm text-center align-middle">
                <thead class="text-white text-uppercase" style="background-image: linear-gradient(to right, #1f2937, #111827);">
                    <tr>
                        <th class="px-4 py-3">Acta</th>
                        <th class="px-4 py-3">Libro</th>
                        <th class="px-4 py-3">Folio</th>
                        <th class="px-4 py-3">DNI</th>
                        <th class="px-4 py-3">Nombres y Apellidos</th>
                        <th class="px-4 py-3">Fecha Defunción</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actas as $acta)
                        <tr class="hover-effect align-middle bg-white shadow-sm mb-3 rounded-3" style="border-bottom: 16px solid #f3f4f6;">
                            <td class="px-4 py-3">
                                <span class="badge rounded-pill shadow-sm" style="background-image: linear-gradient(to right, #3b82f6, #1d4ed8); font-size: 0.9rem;">ACTA-{{ str_pad($acta->id_acta_defuncion, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($acta->folio && $acta->folio->libro)
                                    <span class="badge rounded-pill shadow-sm" style="background-image: linear-gradient(to right, #059669, #10b981);"><strong>{{ $acta->folio->libro->numero_libro }}</strong></span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($acta->folio)
                                    <span class="badge rounded-pill shadow-sm" style="background-image: linear-gradient(to right, #7c3aed, #a855f7);"><strong>{{ $acta->folio->numero_folio }}</strong></span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge rounded-pill shadow-sm" style="background-image: linear-gradient(to right, #6b7280, #4b5563);">{{ $acta->dni_fallecido }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($acta->persona_fallecida)
                                    <strong>{{ $acta->persona_fallecida->nombre_completo }}</strong>
                                @else
                                    <span class="text-danger">Persona no encontrada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($acta->fecha_defuncion)
                                    <span class="badge rounded-pill shadow-sm" style="background-image: linear-gradient(to right, #dc2626, #ef4444);">{{ \Carbon\Carbon::parse($acta->fecha_defuncion)->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-muted">N/D</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group d-flex flex-wrap gap-2 justify-content-center" role="group">
                                    <a href="{{ route('defuncion.edit', $acta->id_acta_defuncion) }}" class="btn btn-sm btn-warning d-flex align-items-center gap-2 shadow-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-md-inline">Editar</span>
                                    </a>
                                    <a href="{{ route('actas.pdf', $acta->id_acta_defuncion) }}" class="btn btn-sm btn-success d-flex align-items-center gap-2 shadow-sm" title="Generar PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                        <span class="d-none d-md-inline">PDF</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <h5>No hay actas registradas</h5>
                                    <p>Comience registrando la primera acta de defunción</p>
                                    <a href="{{ route('defuncion.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i>Registrar Primera Acta
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($actas->hasPages())
        <div class="d-flex justify-content-center mt-4">
           {{ $actas->appends(['buscar' => request('buscar')])->links() }}
        </div>
    @endif
</div>

<script>
    setTimeout(() => {
        let mensaje = document.getElementById('mensaje');
        if (mensaje) mensaje.remove();
    }, 3000);
    
    document.querySelectorAll('.hover-effect').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.background = 'linear-gradient(to right, #e0e7ff, #fce7f3)';
            this.style.boxShadow = '0 4px 20px 0 rgba(0,0,0,0.07)';
        });
        row.addEventListener('mouseleave', function() {
            this.style.background = '';
            this.style.boxShadow = '';
        });
    });
</script>

<style>
    .table > :not(:last-child) > :last-child > * {
        border-bottom-color: transparent !important;
    }
    .hover-effect {
        transition: background 0.3s, box-shadow 0.3s;
        margin-bottom: 1rem;
        border-radius: 0.75rem;
    }
    .btn {
        min-width: 100px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn i {
        font-size: 1.1rem;
    }
    .btn:hover, .btn:focus {
        transform: scale(1.07);
        box-shadow: 0 8px 20px -3px rgba(0,0,0,0.12);
        z-index: 2;
    }
    .btn span {
        font-size: 0.95rem;
    }
    /* Responsive: solo ícono en móvil */
    @media (max-width: 768px) {
        .btn span {
            display: none !important;
        }
        .btn {
            min-width: 40px;
            justify-content: center;
        }
    }
</style>
@endsection