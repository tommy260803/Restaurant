<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Proveedores</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 3px 0;
            color: #2c3e50;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
        }
        .fecha {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 10px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #2c3e50;
        }
        td {
            padding: 8px 5px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .estado-activo {
            color: #27ae60;
            font-weight: bold;
        }
        .estado-inactivo {
            color: #95a5a6;
            font-weight: bold;
        }
        .estado-bloqueado {
            color: #e74c3c;
            font-weight: bold;
        }
        .numero {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .estadisticas {
            margin: 20px 0;
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-around;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">REPORTE DE PROVEEDORES</div>
        <div class="subtitle">Gestión de Proveedores del Sistema</div>
        <div class="fecha">Generado: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <div class="estadisticas">
        <div class="stat-item">
            <div class="stat-number">{{ count($proveedores) }}</div>
            <div class="stat-label">Total Proveedores</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $proveedores->where('estado', 'activo')->count() }}</div>
            <div class="stat-label">Activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $proveedores->where('estado', 'inactivo')->count() }}</div>
            <div class="stat-label">Inactivos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $proveedores->where('estado', 'bloqueado')->count() }}</div>
            <div class="stat-label">Bloqueados</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>RUC</th>
                <th>Estado</th>
                <th>Calificación</th>
                <th>Incumplimientos</th>
            </tr>
        </thead>
        <tbody>
            @php $contador = 1; @endphp
            @forelse($proveedores as $item)
            <tr>
                <td class="numero">{{ $contador++ }}</td>
                <td>{{ $item->nombre }} {{ $item->apellidoPaterno }}</td>
                <td>{{ $item->telefono ?: '-' }}</td>
                <td>{{ $item->email ?: '-' }}</td>
                <td>{{ $item->rucProveedor ?: '-' }}</td>
                <td>
                    @if($item->estado === 'activo')
                        <span class="estado-activo">Activo</span>
                    @elseif($item->estado === 'bloqueado')
                        <span class="estado-bloqueado">Bloqueado</span>
                    @else
                        <span class="estado-inactivo">Inactivo</span>
                    @endif
                </td>
                <td class="numero">{{ $item->calificacion > 0 ? $item->calificacion . '/5' : '-' }}</td>
                <td class="numero">{{ $item->incumplimientos }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">No hay proveedores registrados</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de Gestión de Proveedores</p>
    </div>
</body>
</html>