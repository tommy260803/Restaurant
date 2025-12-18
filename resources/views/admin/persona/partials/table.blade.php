@if ($personas->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="25%">Nombres</th>
                    <th width="25%">Apellidos</th>
                    <th width="20%">DNI</th>
                    <th width="20%" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($personas as $objpersona)
                    <tr>
                        <td class="fw-medium">{{ $objpersona->id_persona }}</td>
                        <td>{{ $objpersona->nombres }}</td>
                        <td>{{ $objpersona->apellido_paterno }} {{ $objpersona->apellido_materno }}</td>
                        <td><span class="badge bg-info text-white">{{ $objpersona->dni }}</span></td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('persona.show', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-outline-secondary" title="Ver detalles">
                                    <i class='bx  bx-bullseye'></i>
                                </a>
                                <a href="{{ route('persona.edit', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-warning" title="Editar">
                                    <i class='bx  bx-edit'></i>
                                </a>
                                <a href="{{ route('confirmarp', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class='bx  bx-trash'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación incluida solo si no estás en un componente --}}
        {{ $personas->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="no-results text-center my-5">
        <i class="fas fa-search fa-2x text-muted mb-3"></i>
        <h4>No se encontraron resultados</h4>
        <p class="text-muted">No hay personas registradas en el sistema.</p>
    </div>
@endif
