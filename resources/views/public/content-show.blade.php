<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->title }} | Manta Intervención</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef9fd] text-slate-900 antialiased">
    <header class="site-header border-b border-[#cfeafa] bg-white/95 shadow-sm backdrop-blur-xl">
        <a class="brand" href="{{ route('home') }}">
            <img src="/assets/brand/logo.png" alt="Manta Intervención">
        </a>
        <nav class="main-nav" aria-label="Navegacion principal">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('mission-vision') }}">Misión y Visión</a>
            <a href="{{ route('news') }}">Noticias</a>
            <a href="{{ route('services') }}">Servicios</a>
            <a href="{{ route('lotaip') }}">LOTAIP</a>
            <a href="{{ route('accountability') }}">Rendición de Cuentas</a>
            <a href="{{ route('contact') }}">Contacto</a>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10 md:py-16">
        <article class="manta-card overflow-hidden">
            @php $detailAsset = $item->assetUrl($item->module === 'noticias' ? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=85' : null); @endphp
            @if ($detailAsset)
                @if (str_contains($detailAsset, '.mp4'))
                    <video class="aspect-video w-full object-cover" src="{{ $detailAsset }}" controls></video>
                @else
                    <img class="max-h-[520px] w-full object-cover" src="{{ $detailAsset }}" alt="{{ $item->title }}">
                @endif
            @endif
            <div class="p-6 md:p-10">
                <span class="text-sm font-black uppercase text-[#149BD7]">{{ ucfirst($item->module) }} · {{ $item->displayDate() }}</span>
                <h1 class="mt-3 text-4xl font-black uppercase leading-tight text-[#064782]">{{ $item->title }}</h1>
                <div class="prose mt-6 max-w-none whitespace-pre-line text-lg leading-8 text-slate-700">{{ $item->content }}</div>
                @if ($item->metadataValue('url'))
                    <a class="manta-action mt-8" href="{{ $item->metadataValue('url') }}" target="_blank" rel="noopener">Abrir enlace <span>→</span></a>
                @endif
            </div>
        </article>
    </main>
</body>
</html>
