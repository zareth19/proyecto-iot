<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParametrosCultivoController extends Controller
{
    public function index()
    {
        $parametros = DB::table('parametros_cultivos')->get();
        return view('admin.parametros.index', compact('parametros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_cultivo' => 'required|string|unique:parametros_cultivos',
            'temp_min' => 'required|numeric',
            'temp_max' => 'required|numeric|gt:temp_min',
            'ph_min' => 'required|numeric',
            'ph_max' => 'required|numeric|gt:ph_min',
            'turbidez_min' => 'required|numeric',
            'turbidez_max' => 'required|numeric|gt:turbidez_min'
        ]);

        DB::table('parametros_cultivos')->insert([
            'tipo_cultivo' => strtolower($request->tipo_cultivo),
            'temp_min' => $request->temp_min,
            'temp_max' => $request->temp_max,
            'ph_min' => $request->ph_min,
            'ph_max' => $request->ph_max,
            'turbidez_min' => $request->turbidez_min,
            'turbidez_max' => $request->turbidez_max,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Parámetros creados exitosamente');
    }

    public function destroy($id)
    {
        DB::table('parametros_cultivos')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Parámetros eliminados');
    }
}