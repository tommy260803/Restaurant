{{-- filepath: vsls:/resources/views/mantenedor/matrimonio/exportar.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Exportar Matrimonios</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-4 py-6">

        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Exportar Matrimonios</h1>
            <a href="{{ route('matrimonio.index') }}" class="text-blue-600 hover:underline no-print">← Volver</a>
        </div>

        <div class="mb-6 flex space-x-4 no-print">
            <a href="{{ route('matrimonio.exportarPDFMasivo') }}" target="_blank"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center space-x-2 shadow">
                <i class="fas fa-file-pdf"></i><span>Exportar PDF</span>
            </a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center space-x-2 shadow">
                <i class="fas fa-print"></i><span>Imprimir</span>
            </button>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-x-auto max-h-[600px]">
            <table class="min-w-full divide-y divide-gray-300 text-sm text-gray-700">
                <thead class="bg-gray-800 text-white sticky top-0">
                    <tr>
                        <th scope="col" class="px-4 py-3">#</th>
                        <th scope="col" class="px-4 py-3">N° Libro</th>
                        <th scope="col" class="px-4 py-3">N° Folio</th>
                        <th scope="col" class="px-4 py-3">DNI Cónyuge 1</th>
                        <th scope="col" class="px-4 py-3">Nombre Cónyuge 1</th>
                        <th scope="col" class="px-4 py-3">DNI Cónyuge 2</th>
                        <th scope="col" class="px-4 py-3">Nombre Cónyuge 2</th>
                        <th scope="col" class="px-4 py-3">Fecha Matrimonio</th>
                        <th scope="col" class="px-4 py-3">Régimen</th>
                        <th scope="col" class="px-4 py-3">Registrador</th>
                        <th scope="col" class="px-4 py-3">Alcalde</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $contador = 1; @endphp
                    @forelse($matrimonios as $item)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $contador++ }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->folio->libro->numero_libro ?? '' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->folio->numero_folio ?? '' }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $item->dni_conyuge1 }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->conyuge1->nombres ?? '' }} {{ $item->conyuge1->apellido_paterno ?? '' }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $item->dni_conyuge2 }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->conyuge2->nombres ?? '' }} {{ $item->conyuge2->apellido_paterno ?? '' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($item->fecha_matrimonio)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2">{{ $item->regimen_matrimonial }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->usuario->nombre_usuario ?? $item->id_usuario }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->alcalde->persona->nombres ?? $item->id_alcalde }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-gray-500 py-4">No hay registros disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>