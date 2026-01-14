<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Compra #{{ $compra->idCompra }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .info { margin-bottom: 20px; }
        .info p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #222; padding: 8px 6px; }
        th { background-color: #2c3e50; color: #fff; font-size: 12px; text-align: left; }
        tr:nth-child(even) td { background-color: #f2f2f2; }
        td { font-size: 11px; }
        .total { font-weight: bold; text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h2>COMPROBANTE DE COMPRA #{{ $compra->idCompra }}</h2>
    
    <div class="info">
        <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre ?? 'N/A' }} {{ $compra->proveedor->apellidoPaterno ?? '' }}</p>
        <p><strong>RUC:</strong> {{ $compra->proveedor->rucProveedor ?? 'N/A' }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($compra->estado) }}</p>
        <p><strong>Descripción:</strong> {{ $compra->descripcion }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Ingrediente</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $contador = 1; @endphp
            @foreach($compra->detalles as $detalle)
            <tr>
                <td>{{ $contador++ }}</td>
                <td>{{ $detalle->ingrediente->nombre ?? 'N/A' }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>S/ {{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">TOTAL:</td>
                <td><strong>S/ {{ number_format($compra->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema de Gestión de Compras - Restaurant</p>
    </div>
</body>
</html>