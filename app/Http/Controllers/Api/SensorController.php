<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
     // GET /api/sensores
    public function index()
    {
        return response()->json(SensorData::all(), 200);
    }

    // GET /api/sensores/{id}
    public function show($id)
    {
        $sensor = SensorData::find($id);
        if (!$sensor) {
            return response()->json(['message' => 'Sensor no encontrado'], 404);
        }
        return response()->json($sensor, 200);
    }
}

