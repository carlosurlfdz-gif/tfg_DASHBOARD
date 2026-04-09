@extends('base') 

@section('content') 


<!-- NAVBAR -->

<div class="bg-slate-800">

<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<div class="flex items-center gap-8">

<h1 class="font-bold text-lg text-red-400">
SIEM SCALE PROJECT
</h1>

<a class="text-red-400 border-b border-red-400 pb-1">Dashboard</a>
<a class="text-gray-300">Alertas</a>
<a class="text-gray-300">Logs</a>

</div>

<div class="flex items-center gap-6">

<button class="bg-green-600 px-3 py-1 rounded text-sm">
VPN
</button>

<span>Admin</span>

</div>

</div>

</div>



<div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

<!-- STATS -->
<div class="grid grid-cols-4 gap-6">

    @foreach ($tipo_alertas as $severidad => $count)
        <div class="bg-slate-800 p-6 rounded-xl">
            <p class="text-{{ $severidad == 1 ? 'red' : ($severidad == 2 ? 'yellow' : ($severidad == 3 ? 'blue' : 'green')) }}-400 text-sm">
                {{ $severidad == 1 ? 'CRÍTICAS' : ($severidad == 2 ? 'ALTAS' : ($severidad == 3 ? 'MEDIAS' : 'BAJAS')) }}
            </p>
            <h2 class="text-4xl font-bold mt-2">{{ $count }}</h2>
            @if ($diferencia_alertas[$severidad]>=0)
                <p class="text-green-400 text-sm mt-2">
                    +{{ $diferencia_alertas[$severidad] }} hoy
                </p>
            @else
                <p class="text-red-400 text-sm mt-2">
                    {{ $diferencia_alertas[$severidad] }} hoy
                </p>
            @endif
        </div>  
    @endforeach

</div>

<!-- TRAFICO -->

<div class="bg-slate-800 p-6 rounded-xl">

<div class="flex justify-between mb-6">

<h2 class="font-semibold text-lg">
TRÁFICO DE RED (Últimas 24h)
</h2>

<span class="text-sm text-gray-400">
24h
</span>

</div>

<canvas id="trafficChart" height="110"></canvas>

</div>



<!-- TOP ATACANTES + ALERTAS -->
<div class="grid grid-cols-3 gap-6">

    <!-- TOP ATACANTES -->
    <div class="col-span-2 bg-slate-800 p-6 rounded-xl">

        <h2 class="font-semibold mb-6">
            TOP 5 IP ATACANTES
        </h2>

        <div class="space-y-5 text-sm">
            @foreach ($top_ips as $ip)
                <div>
                    <div class="flex justify-between mb-1">
                        <span>{{ $ip->src_ip }}</span>
                        <span class="text-gray-400">{{ $ip->total }} ataques</span>
                    </div>

                    <div class="bg-gray-700 h-2 rounded">
                        <div class="bg-red-500 h-2 rounded w-[90%]"></div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- ALERTAS POR TIPO -->
    <div class="bg-slate-800 p-6 rounded-xl">

        <h2 class="font-semibold mb-4">
            ALERTAS POR TIPO
        </h2>

        <canvas id="alertChart"></canvas>

        <div class="mt-4 space-y-2 text-sm">
            @php
                $colores = ['#ef4444','#f59e0b','#3b82f6','#22c55e'];
            @endphp

            @foreach ($alertas_tipos as $categoria => $total)
                <div class="flex justify-between">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full"
                              style="background-color: {{ $colores[$loop->index % count($colores)] }};">
                        </span>
                        {{ $categoria }}
                    </span>
                    <span>{{ $portencajes[$categoria] }}%</span>
                </div>
            @endforeach
        </div>

    </div>

</div>

<!-- ALERTAS TIEMPO REAL -->
<div class="bg-slate-800 p-6 rounded-xl">

    <h2 class="font-semibold mb-6">
        ÚLTIMAS ALERTAS EN TIEMPO REAL
    </h2>

    <div class="space-y-4">
        @foreach ($ultimas_alertas as $alerta)

            <div class="bg-{{ $alerta->severidad == 1 ? 'red' : ($alerta->severidad == 2 ? 'yellow' : ($alerta->severidad == 3 ? 'blue' : 'green')) }}-900/30 
                        border border-{{ $alerta->severidad == 1 ? 'red' : ($alerta->severidad == 2 ? 'yellow' : ($alerta->severidad == 3 ? 'blue' : 'green')) }}-500 
                        p-4 rounded">

                <p class="text-{{ $alerta->severidad == 1 ? 'red' : ($alerta->severidad == 2 ? 'yellow' : ($alerta->severidad == 3 ? 'blue' : 'green')) }}-400 text-sm">
                    {{ $alerta->severidad == 1 ? 'CRÍTICA' : ($alerta->severidad == 2 ? 'ALTA' : ($alerta->severidad == 3 ? 'MEDIA' : 'BAJA')) }}
                </p>

                <p class="mt-1">
                    {{ $alerta->categoria }}
                </p>

                <p class="text-sm text-gray-400 mt-1">
                    Origen: {{ $alerta->src_ip }} → {{ $alerta->dest_ip }}
                </p>

            </div>

        @endforeach
    </div>

</div>

<script>

const ctx = document.getElementById('trafficChart');

new Chart(ctx,{
type:'line',
data:{
//labels:['00','02','04','06','08','10','12','14','16','18','20','22','24'],
labels:[
    @foreach($horas as $hora)
        '{{ $hora }}',
    @endforeach
],
datasets:[{
//data:[120,180,150,300,450,520,650,820,700,550,420,300, 200],
data:[
    @foreach($accesos_data as $acceso)
        {{ $acceso }},
    @endforeach
],
borderColor:'#3b82f6',
tension:0.4
}]
},
options:{
plugins:{legend:{display:false}},
scales:{
x:{ticks:{color:"white"}},
y:{ticks:{color:"white"}}
}
}
});


const ctx2 = document.getElementById('alertChart');

new Chart(ctx2,{
type:'doughnut',
data:{
//labels:['Port Scan','Brute Force','Malware','DDoS'],
labels:[
    @foreach($alertas_tipos as $tipo => $count)
        '{{ $tipo }}',
    @endforeach
],
datasets:[{
data:[
    @foreach($alertas_tipos as $tipo => $count)
        {{ $count }},
    @endforeach
],
backgroundColor:[
'#ef4444',
'#f59e0b',
'#3b82f6',
'#22c55e'
]
}]
},
options:{
plugins:{legend:{display:false}}
}
});

</script>

@endsection