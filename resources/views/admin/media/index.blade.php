@extends('admin.layout')

@section('title', 'Biblioteca de archivos')

@section('content')
    <section class="mb-6 rounded-lg border border-[#cfeafa] bg-white p-6 shadow-xl shadow-[#064782]/10">
        <span class="text-xs font-black uppercase tracking-wide text-[#149BD7]">Archivos del sitio</span>
        <h2 class="mt-1 text-2xl font-black text-[#064782]">Sube y administra imágenes, PDFs y videos</h2>
        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Los archivos se guardan por módulo. Si subes un PDF en Transparencia o PDFs, también se publica automáticamente como documento visible en la web.</p>
    </section>

    <section class="grid gap-6 xl:grid-cols-[420px_minmax(0,1fr)]">
        <aside class="manta-card h-max p-6">
            <h2 class="text-xl font-black uppercase text-[#064782]">Subir archivo</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">Selecciona el módulo donde se usará el archivo y carga el documento.</p>
            <form class="mt-5 grid gap-4" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
                @csrf
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Módulo
                    <select class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="module" required>
                        @foreach ($modules as $module => $label)
                            <option value="{{ $module }}" @selected($selectedModule === $module)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Título
                    <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="title" required>
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Texto alternativo
                    <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="alt">
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Archivo
                    <input class="rounded-lg border border-dashed border-[#149BD7] bg-[#eef9fd] px-4 py-6" name="file" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.mp4" required>
                    <span class="text-xs font-medium text-slate-500">JPG, PNG, WEBP, PDF o MP4. Máximo 50 MB.</span>
                </label>
                <button class="manta-action" type="submit">Subir archivo</button>
            </form>
        </aside>

        <div class="min-w-0">
            <form class="manta-card mb-6 grid gap-4 p-5 md:grid-cols-[240px_auto]" method="GET">
                <select class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="module">
                    <option value="">Todos los módulos</option>
                    @foreach ($modules as $module => $label)
                        <option value="{{ $module }}" @selected($selectedModule === $module)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="manta-action" type="submit">Filtrar archivos</button>
            </form>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($assets as $asset)
                <article class="manta-card overflow-hidden">
                    <div class="grid aspect-video place-items-center bg-[#eef9fd]">
                        @if ($asset->type === 'image')
                            <img class="h-full w-full object-cover" src="{{ $asset->url }}" alt="{{ $asset->alt }}">
                        @elseif ($asset->type === 'video')
                            <video class="h-full w-full object-cover" src="{{ $asset->url }}" muted controls></video>
                        @else
                            <span class="text-5xl font-black text-[#149BD7]">PDF</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-black uppercase text-[#149BD7]">{{ $asset->module }} · {{ $asset->type }}</span>
                        <h3 class="mt-1 font-black text-[#064782]">{{ $asset->title }}</h3>
                        <p class="mt-2 break-all text-xs text-slate-500">{{ $asset->url }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a class="manta-action-outline px-4 py-2 text-xs" href="{{ $asset->url }}" target="_blank" rel="noopener">Abrir</a>
                            <form method="POST" action="{{ route('admin.media.destroy', $asset) }}" onsubmit="return confirm('¿Eliminar este archivo?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-lg border border-red-200 px-4 py-2 text-xs font-black uppercase text-red-600 hover:bg-red-50" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <article class="manta-card p-8 text-center text-slate-500 md:col-span-2 xl:col-span-3">No hay archivos para este filtro.</article>
            @endforelse
            </div>
        </div>
    </section>

    <div class="mt-6">{{ $assets->links() }}</div>
@endsection
