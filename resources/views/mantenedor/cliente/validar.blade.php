<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Validación de Acta de Nacimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="max-w-2xl mx-auto mt-12 bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-4">
            Validación Oficial de Acta de Nacimiento
        </h1>

        <div class="border-t border-b py-6 my-4">
            <p class="text-gray-700"><strong>ID del Acta:</strong> #{{ $acta->id_acta_nacimiento }}</p>
            <p class="text-gray-700"><strong>Nombre del Recién Nacido:</strong>
                {{ $acta->recienNacido->nombres ?? 'N/A' }}
                {{ $acta->recienNacido->apellido_paterno ?? '' }}
                {{ $acta->recienNacido->apellido_materno ?? '' }}
            </p>
            <p class="text-gray-700"><strong>Fecha de Nacimiento:</strong>
                {{ \Carbon\Carbon::parse($acta->recienNacido->fecha_nacimiento ?? '')->format('d/m/Y') ?? 'N/A' }}
            </p>
            <p class="text-gray-700"><strong>Registrado por:</strong>
                {{ $acta->usuario->nombre_usuario ?? 'N/A' }}
            </p>
            <p class="text-gray-700"><strong>Estado:</strong>
                <span class="font-semibold {{ $acta->estado == 1 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $acta->estado == 1 ? 'VÁLIDO' : 'ANULADO / INACTIVO' }}
                </span>
            </p>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            Este acta fue generada electrónicamente desde el Sistema de Registro Civil.
            Para mayor información comuníquese con la municipalidad correspondiente.
        </p>
    </div>
</body>

</html>
