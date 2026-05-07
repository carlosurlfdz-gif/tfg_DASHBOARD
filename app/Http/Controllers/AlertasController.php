<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alerta;


class AlertasController extends Controller
{
    public function index()
    {
        $alertas = Alerta::all();
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
            $prioridades = Alerta::prioridadTexto();
            $seleccion = $request->prioridad;

            if (is_numeric($seleccion) && isset($prioridades[(int)$seleccion])) {
                $label = mb_strtolower($prioridades[(int)$seleccion], 'UTF-8');
                $query->whereRaw('LOWER(severity_label) = ?', [$label]);
            } else {
                $query->whereRaw('LOWER(severity_label) = ?', [mb_strtolower($seleccion, 'UTF-8')]);
            }
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $alertas = $query->get();
        $prioridades= Alerta::prioridadTexto();

        return view('alertas', compact('alertas', 'prioridades'));
    }

    public function destroy(Alerta $alerta)
    {
        $alerta->delete();

        return redirect()->route('alertas')->with('success', 'Alerta eliminada correctamente.');
    }
}