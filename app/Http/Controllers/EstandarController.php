<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EstandarController extends Controller
{
    /**
     * Dashboard principal - redirige a sensores
     */
    public function index()
    {
        return $this->dashboardSensores();
    }

         /**
     * Sensores 
     */
public function dashboardSensores() {
    return view('admin.sensores');
}

public function datosSensores(Request $request) {
    $limite = $request->get('limite', 10);
    $datos = DB::table('sensores_final')
        ->orderBy('fecha', 'desc')
        ->limit($limite)
        ->get();

    return response()->json($datos);
}
}
