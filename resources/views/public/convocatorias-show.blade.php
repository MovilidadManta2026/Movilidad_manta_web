<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $convocatoria->title }} | Convocatorias</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef9fd] text-slate-900 antialiased">
    @include('public.partials.site-header', ['quickLinks' => $quickLinks])

    <x-inner-hero
        title="Convocatorias"
        crumb="Inicio / Servicios / Convocatorias"
        text="Documentación oficial del proceso público seleccionado."
        image="https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1800&q=85"
    />

    <main class="convocatoria-page convocatoria-detail-page">
        <section class="convocatoria-detail-head">
            <h1>{{ $convocatoria->title }}</h1>
            <p>{{ $convocatoria->metadataValue('subtitle', 'Documentación oficial del proceso público.') }}</p>
            @if ($convocatoria->content)
                <div>{!! nl2br(e($convocatoria->content)) !!}</div>
            @endif
        </section>

        <section class="convocatoria-list-section">
            <h2>Documentos disponibles</h2>
            <div class="convocatoria-table">
                @forelse ($documents as $document)
                    @php
                        $path = $document['path'] ?? null;
                        $url = $path && (str_starts_with($path, 'http') || str_starts_with($path, '/')) ? $path : '/storage/'.$path;
                    @endphp
                    <article class="convocatoria-row">
                        <div>
                            <h3>{{ $loop->iteration }}. {{ $document['title'] ?? 'Documento de convocatoria' }}</h3>
                            <p>{{ $document['subtitle'] ?? 'Documento habilitante del proceso.' }}</p>
                        </div>
                        <a href="{{ $url }}" target="_blank" rel="noopener">Descargar</a>
                    </article>
                @empty
                    <article class="convocatoria-empty">Esta convocatoria aún no tiene documentos publicados.</article>
                @endforelse
            </div>
            <a class="convocatoria-back" href="{{ route('convocatorias') }}">← Volver a convocatorias</a>
        </section>
    </main>
</body>
</html>
