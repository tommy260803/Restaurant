<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Acta de Defunción</title>
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
    </style>
</head>
<body>
    <div class="header">
        <div class="title">REPÚBLICA DEL PERÚ</div>
        <div class="title">MUNICIPALIDAD DISTRITAL</div>
        <div class="title">REGISTRO CIVIL</div>
        <div class="title">ACTA DE DEFUNCIÓN N° {{ str_pad($acta->id_acta_defuncion, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section">
        <div class="section-title">I. Información Registral</div>
        <div class="row">
            <span class="label">Número de Libro:</span>
            <span class="value">{{ $acta->folio && $acta->folio->libro ? $acta->folio->libro->numero_libro : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Número de Folio:</span>
            <span class="value">{{ $acta->folio ? $acta->folio->numero_folio : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Fecha de Registro:</span>
            <span class="value">{{ $acta->fecha_registro ? \Carbon\Carbon::parse($acta->fecha_registro)->format('d/m/Y') : date('d/m/Y') }}</span>
        </div>

    </div>

    <div class="section">
        <div class="section-title">II. Datos del Fallecido</div>
        <div class="row">
            <span class="label">Documento de Identidad:</span>
            <span class="value">{{ $acta->dni_fallecido }}</span>
        </div>
        <div class="row">
            <span class="label">Nombres y Apellidos:</span>
            <span class="value">{{ $acta->persona_fallecida ? $acta->persona_fallecida->nombre_completo : 'No registrado' }}</span>
        </div>
        @if($acta->persona_fallecida)
        <div class="row">
            <span class="label">Sexo:</span>
            <span class="value">{{ $acta->persona_fallecida->sexo == 'M' ? 'Masculino' : ($acta->persona_fallecida->sexo == 'F' ? 'Femenino' : 'No especificado') }}</span>
        </div>
        @endif
        <div class="row">
            <span class="label">Fecha de Defunción:</span>
            <span class="value">{{ $acta->fecha_defuncion ? \Carbon\Carbon::parse($acta->fecha_defuncion)->format('d/m/Y') : 'No registrada' }}</span>
        </div>
        <div class="row">
            <span class="label">Causa de Defunción:</span>
            <span class="value">{{ $acta->causa_defuncion ?: 'No especificada' }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">III. Lugar de Defunción</div>
        @if($acta->distrito)
        <div class="row">
            <span class="label">Región:</span>
            <span class="value">{{ $acta->distrito->provincia->region->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Provincia:</span>
            <span class="value">{{ $acta->distrito->provincia->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Distrito:</span>
            <span class="value">{{ $acta->distrito->nombre ?? 'No especificado' }}</span>
        </div>
        @else
        <div class="row">
            <span class="label">Lugar:</span>
            <span class="value">No especificado</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">IV. Autoridades y Funcionarios</div>
        <div class="row">
            <span class="label">Usuario Registrador:</span>
            <span class="value">{{ $acta->usuario && $acta->usuario->persona ? $acta->usuario->persona->nombre_completo : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Alcalde:</span>
            <span class="value">{{ $acta->alcalde && $acta->alcalde->persona ? $acta->alcalde->persona->nombre_completo : 'No asignado' }}</span>
        </div>
    </div>

    <div class="section small-text">
        <div class="section-title">V. Observaciones</div>
        <div class="row">
            <span class="label">Estado del Acta:</span>
            <span class="value">{{ $acta->estado == '1' ? 'Activa' : 'Inactiva' }}</span>
        </div>
        @if($acta->ruta_archivo_pdf)
        <div class="row">
            <span class="label">Documento Adjunto:</span>
            <span class="value">Sí</span>
        </div>
        @endif
    </div>

    <div class="signature-section">
        <div class="signature-box" style="width: 60%; margin: 0 auto;">
            <strong>FIRMA DEL ALCALDE</strong><br>
            <span class="small-text">{{ $acta->alcalde && $acta->alcalde->persona ? $acta->alcalde->persona->nombre_completo : '' }}</span>
        </div>
    </div>

</body>
</html>