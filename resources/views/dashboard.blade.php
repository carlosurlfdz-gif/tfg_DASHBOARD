@extends('base')

@section('content')


    <!-- NAVBAR -->

    <div class="bg-slate-800">

        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <div class="flex items-center gap-8">

                <h1 class="font-bold text-lg text-red-400">
                    SIEM SCALE PROJECT
                </h1>

                <a href="{{url('/dashboard')}}" class="text-red-400 border-b border-red-400 pb-1">Dashboard</a>
                <a href="{{url('/alertas')}}" class="text-gray-300">Alertas</a>

            </div>

            <div class="flex items-center gap-8">

                <span class="text-white">Admin</span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-150">
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </div>

    </div>



    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        <!-- STATS -->
        <div class="grid grid-cols-4 gap-6">

            @foreach ($tipo_alertas as $severity => $count)
                <div class="bg-slate-800 p-6 rounded-xl">
                    <p
                        class="text-{{ $severity == 1 ? 'red' : ($severity == 2 ? 'yellow' : ($severity == 3 ? 'blue' : 'green')) }}-400 text-sm">
                        {{ $severity == 1 ? 'CRÍTICAS' : ($severity == 2 ? 'ALTAS' : ($severity == 3 ? 'MEDIAS' : 'BAJAS')) }}
                    </p>
                    <h2 class="text-4xl font-bold mt-2">{{ $count }}</h2>
                    @if ($diferencia_alertas[$severity] >= 0)
                        <p class="text-green-400 text-sm mt-2">
                            +{{ $diferencia_alertas[$severity] }} hoy
                        </p>
                    @else
                        <p class="text-red-400 text-sm mt-2">
                            {{ $diferencia_alertas[$severity] }} hoy
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
                        $colores = ['#ef4444', '#f59e0b', '#3b82f6', '#22c55e'];
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

                    <div class="bg-{{ $alerta->severity == 1 ? 'red' : ($alerta->severity == 2 ? 'yellow' : ($alerta->severity == 3 ? 'blue' : 'green')) }}-900/30 
                                border border-{{ $alerta->severity == 1 ? 'red' : ($alerta->severity == 2 ? 'yellow' : ($alerta->severity == 3 ? 'blue' : 'green')) }}-500 
                                p-4 rounded">

                        <p
                            class="text-{{ $alerta->severity == 1 ? 'red' : ($alerta->severity == 2 ? 'yellow' : ($alerta->severity == 3 ? 'blue' : 'green')) }}-400 text-sm">
                            {{-- {{ $alerta->severity == 1 ? 'CRÍTICA' : ($alerta->severity == 2 ? 'ALTA' : ($alerta->severity
                            == 3 ? 'MEDIA' : 'BAJA')) }} --}}
                            {{ $prioridades[$alerta->severity] }}
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

            new Chart(ctx, {
                type: 'line',
                data: {
                    //labels:['00','02','04','06','08','10','12','14','16','18','20','22','24'],
                    labels: [
                        @foreach($horas as $hora)
                            '{{ $hora }}',
                        @endforeach
                        ],
                    datasets: [{
                        //data:[120,180,150,300,450,520,650,820,700,550,420,300, 200],
                        data: [
                            @foreach($accesos_data as $acceso)
                                {{ $acceso }},
                            @endforeach
                        ],
                        borderColor: '#3b82f6',
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: "white" } },
                        y: { ticks: { color: "white" } }
                    }
                }
            });


            const ctx2 = document.getElementById('alertChart');

            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    //labels:['Port Scan','Brute Force','Malware','DDoS'],
                    labels: [
                        @foreach($alertas_tipos as $tipo => $count)
                            '{{ $tipo }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($alertas_tipos as $tipo => $count)
                                {{ $count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#ef4444',
                            '#f59e0b',
                            '#3b82f6',
                            '#22c55e'
                        ]
                    }]
                },
                options: {
                    plugins: { legend: { display: false } }
                }
            });

        </script>

@endsection