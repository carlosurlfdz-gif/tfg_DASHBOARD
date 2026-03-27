<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogAcceso;
use App\Models\Alerta;
class DashboardController extends Controller
{
    private function calculos() 
    {
        //Calcular accesos por horas en las últimas 24 horas
        $horas = $accesos_data= [];
        for ($i=0;$i<=24;$i +=2){
            $horas[]= $i < 10 ? '0'.$i : $i;
            $accesos= LogAcceso::select('*')
            ->where('fecha_acceso', '<=', Carbon::now()->subHours($i))
            ->where('fecha_acceso', '>', Carbon::now()->subHours($i+2)) 
            ->get();
            $accesos_data[] = $accesos->count();
        }
        
        //Calcular criticidad de accesos por IP
        $alertas= Alerta::all();
        $tipo_alertas = $diferencia_alertas = [];
        
        for ($i=1; $i<=4; $i++){
            $tipo_alertas[$i] = 0;
            $alertas_ayer = Alerta::whereBetween('timestamp_evento', [
                    Carbon::yesterday(),
                    Carbon::today()
                ])
                ->where('severidad', $i)
                ->count();
            $alertas_hoy = Alerta::where('timestamp_evento', '>=', Carbon::today())
                ->where('severidad', $i)
                ->count();
            $diferencia_alertas[$i] = $alertas_hoy - $alertas_ayer;

        }
        foreach ($alertas as $alerta){
            $tipo_alertas[$alerta->severidad]++;
        }

        //TOP 5 IPs con más alertas
        $top_ips = Alerta::select('src_ip', DB::raw('COUNT(*) as total'))
            ->groupBy('src_ip')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
dump($top_ips);
        return [
                'horas' => $horas,
                'accesos_data' => $accesos_data,
                'tipo_alertas' => $tipo_alertas,
                'diferencia_alertas' => $diferencia_alertas,
                'top_ips' => $top_ips

            ];
    }

    public function index()
    {
        $data = $this->calculos();
        return view('dashboard', $data);
        
    }
}
