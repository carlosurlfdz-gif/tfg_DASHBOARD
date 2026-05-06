@extends('base') 

@section('content') 


<!-- NAVBAR -->

<div class="bg-slate-800">

<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<div class="flex items-center gap-8">

<h1 class="font-bold text-lg text-red-400">
SIEM SCALE PROJECT
</h1>

<a href="{{url('/dashboard')}}" class="text-gray-300">Dashboard</a>
<a href="{{url('/alertas')}}" class="text-red-400 border-b border-red-400 pb-1">Alertas</a>
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
<div id="zonaPDF">

<style>
/* ===== BASE ===== */
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #0a0f1c;
    color: #e6edf3;
}

/* ===== NAVBAR ===== */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 25px;
    background: #0f172a;
    border-bottom: 1px solid #1e293b;
}

.logo {
    font-weight: 600;
    letter-spacing: 1px;
    font-size: 14px;
}

.nav-links span {
    margin: 0 15px;
    font-size: 13px;
    color: #94a3b8;
    cursor: pointer;
}

.nav-links .active {
    color: #f43f5e;
    border-bottom: 2px solid #f43f5e;
    padding-bottom: 3px;
}

/* ===== CONTENIDO ===== */
.container {
    padding: 25px;
}

/* TITULO */
.title {
    font-size: 18px;
    font-weight: 600;
}

.subtitle {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

/* ===== FILTROS ===== */
.filters {
    margin-top: 20px;
    background: #0f172a;
    border: 1px solid #1e293b;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filters input,
.filters select {
    background: #020617;
    border: 1px solid #1e293b;
    color: #cbd5f5;
    padding: 7px;
    border-radius: 5px;
    font-size: 12px;
}

.btn {
    padding: 7px 12px;
    border-radius: 5px;
    border: none;
    font-size: 12px;
    cursor: pointer;
}

.btn-filter { background: #1d4ed8; }
.btn-export { background: #0ea5e9; }

/* ===== ALERTA ===== */
.alert {
    margin-top: 18px;
    border-radius: 8px;
    overflow: hidden;
    border-left: 4px solid;
    background: linear-gradient(180deg, #0f172a, #020617);
    border: 1px solid #1e293b;
}

/* COLORES */
.critical { border-left-color: #ef4444; }
.high { border-left-color: #f59e0b; }
.medium { border-left-color: #eab308; }

/* HEADER ALERTA */
.alert-header {
    padding: 12px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    font-size: 10px;
    font-weight: bold;
    padding: 3px 6px;
    border-radius: 4px;
}

.badge-critical { background: #ef4444; }
.badge-high { background: #f59e0b; }
.badge-medium { background: #eab308; color: black; }

.alert-title {
    font-size: 13px;
    font-weight: 600;
}

/* CUERPO */
.alert-body {
    padding: 12px 15px;
    border-top: 1px solid #1e293b;
    font-size: 12px;
    color: #94a3b8;
}

/* BLOQUE INTERNO (IP / PAYLOAD) */
.box {
    margin-top: 10px;
    background: #020617;
    border: 1px solid #1e293b;
    border-radius: 5px;
    padding: 10px;
    font-family: monospace;
    color: #38bdf8;
}

/* FOOTER */
.alert-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 10px 15px;
}

.btn-detail { background: #1e293b; color: #cbd5f5; }
.btn-ok { background: #16a34a; }

.small {
    font-size: 11px;
    color: #64748b;
}
</style>


<div class="container">

    <div class="title">⚠ Gestión de alertas de seguridad</div>
    <div class="subtitle">Monitorización de amenazas en tiempo real</div>

    <!-- FILTROS -->
    <div class="filters">
        <form action="{{ url("/filtro-alertas") }}" method="POST" id="filterForm">
            @csrf
            <input name = "buscar" type="text" placeholder="Buscar alerta..." value="{{ request('buscar') }}">
            <select name = "prioridad">
                <option value="">Prioridad: Todas</option>
                @foreach ($prioridades as $valor => $prioridad)
                    <option value="{{ $valor }}" {{ request('prioridad') == $valor ? 'selected' : '' }}>
                        {{ $prioridad }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-filter">Filtrar</button>
            <button class="btn btn-export" onclick="generarPDF()">Exportar</button>
        </form>

    </div>



@foreach ($alertas as $alerta)
    <div class="alert {{ strtolower($alerta->prioridad) }}">

        <div class="alert-header">
            @php
                $colorCaja = $alerta->severity == 1 ? 'critical' : ($alerta->severity == 2 ? 'high' : ($alerta->severity == 3 ? 'medium' : 'low'));
                $textoCaja = $prioridades[$alerta->severity] ?? 'DESCONOCIDA';
            @endphp
            <span class="badge badge-{{ strtolower($colorCaja) }}">{{ strtoupper($textoCaja) }}</span>
           
            <div class="alert-title">{{ $alerta->categoria }}</div>
        </div>

        <div class="alert-body">
            {{ $alerta->descripcion }}
        </div>

        <div class="alert-footer">
            <button class="btn btn-detail">Ver detalle</button>
            <button class="btn btn-ok">✔ Revisada</button>
        </div>

    </div>
    
@endforeach

</div>
</div>
<script>
    function generarPDF() {
        const contenido = document.getElementById("zonaPDF").innerHTML;
        const original = document.body.innerHTML;

        document.body.innerHTML = contenido;
        window.print();
        document.body.innerHTML = original;
    }
</script>

</body>
</html>