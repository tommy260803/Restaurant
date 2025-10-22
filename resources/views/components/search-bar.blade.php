<form method="GET" action="{{ route('persona.index') }}" class="search-form" id="search-form">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="buscar_por" class="form-label">Buscar por:</label>
            <select name="buscar_por" id="buscar_por" class="form-select">
                <option value="dni" {{ request('buscar_por') == 'dni' ? 'selected' : '' }}>DNI</option>
                <option value="nombres" {{ request('buscar_por') == 'nombres' ? 'selected' : '' }}>Nombres</option>
                <option value="apellidos" {{ request('buscar_por') == 'apellidos' ? 'selected' : '' }}>Apellidos
                </option>
                <option value="todo" {{ request('buscar_por') == 'todo' ? 'selected' : '' }}>Todos los campos</option>
            </select>
        </div>

        <div class="col-md-6">
            <label for="buscarpor" class="form-label">Término de búsqueda:</label>
            <input type="text" name="buscarpor" id="buscarpor" class="form-control"
                placeholder="Ingrese el término a buscar..." value="{{ request('buscarpor') }}" autocomplete="off">
        </div>

        <div class="col-md-3">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success" id="search-btn">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
                <a href="{{ route('persona.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Limpiar
                </a>
            </div>
        </div>
    </div>
</form>
