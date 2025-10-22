<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distrito;
use App\Models\Provincia;

class UbigeoController extends Controller
{
    public function provincias($id_region)
    {
        // Filtrar provincias cuyo código empiece con id_region
        $provincias = Provincia::where('id_provincia', 'like', $id_region . '%')->get();
        return response()->json($provincias);
    }

    public function distritos($id_provincia)
    {
        // Filtrar distritos cuyo código empiece con id_provincia
        $distritos = Distrito::where('id_distrito', 'like', $id_provincia . '%')->get();
        return response()->json($distritos);
    }
}
