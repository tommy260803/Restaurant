<?php

namespace App\Exports;

use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProveedorExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Proveedor::all()->map(function($proveedor) {
            return [
                'ID' => $proveedor->idProveedor,
                'Nombre' => $proveedor->nombre,
                'Apellido Paterno' => $proveedor->apellidoPaterno,
                'Apellido Materno' => $proveedor->apellidoMaterno,
                'Teléfono' => $proveedor->telefono,
                'Email' => $proveedor->email,
                'RUC' => $proveedor->rucProveedor,
                'Dirección' => $proveedor->direccion,
                'Estado' => ucfirst($proveedor->estado),
                'Calificación' => $proveedor->calificacion,
                'Puntualidad' => $proveedor->puntualidad,
                'Calidad' => $proveedor->calidad,
                'Precio' => $proveedor->precio,
                'Incumplimientos' => $proveedor->incumplimientos,
                'Fecha Registro' => $proveedor->created_at ? $proveedor->created_at->format('d/m/Y H:i') : '-',
                'Última Actualización' => $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Teléfono',
            'Email',
            'RUC',
            'Dirección',
            'Estado',
            'Calificación',
            'Puntualidad',
            'Calidad',
            'Precio',
            'Incumplimientos',
            'Fecha Registro',
            'Última Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2c3e50']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }
}
