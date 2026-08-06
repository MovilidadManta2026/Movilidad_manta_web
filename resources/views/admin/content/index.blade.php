@extends('admin.layout')

@section('title', 'Módulos de edición')

@section('content')
    @php
        $moduleMeta = [
            'inicio' => ['area' => 'Página principal', 'help' => 'Héroe, accesos directos y documentos destacados.', 'action' => 'Editar inicio'],
            'mision_vision' => ['area' => 'Institucional', 'help' => 'Misión, visión y principios institucionales visibles en la web.', 'action' => 'Editar misión y visión'],
            'boletines' => ['area' => 'Comunicados', 'help' => 'Carrusel de boletines con popup de comunicado oficial.', 'action' => 'Gestionar boletines'],
            'convocatorias' => ['area' => 'Popup inicial', 'help' => 'Convocatoria informativa que aparece al abrir la página. Solo una puede estar activa.', 'action' => 'Gestionar convocatorias'],
            'textos' => ['area' => 'Textos globales', 'help' => 'Mensajes reutilizables y bloques generales.', 'action' => 'Editar textos'],
            'noticias' => ['area' => 'Noticias', 'help' => 'Publicaciones con imagen, fecha, categoría y detalle.', 'action' => 'Gestionar noticias'],
            'servicios' => ['area' => 'Trámites', 'help' => 'Tarjetas de servicios, enlaces y búsquedas ciudadanas.', 'action' => 'Gestionar servicios'],
            'transparencia' => ['area' => 'Rendición', 'help' => 'Documentos de rendición de cuentas agrupados por año y fase.', 'action' => 'Gestionar rendición'],
            'lotaip' => ['area' => 'LOTAIP', 'help' => 'Información pública LOTAIP organizada por año y categoría.', 'action' => 'Gestionar LOTAIP'],
            'pdfs' => ['area' => 'Documentos', 'help' => 'PDFs descargables para la web pública.', 'action' => 'Gestionar PDFs'],
            'fotos' => ['area' => 'Galería', 'help' => 'Imágenes para galerías y secciones visuales.', 'action' => 'Gestionar fotos'],
            'foros' => ['area' => 'Participación', 'help' => 'Foros, convocatorias y espacios ciudadanos.', 'action' => 'Gestionar foros'],
        ];
    @endphp

    <section class="mb-6 rounded-lg border border-[#cfeafa] bg-white p-6 shadow-xl shadow-[#064782]/10">
        <span class="text-xs font-black uppercase tracking-wide text-[#149BD7]">Centro de control</span>
        <h2 class="mt-1 text-2xl font-black text-[#064782]">Elige qué parte de la web quieres editar</h2>
        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Cada módulo funciona como un CRUD independiente. Entra al módulo, crea o edita registros, sube archivos y publica cuando esté listo.</p>
    </section>

    <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($modules as $module => $label)
            @php
                $contentTotal = (int) ($stats[$module]->total ?? 0);
                $publishedTotal = (int) ($stats[$module]->published ?? 0);
                $mediaTotal = (int) ($mediaStats[$module]->total ?? 0);
                $meta = $moduleMeta[$module] ?? ['area' => 'Sitio web', 'help' => 'Gestiona contenido publicado.', 'action' => 'Editar módulo'];
            @endphp
            <a class="manta-card group p-6 transition hover:-translate-y-1 hover:shadow-2xl" href="{{ route('admin.modules.show', $module) }}">
                <span class="text-xs font-black uppercase tracking-wide text-[#149BD7]">{{ $meta['area'] }}</span>
                <h2 class="mt-2 text-2xl font-black uppercase text-[#064782]">{{ $label }}</h2>
                <p class="mt-3 min-h-16 text-sm leading-6 text-slate-500">{{ $meta['help'] }}</p>
                <div class="mt-6 grid grid-cols-3 gap-2 text-center text-xs font-black uppercase">
                    <span class="rounded-lg bg-[#eef9fd] p-3 text-[#064782]"><b class="block text-xl">{{ $contentTotal }}</b>Registros</span>
                    <span class="rounded-lg bg-[#eef9fd] p-3 text-[#064782]"><b class="block text-xl">{{ $publishedTotal }}</b>Publicados</span>
                    <span class="rounded-lg bg-[#eef9fd] p-3 text-[#064782]"><b class="block text-xl">{{ $mediaTotal }}</b>Archivos</span>
                </div>
                <span class="mt-6 inline-flex font-black uppercase text-[#149BD7] group-hover:text-[#064782]">{{ $meta['action'] }} →</span>
            </a>
        @endforeach
    </section>
@endsection
