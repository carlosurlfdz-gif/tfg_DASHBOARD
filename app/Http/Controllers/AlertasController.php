<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alerta;


class AlertasController extends Controller
{
    public function index()
    {
        $alertas = Alerta::orderBy('timestamp_evento', 'desc')->paginate(50);
        $prioridades = Alerta::prioridadTexto();

        return view('alertas', compact('alertas', 'prioridades'));
    }

    public function filtrar(Request $request)
    {
        $query = Alerta::query();
        //Buscar por categoría
        if ($request->filled('buscar')) {
            $query->where('categoria', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('prioridad')) {
            $query->where('severity_label', $request->prioridad); // ← CAMBIADO
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $alertas = $query->orderBy('timestamp_evento', 'desc')->paginate(50); // ← CAMBIADO
        $prioridades= Alerta::prioridadTexto();

        return view('alertas', compact('alertas', 'prioridades'));
    }

    public function destroy(Alerta $alerta)
    {
        $alerta->delete();

        return redirect()->route('alertas')->with('success', 'Alerta eliminada correctamente.');
    }
}