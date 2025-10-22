<!-- filepath: vsls:/resources/views/mantenedor/matrimonio/exportarPDF.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Acta de Matrimonio</title>
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
        <div class="title">ACTA DE MATRIMONIO N° {{ str_pad($matrimonio->id_acta_matrimonio, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="section">
        <div class="section-title">I. Información Registral</div>
        <div class="row">
            <span class="label">Número de Libro:</span>
            <span class="value">{{ $matrimonio->folio && $matrimonio->folio->libro ? $matrimonio->folio->libro->numero_libro : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Número de Folio:</span>
            <span class="value">{{ $matrimonio->folio ? $matrimonio->folio->numero_folio : 'No asignado' }}</span>
        </div>
        <div class="row">
            <span class="label">Fecha de Registro:</span>
            <span class="value">{{ $matrimonio->fecha_registro ? \Carbon\Carbon::parse($matrimonio->fecha_registro)->format('d/m/Y') : date('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Fecha de Matrimonio:</span>
            <span class="value">{{ $matrimonio->fecha_matrimonio ? \Carbon\Carbon::parse($matrimonio->fecha_matrimonio)->format('d/m/Y') : 'No registrada' }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">II. Datos del Primer Cónyuge</div>
        <div class="row">
            <span class="label">Documento de Identidad:</span>
            <span class="value">{{ $matrimonio->dni_conyuge1 }}</span>
        </div>
        <div class="row">
            <span class="label">Nombres y Apellidos:</span>
            <span class="value">{{ $matrimonio->conyuge1 ? ($matrimonio->conyuge1->nombres . ' ' . $matrimonio->conyuge1->apellido_paterno . ' ' . ($matrimonio->conyuge1->apellido_materno ?? '')) : 'No registrado' }}</span>
        </div>
        @if($matrimonio->conyuge1)
        <div class="row">
            <span class="label">Sexo:</span>
            <span class="value">{{ $matrimonio->conyuge1->sexo == 'M' ? 'Masculino' : ($matrimonio->conyuge1->sexo == 'F' ? 'Femenino' : 'No especificado') }}</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">III. Datos del Segundo Cónyuge</div>
        <div class="row">
            <span class="label">Documento de Identidad:</span>
            <span class="value">{{ $matrimonio->dni_conyuge2 }}</span>
        </div>
        <div class="row">
            <span class="label">Nombres y Apellidos:</span>
            <span class="value">{{ $matrimonio->conyuge2 ? ($matrimonio->conyuge2->nombres . ' ' . $matrimonio->conyuge2->apellido_paterno . ' ' . ($matrimonio->conyuge2->apellido_materno ?? '')) : 'No registrado' }}</span>
        </div>
        @if($matrimonio->conyuge2)
        <div class="row">
            <span class="label">Sexo:</span>
            <span class="value">{{ $matrimonio->conyuge2->sexo == 'M' ? 'Masculino' : ($matrimonio->conyuge2->sexo == 'F' ? 'Femenino' : 'No especificado') }}</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">IV. Lugar del Matrimonio</div>
        @if($matrimonio->distrito)
        <div class="row">
            <span class="label">Región:</span>
            <span class="value">{{ $matrimonio->distrito->provincia->region->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Provincia:</span>
            <span class="value">{{ $matrimonio->distrito->provincia->nombre ?? 'No especificada' }}</span>
        </div>
        <div class="row">
            <span class="label">Distrito:</span>
            <span class="value">{{ $matrimonio->distrito->nombre ?? 'No especificado' }}</span>
        </div>
        @else
        <div class="row">
            <span class="label">Lugar:</span>
            <span class="value">{{ $matrimonio->id_distrito_mat ?? 'No especificado' }}</span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">V. Régimen Matrimonial y Autoridades</div>
        <div class="row">
            <span class="label">Régimen Matrimonial:</span>
            <span class="value">{{ $matrimonio->regimen_matrimonial ?: 'No especificado' }}</span>
        </div>
        <div class="row">
            <span class="label">Usuario Registrador:</span>
            <span class="value">{{ $matrimonio->usuario && $matrimonio->usuario->persona ? $matrimonio->usuario->persona->nombre_completo : ($matrimonio->usuario->nombre_usuario ?? 'No asignado') }}</span>
        </div>
        <div class="row">
            <span class="label">Alcalde:</span>
            <span class="value">{{ $matrimonio->alcalde && $matrimonio->alcalde->persona ? $matrimonio->alcalde->persona->nombre_completo : ($matrimonio->id_alcalde ?? 'No asignado') }}</span>
        </div>
    </div>

    <div class="section small-text">
        <div class="section-title">VI. Observaciones</div>
        <div class="row">
            <span class="label">Estado del Acta:</span>
            <span class="value">{{ $matrimonio->estado == '1' ? 'Activa' : 'Inactiva' }}</span>
        </div>
        @if($matrimonio->ruta_archivo_pdf)
        <div class="row">
            <span class="label">Documento Adjunto:</span>
            <span class="value">Sí</span>
        </div>
        @endif
    </div>

    <div class="signature-section">
        <div class="signature-box" style="width: 60%; margin: 0 auto;">
            <strong>FIRMA DEL ALCALDE</strong><br>
            <span class="small-text">{{ $matrimonio->alcalde && $matrimonio->alcalde->persona ? $matrimonio->alcalde->persona->nombre_completo : '' }}</span>
        </div>
    </div>

    <div class="footer">
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        Sistema de Registro Civil Municipal
    </div>

</body>
</html>