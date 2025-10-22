<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait GeneradorPDFActa
{
    public function generarQR($tipo, $contenido, $id)
    {
        $qrPath = public_path("qr/{$tipo}_{$id}.png");

        // Asegurar directorio
        if (!file_exists(public_path('qr'))) {
            mkdir(public_path('qr'), 0755, true);
        }

        QrCode::format('png')->size(200)->generate($contenido, $qrPath);

        return $qrPath;
    }

    public function generarYGuardarPDF($vista, $datos, $qrPath, $nombreArchivo)
    {
        $pdf = Pdf::loadView($vista, [
            'datos' => $datos,
            'qrPath' => $qrPath
        ]);

        // Asegurar directorio de actas
        if (!Storage::disk('public')->exists('actas')) {
            Storage::disk('public')->makeDirectory('actas');
        }

        Storage::disk('public')->put("actas/{$nombreArchivo}", $pdf->output());

        return $pdf;
    }
}
