@extends('layouts.plantilla')

@section('contenido')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="container">
        <h4 class="text-primary mb-3"><i class="fas fa-user-tie me-2"></i>Registro de Proveedor</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Corrige los errores:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('proveedor.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Columna Izquierda: Datos del Proveedor -->
                <div class="col-md-8">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-primary py-2 text-white">📄 Datos del Proveedor</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">

                            {{-- Tipo de Persona --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-person-badge me-2"></i>Tipo de Persona
                            </h6>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="btn-group w-100" role="group" aria-label="Tipo de persona">
                                        <input type="radio" class="btn-check" name="tipo_persona" id="persona_natural" 
                                            value="natural" {{ old('tipo_persona', 'natural') == 'natural' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="persona_natural">
                                            <i class="bi bi-person me-2"></i>Persona Natural
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_persona" id="persona_juridica" 
                                            value="juridica" {{ old('tipo_persona') == 'juridica' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="persona_juridica">
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
                                        value="{{ old('nombre') }}" required 
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
                                        value="{{ old('apellidoPaterno') }}" 
                                        placeholder="Apellido paterno">
                                    @error('apellidoPaterno')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidoMaterno" class="form-label">Apellido Materno</label>
                                    <input type="text" name="apellidoMaterno" id="apellidoMaterno" 
                                        class="form-control @error('apellidoMaterno') is-invalid @enderror" 
                                        value="{{ old('apellidoMaterno') }}" 
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
                                        value="{{ old('rucProveedor') }}" 
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
                                        value="{{ old('telefono') }}" required 
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
                                    value="{{ old('email') }}" required 
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
                                    placeholder="Dirección completa del proveedor">{{ old('direccion') }}</textarea>
                                @error('direccion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Información Adicional --}}
                            <h6 class="text-dark fw-bold border-bottom border-2 border-dark pb-2 mb-3 fs-5">
                                <i class="bi bi-info-circle me-2"></i>Información Adicional
                            </h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado Inicial</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                    <small class="text-muted">Por defecto se registra como activo</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Vista Previa del Nombre</label>
                                    <div class="form-control bg-light" id="preview_nombre_completo">
                                        <em class="text-muted">Escriba los nombres para ver la vista previa</em>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-save me-1"></i>Registrar Proveedor
                                </button>
                                <a href="{{ route('proveedor.index') }}" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Documentación -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <h5 class="card-title mb-0 text-center bg-dark py-2 text-white">📎 Documentación</h5>
                        <div class="card-body" style="height:70vh;overflow-y:auto">
                            
                            <div class="alert alert-info mb-3">
                                <h6><i class="fas fa-info-circle me-1"></i>Documentos Sugeridos</h6>
                                <ul class="mb-0 small">
                                    <li id="doc_natural">DNI o cédula de identidad</li>
                                    <li id="doc_juridica" class="d-none">Ficha RUC</li>
                                    <li id="doc_juridica2" class="d-none">Constitución de empresa</li>
                                    <li>Certificados de calidad</li>
                                    <li>Referencias comerciales</li>
                                    <li>Catálogos de productos</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <label for="documentos" class="form-label">Documentos Adjuntos</label>
                                <input type="file" name="documentos[]" id="documentos" 
                                    class="form-control @error('documentos.*') is-invalid @enderror" 
                                    multiple accept=".pdf,.jpg,.jpeg,.png,.docx">
                                <small class="text-muted">
                                    Opcional. Archivos permitidos: PDF, JPG, PNG, DOCX. Máx 2MB c/u.
                                </small>
                                @error('documentos.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Vista previa de archivos seleccionados --}}
                            <div id="preview-container" class="d-none">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-eye me-1"></i>Archivos Seleccionados
                                </h6>
                                <div id="preview-list" class="list-group mb-3"></div>
                            </div>

                            {{-- Información importante --}}
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-1"></i>Importante</h6>
                                <ul class="mb-0 small">
                                    <li>Los campos marcados con <span class="text-danger">*</span> son obligatorios</li>
                                    <li>Para personas jurídicas, el RUC es obligatorio</li>
                                    <li>Verifique que el email sea válido para notificaciones</li>
                                    <li>Los documentos ayudan en la evaluación del proveedor</li>
                                </ul>
                            </div>

                            {{-- Tips según tipo de persona --}}
                            <div class="alert alert-success" id="tips_container">
                                <h6><i class="fas fa-lightbulb me-1"></i>Tips</h6>
                                <div id="tips_natural">
                                    <small>
                                        <strong>Persona Natural:</strong><br>
                                        • Complete nombres y apellidos<br>
                                        • RUC es opcional<br>
                                        • Adjunte DNI si es posible
                                    </small>
                                </div>
                                <div id="tips_juridica" class="d-none">
                                    <small>
                                        <strong>Persona Jurídica:</strong><br>
                                        • Nombre = Razón Social<br>
                                        • RUC es obligatorio (11 dígitos)<br>
                                        • Adjunte ficha RUC y constitución
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const personaNatural = document.getElementById('persona_natural');
        const personaJuridica = document.getElementById('persona_juridica');
        const labelNombre = document.getElementById('label_nombre');
        const apellidosContainer = document.getElementById('apellidos_container');
        const asteriscoPaterno = document.getElementById('asterisco_paterno');
        const asteriscoRuc = document.getElementById('asterisco_ruc');
        const helpRuc = document.getElementById('help_ruc');
        const rucInput = document.getElementById('rucProveedor');
        const apellidoPaternoInput = document.getElementById('apellidoPaterno');
        
        // Elementos de documentos sugeridos
        const docNatural = document.getElementById('doc_natural');
        const docJuridica = document.getElementById('doc_juridica');
        const docJuridica2 = document.getElementById('doc_juridica2');
        
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
                
                // Documentos sugeridos
                docNatural.classList.add('d-none');
                docJuridica.classList.remove('d-none');
                docJuridica2.classList.remove('d-none');
                
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
                
                // Documentos sugeridos
                docNatural.classList.remove('d-none');
                docJuridica.classList.add('d-none');
                docJuridica2.classList.add('d-none');
                
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
                    const fileIcon = getFileIcon(file.type);
                    
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
        function getFileIcon(fileType) {
            const icons = {
                'application/pdf': 'fas fa-file-pdf text-danger',
                'image/jpeg': 'fas fa-file-image text-primary',
                'image/jpg': 'fas fa-file-image text-primary',
                'image/png': 'fas fa-file-image text-primary',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fas fa-file-word text-info'
            };
            return icons[fileType] || 'fas fa-file text-secondary';
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

        // Inicializar
        actualizarFormulario();
        actualizarVistaPrevia();
    });
    </script>

    <style>
    .btn-check:checked + .btn-outline-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
    </style>
@endsection