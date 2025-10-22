<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Nacimiento</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 3px 0;
        }
        .section {
            margin: 20px 0;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 12px;
            text-transform: uppercase;
            background-color: #f5f5f5;
            padding: 5px;
            border-left: 4px solid #333;
        }
        .row {
            margin: 10px 0;
            display: flex;
            align-items: baseline;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 180px;
            flex-shrink: 0;
        }
        .value {
            border-bottom: 1px dotted #333;
            display: inline-block;
            min-width: 300px;
            flex-grow: 1;
            padding-bottom: 2px;
        }
        .signature-section {
            margin-top: 80px;
            text-align: center;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-top: 60px;
            margin: 0 2.5%;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .small-text {
            font-size: 11px;
        }
        .status-activo {
            color: green;
            font-weight: bold;
        }
        .status-inactivo {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">REPÚBLICA DEL PERÚ</div>
        <div class="title">MUNICIPALIDAD DISTRITAL</div>
        <div class="title">REGISTRO CIVIL</div>
        <div class="title">ACTA DE NACIMIENTO N° {{ str_pad($nacimiento->id_acta_nacimiento, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section">
        <div class="section-title">I. Información Registral</div>
        <div class="row">
            <span class="label">Número de Libro:</span>
            <span class="value">{{ $nacimiento->folio && $nacimiento->folio->libro ? $nacimiento->folio->libro->numero_libro : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Número de Folio:</span>
            <span class="value">{{ $nacimiento->folio ? $nacimiento->folio->numero_folio : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Fecha de Registro:</span>
            <span class="value">{{ $nacimiento->fecha_registro ? \Carbon\Carbon::parse($nacimiento->fecha_registro)->format('d/m/Y') : date('d/m/Y') }}</span>
        </div>
       
    </div>

    <div class="section">
        <div class="section-title">II. Datos del Recién Nacido</div>
        <div class="row">
            <span class="label">Nombres:</span>
            <span class="value">{{ $nacimiento->recienNacido->nombre ?? 'No registrado' }}</span>
        </div>
        <div class="row">
            <span class="label">Apellido Paterno:</span>
            <span class="value">{{ $nacimiento->recienNacido->apellido_paterno ?? 'No registrado' }}</span>
        </div>
        <div class="row">
            <span class="label">Apellido Materno:</span>
            <span class="value">{{ $nacimiento->recienNacido->apellido_materno ?? 'No registrado' }}</span>
        </div>
        @if($nacimiento->recienNacido && $nacimiento->recienNacido->sexo)
        <div class="row">
            <span class="label">Sexo:</span>
            <span class="value">{{ $nacimiento->recienNacido->sexo == 'M' ? 'Masculino' : ($nacimiento->recienNacido->sexo == 'F' ? 'Femenino' : 'No especificado') }}</span>
        </div>
        @endif
        @if($nacimiento->fecha_nacimiento)
        <div class="row">
            <span class="label">Fecha de Nacimiento:</span>
            <span class="value">{{ \Carbon\Carbon::parse($nacimiento->fecha_nacimiento)->format('d/m/Y') }}</span>
        </div>
        @endif
        @if($nacimiento->hora_nacimiento)
        <div class="row">
            <span class="label">Hora de Nacimiento:</span>
            <span class="value">{{ $nacimiento->hora_nacimiento }}</span>
        </div>
        @endif
    </div>

    @if($nacimiento->madre || $nacimiento->padre)
    <div class="section">
        <div class="section-title">III. Datos de los Padres</div>
        @if($nacimiento->madre)
        <div class="row">
            <span class="label">Madre:</span>
            <span class="value">{{ $nacimiento->madre->nombres ?? '' }} {{ $nacimiento->madre->apellido_paterno ?? '' }} {{ $nacimiento->madre->apellido_materno ?? '' }}</span>
        </div>
        @if($nacimiento->madre->documento_identidad)
        <div class="row">
            <span class="label">DNI Madre:</span>
            <span class="value">{{ $nacimiento->madre->documento_identidad }}</span>
        </div>
        @endif
        @endif
        @if($nacimiento->padre)
        <div class="row">
            <span class="label">Padre:</span>
            <span class="value">{{ $nacimiento->padre->nombres ?? '' }} {{ $nacimiento->padre->apellido_paterno ?? '' }} {{ $nacimiento->padre->apellido_materno ?? '' }}</span>
        </div>
        @if($nacimiento->padre->documento_identidad)
        <div class="row">
            <span class="label">DNI Padre:</span>
            <span class="value">{{ $nacimiento->padre->documento_identidad }}</span>
        </div>
        @endif
        @endif
    </div>
    @endif

    <div class="section">
        <div class="section-title">IV. Lugar de Nacimiento</div>
        @if($nacimiento->distrito)
        <div class="row">
            <span class="label">Región:</span>
            <span class="value">{{ $nacimiento->distrito->provincia->region->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Provincia:</span>
            <span class="value">{{ $nacimiento->distrito->provincia->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Distrito:</span>
            <span class="value">{{ $nacimiento->distrito->nombre ?? 'No especificado' }}</span>
        </div>
        @else
        <div class="row">
            <span class="label">Lugar:</span>
            <span class="value">{{ $nacimiento->lugar_nacimiento ?? 'No especificado' }}</span>
        </div>
        @endif
        @if($nacimiento->establecimiento_salud)
        <div class="row">
            <span class="label">Establecimiento de Salud:</span>
            <span class="value">{{ $nacimiento->establecimiento_salud }}</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">V. Autoridades y Funcionarios</div>
        <div class="row">
            <span class="label">Usuario Registrador:</span>
            <span class="value">{{ $nacimiento->usuario && $nacimiento->usuario->persona ? $nacimiento->usuario->persona->nombre_completo : ($nacimiento->usuario->nombre_usuario ?? 'No asignado') }}</span>
        </div>
        <div class="row">
            <span class="label">Alcalde:</span>
            <span class="value">
                @if($nacimiento->alcalde && $nacimiento->alcalde->persona)
                    {{ $nacimiento->alcalde->persona->nombres ?? '' }} {{ $nacimiento->alcalde->persona->apellido_paterno ?? '' }} {{ $nacimiento->alcalde->persona->apellido_materno ?? '' }}
                @else
                    No asignado
                @endif
            </span>
        </div>
    </div>

    <div class="section small-text">
        <div class="section-title">VI. Observaciones</div>
        @if($nacimiento->observaciones)
        <div class="row">
            <span class="label">Observaciones:</span>
            <span class="value">{{ $nacimiento->observaciones }}</span>
        </div>
        @endif
        @if($nacimiento->ruta_archivo_pdf)
        <div class="row">
            <span class="label">Documento Adjunto:</span>
            <span class="value">Sí</span>
        </div>
        @endif
    </div>

    <div class="signature-section">
        <div class="signature-box" style="width: 60%; margin: 0 auto;">
            <strong>FIRMA DEL ALCALDE</strong><br>
            <span class="small-text">
                @if($nacimiento->alcalde && $nacimiento->alcalde->persona)
                    {{ $nacimiento->alcalde->persona->nombres ?? '' }} {{ $nacimiento->alcalde->persona->apellido_paterno ?? '' }} {{ $nacimiento->alcalde->persona->apellido_materno ?? '' }}
                @endif
            </span>
        </div>
    </div>

    <div class="footer">
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        Sistema de Registro Civil Municipal
    </div>

</body>
</html> 