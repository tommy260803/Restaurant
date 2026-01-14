{{-- filepath: resources/views/mantenedor/nacimiento/exportar.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Exportar Registros de Nacimiento</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .print-shadow {
                box-shadow: none !important;
            }
        }

        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .table-container {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .hover-row:hover {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .btn-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            color: white;
        }

        .badge-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .badge-inactive {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .table-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .table-scroll::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8);
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #94a3b8, #64748b);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 text-gray-800 min-h-screen">
    <div class="container mx-auto px-6 py-8">

        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h1
                        class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 bg-clip-text text-transparent mb-2">
                        <i class="fas fa-baby mr-3"></i>Exportar Registros de Nacimiento
                    </h1>
                    <p class="text-gray-600 text-lg">Gestión y exportación de actas de nacimiento registradas (Solo
                        registros activos)</p>
                </div>
                <a href="{{ route('nacimiento.index') }}"
                    class="no-print inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-gray-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Listado
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        @php
            $nacimientosActivos = $nacimientos->where('estado', 'vigente');
            $totalActivos = $nacimientosActivos->count();
            $mesActual = $nacimientosActivos->where('fecha_registro', '>=', now()->startOfMonth())->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 no-print">
            <div class="stats-card p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Registros Activos</p>
                        <p class="text-3xl font-bold">{{ $totalActivos }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Registros Mostrados</p>
                        <p class="text-3xl font-bold">{{ $totalActivos }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Mes Actual</p>
                        <p class="text-3xl font-bold">{{ $mesActual }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-calendar text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mb-8 flex flex-wrap gap-4 no-print">
            <a href="{{ route('nacimiento.exportarPDFMasivo') }}" target="_blank"
                class="btn-pdf text-white px-6 py-3 rounded-xl flex items-center space-x-3 font-semibold transition-all duration-300">
                <i class="fas fa-file-pdf text-lg"></i>
                <span>Exportar PDF</span>
            </a>

            <button onclick="window.print()"
                class="btn-modern text-white px-6 py-3 rounded-xl flex items-center space-x-3 font-semibold">
                <i class="fas fa-print text-lg"></i>
                <span>Imprimir Listado</span>
            </button>

            <button onclick="exportToExcel()"
                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl flex items-center space-x-3 font-semibold transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <i class="fas fa-file-excel text-lg"></i>
                <span>Exportar Excel</span>
            </button>
        </div>

        {{-- Data Table --}}
        <div class="table-container print-shadow shadow-2xl rounded-2xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto table-scroll max-h-[700px]">
                <table class="min-w-full divide-y divide-gray-300 text-sm">
                    <thead class="gradient-header text-white sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">#
                            </th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">ID
                                Acta</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Libro</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Folio</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Recién Nacido</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">Sexo
                            </th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Fecha
                                Nac.</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Padre
                            </th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">DNI
                                Padre</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Madre
                            </th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">DNI
                                Madre</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Fecha
                                Reg.</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Alcalde</th>
                            <th scope="col" class="px-4 py-4 text-center font-semibold uppercase tracking-wider">
                                Registrador</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $contador = 1; @endphp
                        @forelse($nacimientosActivos as $item)
                            <tr class="hover-row">
                                <td class="px-4 py-4 whitespace-nowrap text-center font-medium text-gray-900">
                                    {{ $contador++ }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap font-bold text-blue-600">
                                    #{{ $item->id_acta_nacimiento }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                    {{ $item->folio->libro->numero_libro ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                    {{ $item->folio->numero_folio ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-gray-900 font-semibold">
                                    @if ($item->recienNacido)
                                        {{ $item->recienNacido->nombre ?? '' }}
                                        {{ $item->recienNacido->apellido_paterno ?? '' }}
                                        {{ $item->recienNacido->apellido_materno ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        {{ ($item->recienNacido->sexo ?? '') == 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        <i
                                            class="fas {{ ($item->recienNacido->sexo ?? '') == 'M' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                        {{ ($item->recienNacido->sexo ?? '') == 'M' ? 'M' : 'F' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                    {{ $item->recienNacido->fecha_nacimiento ? \Carbon\Carbon::parse($item->recienNacido->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-sm">
                                    @if ($item->padre)
                                        {{ $item->padre->nombres ?? '' }}
                                        {{ $item->padre->apellido_paterno ?? '' }}
                                        {{ $item->padre->apellido_materno ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-mono text-sm">
                                    @if ($item->padre)
                                        <span class="bg-gray-100 px-2 py-1 rounded text-gray-800">
                                            {{ $item->padre->dni ?? 'N/A' }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-sm">
                                    @if ($item->madre)
                                        {{ $item->madre->nombres ?? '' }}
                                        {{ $item->madre->apellido_paterno ?? '' }}
                                        {{ $item->madre->apellido_materno ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-mono text-sm">
                                    @if ($item->madre)
                                        <span class="bg-gray-100 px-2 py-1 rounded text-gray-800">
                                            {{ $item->madre->dni ?? 'N/A' }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                    {{ $item->fecha_registro ? \Carbon\Carbon::parse($item->fecha_registro)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-gray-700 font-medium text-sm">
                                    @if ($item->alcalde && $item->alcalde->persona)
                                        {{ $item->alcalde->persona->nombres ?? '' }}
                                        {{ $item->alcalde->persona->apellido_paterno ?? '' }}
                                        {{ $item->alcalde->persona->apellido_materno ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-gray-700 font-medium text-sm">
                                    @if ($item->usuario)
                                        {{ $item->usuario->nombre_usuario ?? ($item->usuario->nombre ?? ($item->usuario->nombres ?? 'Usuario #' . $item->id_usuario)) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-gray-500 py-12">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-xl font-medium">No hay registros de nacimiento activos
                                            disponibles</p>
                                        <p class="text-gray-400 mt-2">Los registros activos aparecerán aquí una vez que
                                            sean creados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Info (only for print) --}}
        <div class="mt-8 text-center text-gray-500 text-sm print-only" style="display: none;">
            <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }} | Total de registros activos:
                {{ $totalActivos }}</p>
        </div>

    </div>

    <script>
        function exportToExcel() {
            // Crear tabla para Excel
            let table = document.querySelector('table');
            let html = table.outerHTML;

            // Crear blob
            let blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });

            // Crear enlace de descarga
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'registros_nacimiento_activos_' + new Date().toISOString().split('T')[0] + '.xls';
            link.click();
        }

        // Mostrar footer solo en impresión
        window.addEventListener('beforeprint', function() {
            document.querySelector('.print-only').style.display = 'block';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.print-only').style.display = 'none';
        });
    </script>

    <style>
        @media print {
            .print-only {
                display: block !important;
            }

            .gradient-header {
                background: #374151 !important;
                -webkit-print-color-adjust: exact;
            }

            .stats-card {
                background: #6b7280 !important;
                -webkit-print-color-adjust: exact;
            }

            .badge-active {
                background: #059669 !important;
                -webkit-print-color-adjust: exact;
            }

            .badge-inactive {
                background: #d97706 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</body>

</html>
