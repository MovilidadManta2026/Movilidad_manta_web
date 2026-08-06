@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <section class="mb-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
        <article class="manta-card p-6">
            <span class="text-xs font-black uppercase tracking-wide text-[#149BD7]">Bienvenido</span>
            <h2 class="mt-1 text-2xl font-black text-[#064782]">Gestiona la web desde un solo lugar</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Publica noticias, sube PDFs, actualiza servicios y revisa qué contenido está activo. Para que algo aparezca en la web, debe estar en estado “Publicado”.</p>
        </article>
        <div class="grid gap-3 sm:grid-cols-3 lg:min-w-[520px]">
            <a class="manta-action" href="{{ route('admin.content.index') }}">Editar módulos</a>
            <a class="manta-action-outline" href="{{ route('admin.media.index') }}">Subir archivo</a>
            <a class="manta-action-outline" href="{{ route('home') }}" target="_blank">Ver web</a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-4">
        @foreach ([
            'Registros CMS' => $counts['items'],
            'Publicados' => $counts['published'],
            'Archivos' => $counts['media'],
            'Usuarios' => $counts['users'],
        ] as $label => $value)
            <article class="manta-card p-5">
                <span class="text-sm font-bold uppercase text-slate-500">{{ $label }}</span>
                <strong class="mt-3 block text-4xl font-black text-[#064782]">{{ $value }}</strong>
            </article>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
        <article class="manta-card p-6">
            <h2 class="text-xl font-black uppercase text-[#064782]">Contenido por módulo</h2>
            <div class="mt-5 grid gap-3">
                @forelse ($modules as $module)
                    <a class="flex items-center justify-between rounded-lg bg-[#eef9fd] px-4 py-3 transition hover:bg-white hover:shadow" href="{{ route('admin.modules.show', $module->module) }}">
                        <span class="font-bold capitalize text-[#064782]">{{ $module->module }}</span>
                        <span class="rounded-full bg-white px-3 py-1 text-sm font-black text-[#149BD7]">{{ $module->total }}</span>
                    </a>
                @empty
                    <p class="text-slate-500">Aún no hay contenido registrado.</p>
                @endforelse
            </div>
        </article>

        <article class="manta-card p-6">
            <h2 class="text-xl font-black uppercase text-[#064782]">Actividad reciente</h2>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[620px] text-left text-sm">
                    <thead class="bg-[#eef9fd] text-xs uppercase text-[#064782]">
                        <tr><th class="px-4 py-3">Título</th><th>Módulo</th><th>Estado</th><th>Actualizado</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($recentItems as $item)
                            <tr class="border-b border-[#cfeafa]">
                                <td class="px-4 py-3 font-bold">{{ $item->title }}</td>
                                <td class="capitalize">{{ $item->module }}</td>
                                <td><span class="rounded-full bg-[#eef9fd] px-3 py-1 text-xs font-black uppercase text-[#064782]">{{ $item->status }}</span></td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
