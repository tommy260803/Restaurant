@extends('layouts.plantilla')

@section('titulo', 'Registro Civil - Personas')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/persona.css') }}">
@endsection

@section('contenido')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-users me-2"></i> Gestión de Personas
            </h2>
            <a href="{{ route('persona.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Nueva Persona
            </a>
        </div>

        @if (session('datos'))
            <div id="session-alert">
                <x-alert-success :message="session('datos')" />
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <x-search-bar :request="request()" routeName="persona.index" />
            </div>
        </div>

        @if (request('buscarpor'))
            <div class="mb-2">
                <x-search-info :term="request('buscarpor')" :field="request('buscar_por')" :count="$persona->total()" />
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if ($persona->count())
                    <div id="tabla-personas">
                        @include('admin.persona.partials.table', ['personas' => $persona])
                    </div>
                @else
                    <x-no-results :term="request('buscarpor')" routeName="persona.index" />
                @endif
            </div>
        </div>

        @if ($persona->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $persona->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

            const alertBox = document.getElementById('session-alert');
            if (alertBox) {
                setTimeout(() => {
                    bootstrap.Alert.getOrCreateInstance(alertBox).close();
                }, 5000);
            }

            const searchInput = document.getElementById('buscarpor');
            const searchForm = document.getElementById('search-form');
            const searchBtn = document.getElementById('search-btn');
            const fieldSelect = document.getElementById('buscar_por');
            let searchTimeout;

            const performSearch = () => {
                const term = searchInput.value.trim();
                const field = fieldSelect.value;

                if (term.length >= 2 || term.length === 0) {
                    $.ajax({
                        url: "{{ route('persona.index') }}",
                        type: 'GET',
                        data: {
                            buscarpor: term,
                            buscar_por: field
                        },
                        beforeSend: () => {
                            searchBtn.classList.add('loading');
                            searchBtn.disabled = true;
                        },
                        success: (data) => {
                            $('#tabla-personas').html(data);
                        },
                        complete: () => {
                            searchBtn.classList.remove('loading');
                            searchBtn.disabled = false;
                        }
                    });
                }
            };

            // Ejecutar búsqueda al tipear
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500); // velocidad adecuada
            });

            // Ejecutar búsqueda al cambiar campo
            fieldSelect.addEventListener('change', () => {
                if (searchInput.value.trim().length >= 2) performSearch();
            });

            // Evitar submit por defecto
            searchForm.addEventListener('submit', e => {
                e.preventDefault();
                performSearch();
            });

            window.addEventListener('load', () => {
                searchBtn.classList.remove('loading');
                searchBtn.disabled = false;
            });

            document.addEventListener('keydown', e => {
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                } else if (e.key === 'Escape' && document.activeElement === searchInput) {
                    searchInput.value = '';
                    performSearch();
                }
            });

            const highlightSearchTerm = term => {
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    row.querySelectorAll('td').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(term.toLowerCase())) {
                            const regex = new RegExp(`(${term})`, 'gi');
                            cell.innerHTML = cell.innerHTML.replace(regex, '<mark>$1</mark>');
                        }
                    });
                });
            };

            const searchTerm = '{{ request('buscarpor') }}';
            if (searchTerm) highlightSearchTerm(searchTerm);
        });
    </script>
@endsection
