<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Convocatorias | Manta Movilidad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
    @include('public.partials.site-header', ['quickLinks' => $quickLinks])

    <section class="convocatoria-hero">
        <img src="/assets/brand/logo.png" alt="Manta Intervención">
        <h1>Convocatorias</h1>
        <p>Procesos públicos disponibles de Movilidad de Manta EP</p>
    </section>

    <main class="convocatoria-page">
        <section class="convocatoria-intro">
            <h2>Convocatorias públicas</h2>
            <p>En esta sección se publican las convocatorias oficiales emitidas por la Empresa Pública Municipal Movilidad de Manta EP, junto con sus bases, cronogramas, requisitos y documentos habilitantes.</p>
        </section>

        <section class="convocatoria-list-section">
            <h2>Convocatorias disponibles</h2>
            <div class="convocatoria-table">
                @forelse ($convocatorias as $convocatoria)
                    <article class="convocatoria-row">
                        <div>
                            <h3>{{ $loop->iteration }}. {{ $convocatoria->title }}</h3>
                            <p>{{ $convocatoria->metadataValue('subtitle') ?: $convocatoria->content }}</p>
                        </div>
                        <a href="{{ route('convocatorias.show', $convocatoria) }}">Ver convocatoria</a>
                    </article>
                @empty
                    <article class="convocatoria-empty">No hay convocatorias publicadas por el momento.</article>
                @endforelse
            </div>
            <div class="cms-pagination mt-8">{{ $convocatorias->links() }}</div>
        </section>
    </main>
</body>
</html>
