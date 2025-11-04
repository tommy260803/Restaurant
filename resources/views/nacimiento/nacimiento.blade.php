<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de Nacimiento</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.5;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            margin-bottom: 10px;
        }

        td {
            padding: 4px;
            vertical-align: top;
        }

        .firma {
            margin-top: 40px;
            text-align: center;
        }

        .qr {
            text-align: right;
            margin-top: 20px;
        }

        .img-firma {
            margin-top: 10px;
            height: 80px;
        }
    </style>
</head>

<body>

    <div class="title">ACTA DE NACIMIENTO</div>

    <div class="section">
        <div class="section-title">Datos del Nacimiento</div>
        <table>
            <tr>
                <td><strong>Libro:</strong> {{ $acta->folio->libro->numero_libro ?? 'N/A' }}</td>
                <td><strong>Folio:</strong> {{ $acta->folio->numero_folio ?? 'N/A' }}</td>
                <td><strong>Fecha Registro:</strong> {{ \Carbon\Carbon::parse($acta->fecha_registro)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Recién Nacido</div>
        <table>
            <tr>
                <td><strong>Nombres:</strong> {{ $acta->nombres }}</td>
                <td><strong>Sexo:</strong> {{ $acta->sexo == 'M' ? 'Masculino' : 'Femenino' }}</td>
                <td><strong>Fecha de Nacimiento:</strong>
                    {{ \Carbon\Carbon::parse($acta->fecha_nacimiento)->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Padres</div>
        <table>
            <tr>
                <td><strong>Padre:</strong> {{ $acta->nombre_padre ?? 'N/A' }}</td>
                <td><strong>DNI:</strong> {{ $acta->dni_padre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Madre:</strong> {{ $acta->nombre_madre ?? 'N/A' }}</td>
                <td><strong>DNI:</strong> {{ $acta->dni_madre ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Registrador y Autoridades</div>
        <p><strong>Registrador:</strong> {{ $acta->usuario->nombre_usuario ?? 'N/A' }}</p>
        <p><strong>Alcalde:</strong> {{ $acta->alcalde->persona->nombres ?? '' }}
            {{ $acta->alcalde->persona->apellido_paterno ?? '' }}
            {{ $acta->alcalde->persona->apellido_materno ?? '' }}</p>
    </div>

    <div class="firma">
        <p>Firma del Registrador</p>
        @if ($firma)
            <img src="{{ $firma }}" alt="Firma del Alcalde" style="width:150px;">
        @endif
    </div>

    <div class="qr">
        <img src="{{ $qr }}" alt="Código QR">
        <p style="font-size: 10px; color: #555;">Escanee para validar esta acta</p>
    </div>

</body>

</html>
