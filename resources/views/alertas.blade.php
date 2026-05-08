@extends('base')

@section('content')

@vite(['resources/css/alertas.css'])

    <div class="bg-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap justify-between items-center gap-4">

            <div class="flex items-center gap-8 flex-wrap">
                <h1 class="font-bold text-lg text-red-400 tracking-wide">
                    SIEM SCALE PROJECT
                </h1>

                <a href="{{ url('/dashboard') }}"
                   class="text-slate-300 hover:text-white transition">
                    Dashboard
                </a>

                <a href="{{ url('/alertas') }}"
                   class="text-red-400 border-b border-red-400 pb-1">
                    Alertas
                </a>
            </div>

            <div class="flex items-center gap-8">
                <span class="text-white">Admin</span>

                <form method="POST" action="{{ route('logout') }}" class="m-0 flex items-center">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded bg-red-600 px-3 py-1 text-sm text-white shadow-sm shadow-red-950/40 transition hover:bg-red-700">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div id="zonaPDF" class="siem-page">

        <div class="alertas-container">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Gestión de alertas de seguridad</h1>
                    <p class="page-subtitle">
                        Monitorización de amenazas, eventos Suricata y detalles completos por alerta.
                    </p>
                </div>

                <form action="{{ url('/filtro-alertas') }}" method="POST" class="filters-card">
                    @csrf

                    <input
                        name="buscar"
                        type="text"
                        placeholder="Buscar alerta..."
                        value="{{ request('buscar') }}"
                        class="siem-input"
                    />

                    <select name="prioridad" class="siem-select">
                        <option value="">Prioridad: todas</option>
                        @foreach ($prioridades as $valor => $prioridad)
                            <option value="{{ $valor }}" {{ request('prioridad') == $valor ? 'selected' : '' }}>
                                {{ $prioridad }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="siem-btn siem-btn-primary">
                        Filtrar
                    </button>

                    <button type="button" class="siem-btn siem-btn-secondary" onclick="generarPDF()">
                        Exportar
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="success-alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($alertas->isEmpty())

                <div class="empty-state">
                    No se han encontrado alertas.
                </div>

            @else

                <div class="alerts-list">

                    @foreach ($alertas as $alerta)

                        @php
                            $severityLabel = $alerta->severity_label
                                ?? ($prioridades[$alerta->severity] ?? 'DESCONOCIDA');

                            $normalizedLabel = strtoupper(trim($severityLabel));
                            $colorCaja = match ($normalizedLabel) {
                                'CRÍTICA', 'CRITICA' => 'critical',
                                'ALTA' => 'high',
                                'MEDIA' => 'medium',
                                'BAJA' => 'low',
                                default => ($alerta->severity == 1
                                    ? 'critical'
                                    : ($alerta->severity == 2
                                        ? 'high'
                                        : ($alerta->severity == 3
                                            ? 'medium'
                                            : 'low'))),
                            };

                            $badgeClass = 'badge-' . $colorCaja;
                        @endphp

                        <article class="alert-card">

                            <div class="alert-card-header">

                                <div class="alert-title-block">
                                    <div class="alert-topline">
                                        <span class="badge {{ $badgeClass }}">
                                            {{ strtoupper($severityLabel) }}
                                        </span>

                                        <span class="alert-id">
                                            ID #{{ $alerta->id_alerta }}
                                        </span>
                                    </div>

                                    <p class="alert-message">
                                        {{ $alerta->categoria ?? 'Sin categoría' }}
                                    </p>

                                    <div class="alert-subline">
                                        {{ $alerta->firma ?? 'Alerta sin mensaje definido' }}
                                        ·
                                        {{ optional($alerta->timestamp_evento)->format('Y-m-d H:i:s') ?? '-' }}
                                    </div>
                                </div>

                                <div class="alert-route">
                                    <div>
                                        <strong>{{ $alerta->src_ip ?? '-' }}</strong>:{{ $alerta->src_port ?? '-' }}
                                        →
                                        <strong>{{ $alerta->dest_ip ?? '-' }}</strong>:{{ $alerta->dest_port ?? '-' }}
                                    </div>

                                    <div class="muted">
                                        {{ $alerta->proto ?? '-' }} · {{ $alerta->app_proto ?? '-' }}
                                    </div>
                                </div>

                            </div>

                            <div class="summary-grid">

                                <div class="summary-item">
                                    <span class="summary-label">Severidad</span>
                                    <span class="summary-value">{{ $severityLabel }}</span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">IP origen</span>
                                    <span class="summary-value">
                                        {{ $alerta->src_ip ?? '-' }}:{{ $alerta->src_port ?? '-' }}
                                    </span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">IP destino</span>
                                    <span class="summary-value">
                                        {{ $alerta->dest_ip ?? '-' }}:{{ $alerta->dest_port ?? '-' }}
                                    </span>
                                </div>

                                <div class="summary-item">
                                    <span class="summary-label">Protocolo</span>
                                    <span class="summary-value">
                                        {{ $alerta->proto ?? '-' }} · {{ $alerta->app_proto ?? '-' }}
                                    </span>
                                </div>

                            </div>

                            <div class="alert-details" id="alert-details-{{ $alerta->id_alerta }}">

                                <div class="alert-meta">

                                    <div class="alert-field">
                                        <span class="alert-label">Event UUID</span>
                                        <span class="alert-value">{{ $alerta->event_uuid ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Flow ID</span>
                                        <span class="alert-value">{{ $alerta->flow_id ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Timestamp evento</span>
                                        <span class="alert-value">
                                            {{ optional($alerta->timestamp_evento)->format('Y-m-d H:i:s') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Event type</span>
                                        <span class="alert-value">{{ $alerta->event_type ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Interfaz</span>
                                        <span class="alert-value">{{ $alerta->in_iface ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Protocolo</span>
                                        <span class="alert-value">{{ $alerta->proto ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">App proto</span>
                                        <span class="alert-value">{{ $alerta->app_proto ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Firma ID</span>
                                        <span class="alert-value">{{ $alerta->firma_id ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field full">
                                        <span class="alert-label">Firma</span>
                                        <span class="alert-value">{{ $alerta->firma ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Categoría</span>
                                        <span class="alert-value">{{ $alerta->categoria ?? '-' }}</span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Creada</span>
                                        <span class="alert-value">
                                            {{ optional($alerta->created_at)->format('Y-m-d H:i:s') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="alert-field">
                                        <span class="alert-label">Actualizada</span>
                                        <span class="alert-value">
                                            {{ optional($alerta->updated_at)->format('Y-m-d H:i:s') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="alert-field full">
                                        <span class="alert-label">Payload</span>
                                        <pre class="alert-pre">{{ $alerta->payload ?? '-' }}</pre>
                                    </div>

                                    <div class="alert-field full">
                                        <span class="alert-label">JSON crudo</span>
                                        <pre class="alert-pre" id="alert-copy-{{ $alerta->id_alerta }}">{{ $alerta->json_crudo ?? '-' }}</pre>
                                    </div>

                                </div>

                            </div>

                            <div class="alert-footer">

                                <button
                                    type="button"
                                    onclick="toggleDetails({{ $alerta->id_alerta }}, this)"
                                    class="siem-btn siem-btn-secondary">
                                    Ver detalles
                                </button>

                                <button
                                    type="button"
                                    onclick="copyAlert({{ $alerta->id_alerta }})"
                                    class="siem-btn siem-btn-secondary">
                                    Copiar JSON
                                </button>

                                <form method="POST" action="{{ route('alertas.destroy', $alerta) }}" class="m-0">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="siem-btn siem-btn-danger">
                                        Borrar
                                    </button>
                                </form>

                            </div>

                        </article>

                    @endforeach
                        {{ $alertas->links() }}
                </div>

            @endif

        </div>
    </div>

    <script>
        function generarPDF() {
            window.print();
        }

        function toggleDetails(id, button) {
            const details = document.getElementById('alert-details-' + id);

            if (!details) return;

            details.classList.toggle('active');

            if (button) {
                button.textContent = details.classList.contains('active')
                    ? 'Ocultar detalles'
                    : 'Ver detalles';
            }
        }

        function copyAlert(id) {
            const element = document.getElementById('alert-copy-' + id);

            if (!element) {
                alert('No se encontró el contenido para copiar.');
                return;
            }

            const text = element.innerText;

            navigator.clipboard.writeText(text).then(() => {
                alert('JSON copiado al portapapeles.');
            }).catch(() => {
                alert('No se pudo copiar el texto. Usa Ctrl+C.');
            });
        }
    </script>

@endsection