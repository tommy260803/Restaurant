@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="container">
        <h4 class="text-warning mb-3"><i class="fas fa-edit me-2"></i>Editar Proveedor</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('proveedor.update', $proveedor->idProveedor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Columna Izquierda: Datos del Proveedor -->
                <div class="col-md-8">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-warning py-2 text-dark">📄 Editar Datos del Proveedor</h5>
                        <div class="card-body" style="height:75vh;overflow-y:auto">

                            {{-- Información del Registro --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-info-circle me-2"></i>Información del Registro
                            </h6>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="mb-2">ID del Proveedor</label>
                                    <input type="text" class="form-control bg-light" value="PROV-{{ str_pad($proveedor->idProveedor, 5, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="mb-2">Fecha de Registro</label>
                                    <input type="text" class="form-control bg-light" value="{{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y H:i') : 'N/D' }}" readonly>
                                </div>
                            </div>

                            {{-- Tipo de Persona --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-badge me-2"></i>Tipo de Persona
                            </h6>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="btn-group w-100" role="group" aria-label="Tipo de persona">
                                        @php
                                            // Detectar tipo actual basado en si tiene apellidos o solo RUC
                                            $tipoActual = (empty($proveedor->apellidoPaterno) && !empty($proveedor->rucProveedor)) ? 'juridica' : 'natural';
                                            $tipoActual = old('tipo_persona', $tipoActual);
                                        @endphp
                                        
                                        <input type="radio" class="btn-check" name="tipo_persona" id="persona_natural" 
                                            value="natural" {{ $tipoActual == 'natural' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="persona_natural">
                                            <i class="bi bi-person me-2"></i>Persona Natural
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_persona" id="persona_juridica" 
                                            value="juridica" {{ $tipoActual == 'juridica' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="persona_juridica">
                                            <i class="bi bi-building me-2"></i>Persona Jurídica
                                        </label>
                                    </div>
                                    @error('tipo_persona')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Datos de Identificación --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-card-text me-2"></i>Datos de Identificación
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="nombre" class="form-label">
                                        <span id="label_nombre">Nombres</span> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre" 
                                        class="form-control @error('nombre') is-invalid @enderror" 
                                        value="{{ old('nombre', $proveedor->nombre) }}" required 
                                        placeholder="Ingrese los nombres">
                                    @error('nombre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3" id="apellidos_container">
                                <div class="col-md-6">
                                    <label for="apellidoPaterno" class="form-label">
                                        Apellido Paterno <span class="text-danger" id="asterisco_paterno">*</span>
                                    </label>
                                    <input type="text" name="apellidoPaterno" id="apellidoPaterno" 
                                        class="form-control @error('apellidoPaterno') is-invalid @enderror" 
                                        value="{{ old('apellidoPaterno', $proveedor->apellidoPaterno) }}" 
                                        placeholder="Apellido paterno">
                                    @error('apellidoPaterno')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidoMaterno" class="form-label">Apellido Materno</label>
                                    <input type="text" name="apellidoMaterno" id="apellidoMaterno" 
                                        class="form-control @error('apellidoMaterno') is-invalid @enderror" 
                                        value="{{ old('apellidoMaterno', $proveedor->apellidoMaterno) }}" 
                                        placeholder="Apellido materno">
                                    @error('apellidoMaterno')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="rucProveedor" class="form-label">
                                        RUC <span class="text-danger d-none" id="asterisco_ruc">*</span>
                                    </label>
                                    <input type="text" name="rucProveedor" id="rucProveedor" 
                                        class="form-control @error('rucProveedor') is-invalid @enderror" 
                                        value="{{ old('rucProveedor', $proveedor->rucProveedor) }}" 
                                        placeholder="Número de RUC"
                                        maxlength="11">
                                    <small class="text-muted" id="help_ruc">Opcional para personas naturales, obligatorio para jurídicas</small>
                                    @error('rucProveedor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">
                                        Teléfono <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="telefono" id="telefono" 
                                        class="form-control @error('telefono') is-invalid @enderror" 
                                        value="{{ old('telefono', $proveedor->telefono) }}" required 
                                        placeholder="Número de teléfono"
                                        maxlength="15">
                                    @error('telefono')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Información de Contacto --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-envelope me-2"></i>Información de Contacto
                            </h6>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $proveedor->email) }}" required 
                                    placeholder="ejemplo@correo.com">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <textarea name="direccion" id="direccion" 
                                    class="form-control @error('direccion') is-invalid @enderror" 
                                    rows="3" required 
                                    placeholder="Dirección completa del proveedor">{{ old('direccion', $proveedor->direccion) }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Estado y Evaluaciones --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-toggle-on me-2"></i>Estado y Evaluaciones
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="activo" {{ old('estado', $proveedor->estado) == 'activo' ? 'selected' : '' }}>
                                            <i class="bi bi-check-circle"></i> Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado', $proveedor->estado) == 'inactivo' ? 'selected' : '' }}>
                                            <i class="bi bi-x-circle"></i> Inactivo
                                        </option>
                                        <option value="bloqueado" {{ old('estado', $proveedor->estado) == 'bloqueado' ? 'selected' : '' }}>
                                            <i class="bi bi-shield-x"></i> Bloqueado
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="calificacion" class="form-label">
                                        Calificación General
                                        <span class="badge bg-primary">{{ number_format($proveedor->calificacion ?? 0, 1) }}/5</span>
                                    </label>
                                    <div class="form-control bg-light d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ ($proveedor->calificacion ?? 0) >= $i ? 'text-warning' : 'text-muted' }} me-1"></i>
                                        @endfor
                                        <small class="ms-2 text-muted">
                                            ({{ $proveedor->calificacion ?? 0 }} puntos)
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="incumplimientos" class="form-label">
                                        Incumplimientos
                                        <span class="badge {{ ($proveedor->incumplimientos ?? 0) >= 3 ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $proveedor->incumplimientos ?? 0 }}
                                        </span>
                                    </label>
                                    <div class="form-control bg-light">
                                        {{ $proveedor->incumplimientos ?? 0 }} registrado(s)
                                        @if(($proveedor->incumplimientos ?? 0) >= 3)
                                            <small class="text-danger d-block">⚠️ Límite alcanzado</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vista Previa del Nombre</label>
                                    <div class="form-control bg-light" id="preview_nombre_completo">
                                        {{ $proveedor->nombre_completo ?? 'Nombre completo' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Última Actualización</label>
                                    <div class="form-control bg-light">
                                        {{ $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : 'Nunca' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-warning flex-fill">
                                    <i class="bi bi-save me-1"></i>Actualizar Proveedor
                                </button>
                                <a href="{{ route('proveedor.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Documentación y Estadísticas -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">📊 Información Adicional</h5>
                        <div class="card-body" style="height:75vh;overflow-y:auto">
                            
                            {{-- Estadísticas del Proveedor --}}
                            <div class="alert alert-info mb-3">
                                <h6><i class="fas fa-chart-bar me-1"></i>Estadísticas</h6>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <strong>{{ $proveedor->compras->count() ?? 0 }}</strong>
                                            <small class="d-block text-muted">Compras</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <strong>{{ number_format($proveedor->compras->sum('total') ?? 0, 2) }}</strong>
                                            <small class="d-block text-muted">S/ Total</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <strong>{{ $proveedor->documentos->count() ?? 0 }}</strong>
                                            <small class="d-block text-muted">Docs</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Documentos Actuales --}}
                            @if($proveedor->documentos && count($proveedor->documentos) > 0)
                                <div class="alert alert-success mb-3">
                                    <h6><i class="fas fa-file-alt me-1"></i>Documentos Actuales ({{ count($proveedor->documentos) }})</h6>
                                    <div class="list-group list-group-flush">
                                        @foreach($proveedor->documentos as $documento)
                                            <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                <div>
                                                    <small class="fw-bold">{{ $documento->nombre_original ?? basename($documento->archivo) }}</small>
                                                    <small class="d-block text-muted">
                                                        {{ $documento->created_at ? $documento->created_at->format('d/m/Y') : 'N/A' }}
                                                    </small>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ asset('storage/' . $documento->archivo) }}" 
                                                       class="btn btn-outline-success btn-sm" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger btn-sm eliminar-documento" 
                                                            data-archivo="{{ $documento->archivo }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning mb-3">
                                    <h6><i class="fas fa-exclamation-triangle me-1"></i>Sin Documentos</h6>
                                    <p class="mb-0">Este proveedor no tiene documentos asociados.</p>
                                </div>
                            @endif

                            {{-- Subir Nuevos Documentos --}}
                            <div class="mb-3">
                                <label for="documentos" class="form-label">
                                    {{ ($proveedor->documentos && count($proveedor->documentos) > 0) ? 'Agregar más documentos' : 'Subir documentos' }}
                                </label>
                                <input type="file" name="documentos[]" id="documentos" 
                                    class="form-control @error('documentos.*') is-invalid @enderror" 
                                    multiple accept=".pdf,.jpg,.jpeg,.png,.docx">
                                <small class="text-muted">Opcional. Archivos permitidos: PDF, JPG, PNG, DOCX. Máx 2MB c/u.</small>
                                @error('documentos.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Vista previa de archivos seleccionados --}}
                            <div id="preview-container" class="d-none">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-eye me-1"></i>Archivos Nuevos
                                </h6>
                                <div id="preview-list" class="list-group mb-3"></div>
                            </div>

                            {{-- Tips según tipo de persona --}}
                            <div class="alert alert-success" id="tips_container">
                                <h6><i class="fas fa-lightbulb me-1"></i>Tips de Edición</h6>
                                <div id="tips_natural">
                                    <small>
                                        <strong>Persona Natural:</strong><br>
                                        • Complete nombres y apellidos<br>
                                        • RUC es opcional<br>
                                        • Verifique datos de contacto
                                    </small>
                                </div>
                                <div id="tips_juridica" class="d-none">
                                    <small>
                                        <strong>Persona Jurídica:</strong><br>
                                        • Nombre = Razón Social<br>
                                        • RUC es obligatorio (11 dígitos)<br>
                                        • Mantenga documentos actualizados
                                    </small>
                                </div>
                            </div>

                            {{-- Evaluaciones Detalladas --}}
                            @if($proveedor->puntualidad > 0 || $proveedor->calidad > 0 || $proveedor->precio > 0)
                                <div class="alert alert-primary">
                                    <h6><i class="fas fa-star me-1"></i>Evaluaciones Detalladas</h6>
                                    <div class="mb-2">
                                        <small class="d-flex justify-content-between">
                                            <span>Puntualidad:</span>
                                            <span>{{ $proveedor->puntualidad }}/5 ⭐</span>
                                        </small>
                                    </div>
                                    <div class="mb-2">
                                        <small class="d-flex justify-content-between">
                                            <span>Calidad:</span>
                                            <span>{{ $proveedor->calidad }}/5 ⭐</span>
                                        </small>
                                    </div>
                                    <div class="mb-0">
                                        <small class="d-flex justify-content-between">
                                            <span>Precio:</span>
                                            <span>{{ $proveedor->precio }}/5 ⭐</span>
                                        </small>
                                    </div>
                                </div>
                            @endif

                            {{-- Acciones Adicionales --}}
                            <div class="d-grid gap-2">
                                @if($proveedor->estado == 'bloqueado')
                                    <a href="{{ route('proveedor.activar', $proveedor->idProveedor) }}" 
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('¿Reactivar este proveedor?')">
                                        <i class="fas fa-unlock me-1"></i>Reactivar Proveedor
                                    </a>
                                @endif
                                <a href="{{ route('proveedor.dashboard', $proveedor->idProveedor) }}" 
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-chart-pie me-1"></i>Ver Dashboard
                                </a>
                                <a href="{{ route('proveedor.historial', $proveedor->idProveedor) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-history me-1"></i>Historial Compras
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Modal de Confirmación para Eliminar Documento --}}
        <div class="modal fade" id="eliminarDocumentoModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro que desea eliminar este documento?</p>
                        <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmarEliminar">
                            <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content || 
                      document.querySelector('input[name="_token"]')?.value;

        // Elementos del DOM
        const personaNatural = document.getElementById('persona_natural');
        const personaJuridica = document.getElementById('persona_juridica');
        const labelNombre = document.getElementById('label_nombre');
        const asteriscoPaterno = document.getElementById('asterisco_paterno');
        const asteriscoRuc = document.getElementById('asterisco_ruc');
        const helpRuc = document.getElementById('help_ruc');
        const rucInput = document.getElementById('rucProveedor');
        const apellidoPaternoInput = document.getElementById('apellidoPaterno');
        
        // Tips
        const tipsNatural = document.getElementById('tips_natural');
        const tipsJuridica = document.getElementById('tips_juridica');

        // Vista previa del nombre
        const previewNombre = document.getElementById('preview_nombre_completo');
        const nombreInput = document.getElementById('nombre');
        const apellidoMaternoInput = document.getElementById('apellidoMaterno');

        function actualizarFormulario() {
            if (personaJuridica.checked) {
                // Persona Jurídica
                labelNombre.textContent = 'Razón Social';
                asteriscoPaterno.classList.add('d-none');
                asteriscoRuc.classList.remove('d-none');
                helpRuc.textContent = 'Obligatorio para personas jurídicas (11 dígitos)';
                rucInput.setAttribute('required', 'required');
                apellidoPaternoInput.removeAttribute('required');
                
                // Tips
                tipsNatural.classList.add('d-none');
                tipsJuridica.classList.remove('d-none');
                
                // Placeholders
                nombreInput.placeholder = 'Razón social de la empresa';
                apellidoPaternoInput.placeholder = 'Opcional';
                apellidoMaternoInput.placeholder = 'Opcional';
            } else {
                // Persona Natural
                labelNombre.textContent = 'Nombres';
                asteriscoPaterno.classList.remove('d-none');
                asteriscoRuc.classList.add('d-none');
                helpRuc.textContent = 'Opcional para personas naturales';
                rucInput.removeAttribute('required');
                apellidoPaternoInput.setAttribute('required', 'required');
                
                // Tips
                tipsNatural.classList.remove('d-none');
                tipsJuridica.classList.add('d-none');
                
                // Placeholders
                nombreInput.placeholder = 'Nombres completos';
                apellidoPaternoInput.placeholder = 'Apellido paterno';
                apellidoMaternoInput.placeholder = 'Apellido materno';
            }
        }

        // Vista previa del nombre completo
        function actualizarVistaPrevia() {
            const nombre = nombreInput.value.trim();
            const paterno = apellidoPaternoInput.value.trim();
            const materno = apellidoMaternoInput.value.trim();
            
            if (personaJuridica.checked) {
                previewNombre.innerHTML = nombre || '<em class="text-muted">Razón social</em>';
            } else {
                let nombreCompleto = nombre;
                if (paterno) nombreCompleto += ' ' + paterno;
                if (materno) nombreCompleto += ' ' + materno;
                
                previewNombre.innerHTML = nombreCompleto || '<em class="text-muted">Nombre completo</em>';
            }
        }

        // Event listeners
        personaNatural.addEventListener('change', actualizarFormulario);
        personaJuridica.addEventListener('change', actualizarFormulario);
        nombreInput.addEventListener('input', actualizarVistaPrevia);
        apellidoPaternoInput.addEventListener('input', actualizarVistaPrevia);
        apellidoMaternoInput.addEventListener('input', actualizarVistaPrevia);

        // Validación de RUC (solo números, 11 dígitos)
        rucInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });

        // Validación de teléfono (solo números)
        document.getElementById('telefono').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+\-\s]/g, '').slice(0, 15);
        });

        // Vista previa de documentos
        const documentosInput = document.getElementById('documentos');
        const previewContainer = document.getElementById('preview-container');
        const previewList = document.getElementById('preview-list');

        documentosInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            if (files.length > 0) {
                previewContainer.classList.remove('d-none');
                previewList.innerHTML = '';

                files.forEach((file, index) => {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    const fileIcon = getFileIcon(file);

                    const listItem = document.createElement('div');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="${fileIcon} me-2"></i>
                            <div>
                                <small class="fw-bold">${file.name}</small><br>
                                <small class="text-muted">${fileSize} MB</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-file" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewList.appendChild(listItem);
                });
            } else {
                previewContainer.classList.add('d-none');
            }
        });

        // Función para obtener icono según tipo de archivo
        function getFileIcon(file) {
            const type = file.type;
            const name = file.name.toLowerCase();
            if (type === 'application/pdf' || name.endsWith('.pdf')) {
                return 'fas fa-file-pdf text-danger';
            }
            if (type.startsWith('image/') || name.match(/\.(jpg|jpeg|png)$/)) {
                return 'fas fa-file-image text-primary';
            }
            if (type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || name.endsWith('.docx')) {
                return 'fas fa-file-word text-info';
            }
            return 'fas fa-file text-secondary';
        }

        // Remover archivo de la vista previa
        previewList.addEventListener('click', function(e) {
            if (e.target.closest('.remove-file')) {
                const index = parseInt(e.target.closest('.remove-file').dataset.index);
                const dt = new DataTransfer();
                const files = Array.from(documentosInput.files);
                
                files.forEach((file, i) => {
                    if (i !== index) dt.items.add(file);
                });
                
                documentosInput.files = dt.files;
                documentosInput.dispatchEvent(new Event('change'));
            }
        });

        // Manejo de eliminación de documentos existentes
        const modal = new bootstrap.Modal(document.getElementById('eliminarDocumentoModal'));
        let archivoAEliminar = null;

        document.querySelectorAll('.eliminar-documento').forEach(btn => {
            btn.addEventListener('click', function() {
                archivoAEliminar = this.dataset.archivo;
                modal.show();
            });
        });

        document.getElementById('confirmarEliminar').addEventListener('click', function() {
            if (archivoAEliminar && token) {
                fetch(`{{ route('proveedor.eliminarDocumento', $proveedor->idProveedor) }}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ archivo: archivoAEliminar })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remover el elemento del DOM
                        const elemento = document.querySelector(`[data-archivo="${archivoAEliminar}"]`).closest('.list-group-item');
                        if (elemento) {
                            elemento.remove();
                        }
                        
                        // Mostrar mensaje de éxito
                        mostrarMensaje('Documento eliminado exitosamente', 'success');
                        
                        // Actualizar contador si existe
                        const contadorElement = document.querySelector('h6:contains("Documentos Actuales")');
                        if (contadorElement) {
                            const documentosRestantes = document.querySelectorAll('.eliminar-documento').length - 1;
                            contadorElement.textContent = `Documentos Actuales (${documentosRestantes})`;
                        }
                    } else {
                        mostrarMensaje(data.message || 'Error al eliminar el documento', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje('Error de conexión al eliminar el documento', 'error');
                })
                .finally(() => {
                    modal.hide();
                    archivoAEliminar = null;
                });
            }
        });

        // Función para mostrar mensajes
        function mostrarMensaje(mensaje, tipo) {
            const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
            const icono = tipo === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="${icono} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Validaciones adicionales para edición
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let errores = [];
            
            // Validar RUC para persona jurídica
            if (personaJuridica.checked) {
                const ruc = rucInput.value.trim();
                if (!ruc) {
                    errores.push('El RUC es obligatorio para personas jurídicas');
                } else if (ruc.length !== 11) {
                    errores.push('El RUC debe tener exactamente 11 dígitos');
                }
            }

            // Validar nombres para persona natural
            if (personaNatural.checked) {
                const apellido = apellidoPaternoInput.value.trim();
                if (!apellido) {
                    errores.push('El apellido paterno es obligatorio para personas naturales');
                }
            }

            // Validar email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errores.push('El formato del correo electrónico no es válido');
            }

            // Validar teléfono
            const telefono = document.getElementById('telefono').value.trim();
            if (telefono.length < 7) {
                errores.push('El teléfono debe tener al menos 7 dígitos');
            }

            if (errores.length > 0) {
                e.preventDefault();
                mostrarMensaje('Corrija los siguientes errores:<br>• ' + errores.join('<br>• '), 'error');
            }
        });

        // Confirmación antes de guardar cambios importantes
        form.addEventListener('submit', function(e) {
            const estadoSelect = document.getElementById('estado');
            if (estadoSelect.value === 'bloqueado') {
                const confirmacion = confirm('¿Está seguro de bloquear este proveedor? Esta acción afectará las operaciones comerciales.');
                if (!confirmacion) {
                    e.preventDefault();
                }
            }
        });

        // Auto-completar campos basado en el tipo de persona al cargar
        function inicializarFormulario() {
            // Determinar el tipo actual
            const tieneApellidos = apellidoPaternoInput.value.trim() || apellidoMaternoInput.value.trim();
            const tieneRUC = rucInput.value.trim();
            
            // Si no tiene apellidos pero tiene RUC, probablemente es jurídica
            if (!tieneApellidos && tieneRUC && tieneRUC.length === 11) {
                personaJuridica.checked = true;
            }
            
            actualizarFormulario();
            actualizarVistaPrevia();
        }

        // Feedback visual para cambios
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const valorOriginal = input.value;
            
            input.addEventListener('change', function() {
                if (this.value !== valorOriginal) {
                    this.classList.add('border-warning');
                    this.title = 'Campo modificado - requiere guardar cambios';
                } else {
                    this.classList.remove('border-warning');
                    this.title = '';
                }
            });
        });

        // Prevenir pérdida de cambios
        let cambiosGuardados = false;
        
        form.addEventListener('submit', function() {
            cambiosGuardados = true;
        });

        window.addEventListener('beforeunload', function(e) {
            const hayChanges = Array.from(inputs).some(input => input.classList.contains('border-warning'));
            
            if (hayChanges && !cambiosGuardados) {
                e.preventDefault();
                e.returnValue = '¿Está seguro de salir sin guardar los cambios?';
                return e.returnValue;
            }
        });

        // Botón de cancelar con confirmación
        document.querySelector('a[href*="index"]').addEventListener('click', function(e) {
            const hayChanges = Array.from(inputs).some(input => input.classList.contains('border-warning'));
            
            if (hayChanges) {
                const confirmacion = confirm('Hay cambios sin guardar. ¿Está seguro de cancelar?');
                if (!confirmacion) {
                    e.preventDefault();
                }
            }
        });

        // Inicializar todo
        inicializarFormulario();
    });
    </script>

    <style>
    .btn-check:checked + .btn-outline-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .border-warning {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.1rem rgba(255, 193, 7, 0.25);
    }

    .list-group-item {
        border-left: none;
        border-right: none;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    #preview_nombre_completo {
        font-weight: 500;
        min-height: 38px;
        display: flex;
        align-items: center;
    }

    .alert.position-fixed {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .eliminar-documento:hover {
        transform: scale(1.1);
        transition: transform 0.2s;
    }

    .btn-group-sm .btn {
        transition: all 0.2s ease;
    }

    .btn-group-sm .btn:hover {
        transform: translateY(-1px);
    }

    /* Estilos para indicar campos modificados */
    .border-warning::placeholder {
        color: #856404 !important;
    }

    /* Mejorar la apariencia de las estadísticas */
    .alert-info .border {
        background-color: rgba(13, 202, 240, 0.1);
        border-color: #0dcaf0 !important;
    }

    /* Estilo para evaluaciones */
    .fa-star.text-warning {
        text-shadow: 0 0 3px rgba(255, 193, 7, 0.5);
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .card-body {
            height: auto !important;
        }
        
        .col-md-8, .col-md-4 {
            margin-bottom: 1rem;
        }
        
        .alert.position-fixed {
            left: 10px;
            right: 10px;
            min-width: auto;
        }
    }
    </style>
@endsection