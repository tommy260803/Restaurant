<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Compra #{{ str_pad($compra->idCompra, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
            size: A4 portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.6;
            color: #1a1a1a;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        .page {
            width: 100%;
            max-width: 180mm;
            margin: 0 auto;
        }
        
        /* Header empresarial */
        .header {
            border-bottom: 4px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .company-info {
            float: left;
            width: 58%;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 4px;
            letter-spacing: 2px;
        }
        
        .company-tagline {
            font-size: 10px;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 8px;
        }
        
        .document-info {
            float: right;
            width: 40%;
            text-align: right;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 6px;
        }
        
        .document-number {
            font-size: 20px;
            font-weight: 800;
            color: #000000;
            background-color: #f3f4f6;
            padding: 6px 12px;
            display: inline-block;
            border-left: 4px solid #1e3a8a;
            margin-bottom: 6px;
        }
        
        .document-date {
            font-size: 9px;
            color: #6b7280;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Secciones de información */
        .info-section {
            margin-bottom: 18px;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #1e3a8a;
            padding: 10px 12px;
            margin-bottom: 12px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #374151;
            padding: 3px 12px 3px 0;
            width: 32%;
            font-size: 9px;
        }
        
        .info-value {
            display: table-cell;
            color: #1f2937;
            padding: 3px 0;
            font-size: 9px;
        }
        
        /* Estado badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        
        .status-en_transito {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #60a5fa;
        }
        
        .status-recibida {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        
        .status-anulada {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        
        /* Tabla profesional */
        .table-container {
            margin: 18px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
        }
        
        thead {
            background-color: #1e3a8a;
            color: #ffffff;
        }
        
        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #1e40af;
        }
        
        tbody td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
            color: #374151;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .item-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 9px;
        }
        
        .item-category {
            color: #6b7280;
            font-size: 8px;
            font-style: italic;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .number {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        /* Footer de tabla - Total */
        tfoot {
            background-color: #f3f4f6;
            border-top: 3px solid #1e3a8a;
        }
        
        tfoot td {
            padding: 10px 6px;
            font-weight: 700;
            font-size: 10px;
            border: none;
        }
        
        .total-label {
            color: #1f2937;
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .total-amount {
            color: #065f46;
            font-size: 14px;
            font-weight: 800;
            font-family: 'Courier New', monospace;
        }
        
        /* Notas y términos */
        .notes-section {
            margin-top: 20px;
            padding: 10px;
            background-color: #fffbeb;
            border-left: 3px solid #f59e0b;
            border-radius: 2px;
        }
        
        .notes-title {
            font-size: 9px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 5px;
        }
        
        .notes-content {
            font-size: 8px;
            color: #78350f;
            line-height: 1.5;
        }
        
        /* Footer del documento */
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }
        
        .footer-content {
            font-size: 7px;
            color: #9ca3af;
            line-height: 1.6;
        }
        
        .footer-signature {
            margin-top: 30px;
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            margin: 0 auto 6px;
            border-top: 2px solid #1f2937;
            padding-top: 6px;
        }
        
        .signature-label {
            font-size: 8px;
            color: #4b5563;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header Empresarial -->
        <div class="header clearfix">
            <div class="company-info">
                <div class="company-name">UMAMI</div>
                <div class="company-tagline">Excelencia en Servicio Gastronómico</div>
            </div>
            <div class="document-info">
                <div class="document-title">ORDEN DE COMPRA</div>
                <div class="document-number">#{{ str_pad($compra->idCompra, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="document-date">
                    <strong>Fecha de emisión:</strong><br>
                    {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                </div>
            </div>
        </div>

        <!-- Información del Proveedor -->
        <div class="info-section">
            <div class="section-title">Información del Proveedor</div>
            <div class="info-box">
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Razón Social:</span>
                        <span class="info-value">{{ $compra->proveedor->nombre ?? 'N/A' }} {{ $compra->proveedor->apellidoPaterno ?? '' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">RUC:</span>
                        <span class="info-value number">{{ $compra->proveedor->rucProveedor ?? 'N/A' }}</span>
                    </div>
                    @if(isset($compra->proveedor->telefono))
                    <div class="info-row">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value">{{ $compra->proveedor->telefono }}</span>
                    </div>
                    @endif
                    @if(isset($compra->proveedor->email))
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $compra->proveedor->email }}</span>
                    </div>
                    @endif
                    @if(isset($compra->proveedor->direccion))
                    <div class="info-row">
                        <span class="info-label">Dirección:</span>
                        <span class="info-value">{{ $compra->proveedor->direccion }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detalles de la Orden -->
        <div class="info-section">
            <div class="section-title">Detalles de la Orden</div>
            <div class="info-box">
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Estado:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $compra->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $compra->estado)) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Descripción:</span>
                        <span class="info-value">{{ $compra->descripcion }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fecha de Entrega Esperada:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($compra->fecha)->addDays(7)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="table-container">
            <div class="section-title">Detalle de Productos</div>
            <table>
                <thead>
                    <tr>
                        <th width="6%" class="text-center">ÍTEM</th>
                        <th width="44%">DESCRIPCIÓN</th>
                        <th width="14%" class="text-center">CANTIDAD</th>
                        <th width="18%" class="text-right">PRECIO UNIT.</th>
                        <th width="18%" class="text-right">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach($compra->detalles as $detalle)
                    <tr>
                        <td class="text-center number">{{ str_pad($contador++, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="item-name">{{ $detalle->ingrediente->nombre ?? 'N/A' }}</div>
                            @if(isset($detalle->ingrediente->categoria))
                                <div class="item-category">{{ $detalle->ingrediente->categoria }}</div>
                            @endif
                        </td>
                        <td class="text-center number">
                            {{ number_format($detalle->cantidad, 2) }}
                            @if(isset($detalle->ingrediente->unidad))
                                {{ $detalle->ingrediente->unidad }}
                            @endif
                        </td>
                        <td class="text-right number">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="text-right number">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="total-label">TOTAL A PAGAR:</td>
                        <td class="text-right total-amount">S/ {{ number_format($compra->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Notas -->
        <div class="notes-section">
            <div class="notes-title">NOTAS IMPORTANTES:</div>
            <div class="notes-content">
                • Esta orden de compra es válida por 15 días calendario desde su emisión.<br>
                • Los productos deben cumplir con los estándares de calidad establecidos.<br>
                • La factura debe incluir el número de orden de compra como referencia.<br>
                • Cualquier modificación debe ser aprobada por escrito por el departamento de compras.
            </div>
        </div>

        <!-- Firma -->
        <div class="footer-signature">
            <div class="signature-line">
                <div class="signature-label">Autorizado por</div>
            </div>
            <div style="font-size: 8px; color: #6b7280; margin-top: 3px;">
                Departamento de Compras
            </div>
        </div>

        <!-- Footer del Documento -->
        <div class="footer">
            <div class="footer-content">
                <strong>Documento generado electrónicamente el:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}<br>
                Este documento es válido sin firma autógrafa según la Ley N° 27269<br>
                <strong>UMAMI</strong> - Sistema Integrado de Gestión de Compras
            </div>
        </div>
    </div>
</body>
</html>