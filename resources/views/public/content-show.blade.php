<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $item->title }} | Manta Intervención</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef9fd] text-slate-900 antialiased">
    @include('public.partials.site-header', ['quickLinks' => $quickLinks])

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
