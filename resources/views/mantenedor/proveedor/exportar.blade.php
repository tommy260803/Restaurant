<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Exportar Proveedores</title>

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
            <h1 class="text-2xl font-semibold">Exportar Proveedores</h1>
            <a href="{{ route('proveedor.index') }}" class="text-blue-600 hover:underline no-print">← Volver</a>
        </div>

        <div class="mb-6 flex space-x-4 no-print">
            <a href="{{ route('proveedor.exportarPDFMasivo') }}" target="_blank"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center space-x-2 shadow">
                <i class="fas fa-file-pdf"></i><span>Exportar PDF</span>
            </a>
            <a href="{{ route('proveedor.exportarExcel') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center space-x-2 shadow">
                <i class="fas fa-file-excel"></i><span>Exportar Excel</span>
            </a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center space-x-2 shadow">
                <i class="fas fa-print"></i><span>Imprimir</span>
            </button>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-x-auto max-h-[600px]">
            <table class="min-w-full divide-y divide-gray-300 text-sm text-gray-700">
                <thead class="bg-blue-800 text-white sticky top-0">
                    <tr>
                        <th scope="col" class="px-4 py-3">#</th>
                        <th scope="col" class="px-4 py-3">Nombre</th>
                        <th scope="col" class="px-4 py-3">Teléfono</th>
                        <th scope="col" class="px-4 py-3">Email</th>
                        <th scope="col" class="px-4 py-3">RUC</th>
                        <th scope="col" class="px-4 py-3">Dirección</th>
                        <th scope="col" class="px-4 py-3">Estado</th>
                        <th scope="col" class="px-4 py-3">Calificación</th>
                        <th scope="col" class="px-4 py-3">Incumplimientos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $contador = 1; @endphp
                    @forelse($proveedores as $item)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $contador++ }}</td>
                            <td class="px-4 py-2">{{ $item->nombre }} {{ $item->apellidoPaterno }}</td>
                            <td class="px-4 py-2">{{ $item->telefono ?: '-' }}</td>
                            <td class="px-4 py-2">{{ $item->email ?: '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->rucProveedor ?: '-' }}</td>
                            <td class="px-4 py-2">{{ $item->direccion ?: '-' }}</td>
                            <td class="px-4 py-2 text-center">
                                @if($item->estado == 'activo')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded">{{ ucfirst($item->estado) }}</span>
                                @elseif($item->estado == 'bloqueado')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded">{{ ucfirst($item->estado) }}</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded">{{ ucfirst($item->estado) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">{{ $item->calificacion > 0 ? $item->calificacion . '/5' : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->incumplimientos }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-4">No hay registros disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>