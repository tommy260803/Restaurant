@props(['personas'])

@if ($personas->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="25%">Nombres</th>
                    <th width="25%">Apellidos</th>
                    <th width="12%">DNI</th>
                    <th width="15%">Fecha Nac.</th>
                    <th width="15%" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($personas as $objpersona)
                    <tr>
                        <td class="fw-medium">{{ $objpersona->id_persona }}</td>
                        <td>{{ $objpersona->nombres }}</td>
                        <td>{{ $objpersona->apellido_paterno }} {{ $objpersona->apellido_materno }}</td>
                        <td><span class="badge badge-info">{{ $objpersona->dni }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($objpersona->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('persona.show', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-outline-secondary" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('persona.edit', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('confirmarp', $objpersona->id_persona) }}"
                                    class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="no-results">
        <i class="fas fa-search"></i>
        <h4>No se encontraron resultados</h4>
        <p class="text-muted">No hay personas registradas en el sistema.</p>
    </div>
@endif
