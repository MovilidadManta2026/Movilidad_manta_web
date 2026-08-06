@extends('admin.layout')

@section('title', $moduleLabel)

@section('actions')
    <div class="flex flex-wrap gap-3">
        <a class="manta-action-outline" href="{{ route('admin.content.index') }}">Todos los módulos</a>
        <a class="manta-action-outline" href="{{ route('admin.media.index', ['module' => $module]) }}">Archivos del módulo</a>
    </div>
@endsection

@section('content')
    @php
        $moduleHelp = [
            'inicio' => 'Edita el héroe, accesos directos y documentos destacados que aparecen en la página principal.',
            'mision_vision' => 'Edita los bloques públicos de misión, visión y principios institucionales.',
            'boletines' => 'Crea comunicados para el carrusel de boletines de la página principal.',
            'convocatorias' => 'Crea popups informativos que aparecen al abrir la web. Solo una convocatoria puede estar publicada a la vez.',
            'textos' => 'Centraliza textos generales, mensajes institucionales y bloques reutilizables.',
            'noticias' => 'Publica noticias con imagen, categoría, fecha visible y detalle de lectura.',
            'servicios' => 'Administra trámites, consultas, accesos y servicios ciudadanos.',
            'transparencia' => 'Organiza documentos por año y fase para la página de Rendición de Cuentas.',
            'lotaip' => 'Organiza PDFs LOTAIP por año y categoría para la página pública LOTAIP.',
            'pdfs' => 'Sube documentos PDF y publícalos automáticamente en la web.',
            'fotos' => 'Gestiona imágenes para galerías, noticias y secciones visuales.',
            'foros' => 'Publica espacios de participación, convocatorias y consultas ciudadanas.',
        ];
        $statusLabels = ['published' => 'Publicado', 'draft' => 'Borrador', 'archived' => 'Archivado'];
        $statusClasses = [
            'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
            'archived' => 'bg-slate-100 text-slate-600 border-slate-200',
        ];
    @endphp

    <section class="mb-6 grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-5 shadow-xl shadow-[#064782]/10 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-center">
        <div>
            <span class="text-xs font-black uppercase tracking-wide text-[#149BD7]">Módulo de edición</span>
            <h2 class="mt-1 text-2xl font-black text-[#064782]">{{ $moduleLabel }}</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">{{ $moduleHelp[$module] ?? 'Gestiona los contenidos publicados de este módulo.' }}</p>
        </div>
        <div class="grid gap-2 sm:grid-cols-3 xl:min-w-[360px]">
            <div class="rounded-lg bg-[#eef9fd] p-3 text-center">
                <strong class="block text-2xl text-[#064782]">{{ $items->total() }}</strong>
                <span class="text-xs font-black uppercase text-slate-500">Registros</span>
            </div>
            <a class="rounded-lg border border-[#aee0f6] bg-[#eef9fd] p-3 text-center hover:bg-white" href="{{ route('admin.media.index', ['module' => $module]) }}">
                <strong class="block text-2xl text-[#064782]">{{ $assets->count() }}</strong>
                <span class="text-xs font-black uppercase text-slate-500">Archivos recientes</span>
            </a>
            <a class="rounded-lg border border-[#aee0f6] bg-[#eef9fd] p-3 text-center hover:bg-white" href="{{ route('home') }}" target="_blank">
                <strong class="block text-2xl text-[#064782]">Web</strong>
                <span class="text-xs font-black uppercase text-slate-500">Vista pública</span>
            </a>
        </div>
    </section>

    <section class="grid gap-6 2xl:grid-cols-[440px_minmax(0,1fr)]">
        <aside class="grid h-max gap-6">
            <article class="manta-card p-6">
                <h2 class="text-xl font-black uppercase text-[#064782]">Nuevo contenido</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Completa título, descripción y archivo. Los campos técnicos pueden quedarse vacíos.</p>
                <form class="mt-5 grid gap-4" method="POST" action="{{ route('admin.modules.store', $module) }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.content.partials.form', ['item' => null, 'modules' => $modules, 'lockedModule' => $module])
                    <button class="manta-action" type="submit">Publicar contenido</button>
                </form>
            </article>

            <article class="manta-card p-6">
                <h2 class="text-xl font-black uppercase text-[#064782]">Biblioteca reciente</h2>
                <p class="mt-2 text-sm text-slate-500">Archivos subidos para este módulo.</p>
                <div class="mt-4 grid gap-3">
                    @forelse ($assets as $asset)
                        <a class="rounded-lg border border-[#cfeafa] bg-white p-3 text-sm hover:bg-[#eef9fd]" href="{{ $asset->url }}" target="_blank">
                            <strong class="block text-[#064782]">{{ $asset->title }}</strong>
                            <span class="text-xs uppercase text-slate-500">{{ $asset->type }} · {{ number_format($asset->size / 1024, 1) }} KB</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Aún no hay archivos en este módulo.</p>
                    @endforelse
                </div>
            </article>
        </aside>

        <div class="min-w-0">
            <form class="manta-card mb-6 grid gap-4 p-5 md:grid-cols-[minmax(0,1fr)_auto]" method="GET">
                <input class="min-w-0 rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20" name="search" value="{{ $search }}" placeholder="Buscar en {{ $moduleLabel }} por título, clave o texto">
                <button class="manta-action" type="submit">Buscar</button>
            </form>

            <div class="grid gap-5">
                @forelse ($items as $item)
                    <article class="manta-card p-5">
                        <div class="mb-5 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div class="min-w-0">
                                <span class="block truncate text-xs font-black uppercase tracking-wide text-[#149BD7]">{{ $item->key ?: 'Sin clave técnica' }}</span>
                                <h3 class="text-xl font-black text-[#064782]">{{ $item->title }}</h3>
                                <p class="mt-1 line-clamp-2 text-sm leading-6 text-slate-500">{{ $item->content ?: 'Sin descripción registrada.' }}</p>
                            </div>
                            <span class="w-max rounded-full border px-3 py-1 text-xs font-black uppercase {{ $statusClasses[$item->status] ?? 'bg-[#eef9fd] text-[#064782] border-[#cfeafa]' }}">{{ $statusLabels[$item->status] ?? $item->status }}</span>
                        </div>

                        <div class="mb-5 grid gap-3 rounded-lg bg-[#eef9fd] p-4 text-sm text-slate-600 md:grid-cols-3">
                            <span><strong class="block text-[#064782]">Actualizado</strong>{{ $item->updated_at->format('d/m/Y H:i') }}</span>
                            <span><strong class="block text-[#064782]">Categoría</strong>{{ $item->metadataValue('category', 'Sin categoría') }}</span>
                            <span class="min-w-0"><strong class="block text-[#064782]">Archivo</strong>
                                @if ($item->assetUrl())
                                    <a class="truncate font-bold text-[#149BD7]" href="{{ $item->assetUrl() }}" target="_blank" rel="noopener">Ver archivo asociado</a>
                                @else
                                    Sin archivo
                                @endif
                            </span>
                        </div>

                        <form id="update-content-{{ $item->id }}" class="grid gap-4" method="POST" action="{{ route('admin.content.update', $item) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.content.partials.form', ['item' => $item, 'modules' => $modules, 'lockedModule' => $module])
                        </form>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <button class="manta-action" form="update-content-{{ $item->id }}" type="submit">Actualizar</button>
                            <form method="POST" action="{{ route('admin.content.destroy', $item) }}" onsubmit="return confirm('¿Eliminar este registro del módulo {{ $moduleLabel }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-red-200 px-5 py-3 text-sm font-black uppercase text-red-600 hover:bg-red-50" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <article class="manta-card p-8 text-center text-slate-500">
                        No hay registros en este módulo todavía.
                    </article>
                @endforelse
            </div>

            <div class="mt-6">{{ $items->links() }}</div>
        </div>
    </section>
@endsection
