<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mesa; // Asegúrate de incluir el modelo Mesa

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mesas = [
            // Mesas para 2 personas
            ['numero' => '1', 'capacidad' => 2],
            ['numero' => '2', 'capacidad' => 2],
            ['numero' => '3', 'capacidad' => 2],
            ['numero' => '4', 'capacidad' => 2],
            
            // Mesas para 4 personas
            ['numero' => '5', 'capacidad' => 4],
            ['numero' => '6', 'capacidad' => 4],
            ['numero' => '7', 'capacidad' => 4],
            ['numero' => '8', 'capacidad' => 4],
            ['numero' => '9', 'capacidad' => 4],
            ['numero' => '10', 'capacidad' => 4],
            
            // Mesas para 6 personas
            ['numero' => '11', 'capacidad' => 6],
            ['numero' => '12', 'capacidad' => 6],
            ['numero' => '13', 'capacidad' => 6],
            ['numero' => '14', 'capacidad' => 6],
            
            // Mesas para 8 personas
            ['numero' => '15', 'capacidad' => 8],
            ['numero' => '16', 'capacidad' => 8],
        ];

        foreach ($mesas as $mesa) {
            Mesa::create([
                'numero' => $mesa['numero'],
                'capacidad' => $mesa['capacidad'],
                'estado' => 'disponible',
                'mesero_id' => null, // Sin mesero asignado inicialmente
            ]);
        }
    }
}
