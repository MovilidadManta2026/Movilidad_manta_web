@php
    $metadata = $item ? ($item->metadata ?? []) : old('metadata', []);
    $lockedModule = $lockedModule ?? null;
    $value = fn (string $field, mixed $default = '') => $item ? data_get($item, $field, $default) : old($field, $default);
    $currentModule = $lockedModule ?? $value('module');
    $asset = $metadata['asset'] ?? null;
    $assetUrl = $asset ? (str_starts_with($asset, 'http') || str_starts_with($asset, '/') ? $asset : '/storage/'.$asset) : null;
    $inputClass = 'rounded-lg border border-[#aee0f6] bg-white px-4 py-3 outline-none transition focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20';
    $fileAccept = $currentModule === 'popup_convocatorias' ? '.jpg,.jpeg,.png,.webp' : '.jpg,.jpeg,.png,.webp,.pdf,.mp4';
@endphp

<div class="rounded-lg border border-[#cfeafa] bg-[#eef9fd] p-4 text-sm text-slate-600">
    <strong class="block text-[#064782]">Edita lo importante primero.</strong>
    Los campos avanzados son opcionales. Para publicar en la web, deja el estado como “Publicado”.
</div>

<section class="grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-4">
    <div>
        <h3 class="text-sm font-black uppercase text-[#064782]">1. Contenido principal</h3>
        <p class="mt-1 text-xs text-slate-500">Esto es lo que verá el ciudadano en la página.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Módulo
            @if ($lockedModule)
                <input type="hidden" name="module" value="{{ $lockedModule }}">
                <input class="{{ $inputClass }} bg-[#eef9fd] font-black text-[#064782]" value="{{ $modules[$lockedModule] }}" readonly>
            @else
                <select class="{{ $inputClass }}" name="module" required>
                    @foreach ($modules as $key => $label)
                        <option value="{{ $key }}" @selected($value('module') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            @endif
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Estado de publicación
            <select class="{{ $inputClass }}" name="status" required>
                @foreach (['published' => 'Publicado en la web', 'draft' => 'Borrador privado', 'archived' => 'Archivado'] as $key => $label)
                    <option value="{{ $key }}" @selected($value('status', $item ? 'draft' : 'published') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <label class="grid gap-2 text-sm font-bold text-slate-700">
        Título
        <input class="{{ $inputClass }}" name="title" value="{{ $value('title') }}" placeholder="Ej: Informe final de rendición de cuentas" required>
    </label>

    <label class="grid gap-2 text-sm font-bold text-slate-700">
        Subtítulo
        <input class="{{ $inputClass }}" name="metadata[subtitle]" value="{{ $metadata['subtitle'] ?? '' }}" placeholder="Ej: Comunicado oficial de movilidad">
    </label>

    <label class="grid gap-2 text-sm font-bold text-slate-700">
        Descripción / texto
        <textarea class="{{ $inputClass }} min-h-32" name="content" placeholder="Escribe el resumen, noticia o texto que aparecerá en la web.">{{ $value('content') }}</textarea>
    </label>
</section>

@if ($currentModule === 'popup_convocatorias')
    <section class="grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-4">
        <div>
            <h3 class="text-sm font-black uppercase text-[#064782]">Tiempo del popup</h3>
            <p class="mt-1 text-xs text-slate-500">El aviso solo aparecerá en la página principal durante este rango. Si dejas una fecha vacía, no tendrá ese límite.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Mostrar desde
                <input class="{{ $inputClass }}" name="metadata[starts_at]" type="datetime-local" value="{{ $metadata['starts_at'] ?? '' }}">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Mostrar hasta
                <input class="{{ $inputClass }}" name="metadata[ends_at]" type="datetime-local" value="{{ $metadata['ends_at'] ?? '' }}">
            </label>
        </div>
    </section>
@endif

@if ($currentModule === 'convocatorias')
    @php
        $convocatoriaDocs = $metadata['documents'] ?? [];
        $defaultDocTitles = [
            'Bases Concurso Público',
            'Cronograma y Tarifario',
            'Requisitos y Formularios',
            'Acta Concurso Público',
        ];
    @endphp
    <section class="grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-4">
        <div>
            <h3 class="text-sm font-black uppercase text-[#064782]">Documentos de convocatoria</h3>
            <p class="mt-1 text-xs text-slate-500">Configura los cuatro documentos que aparecerán como botones de descarga en la convocatoria pública.</p>
        </div>

        <div class="grid gap-4">
            @foreach (range(0, 3) as $docIndex)
                @php
                    $doc = $convocatoriaDocs[$docIndex] ?? [];
                    $docPath = $doc['path'] ?? null;
                    $docUrl = $docPath ? (str_starts_with($docPath, 'http') || str_starts_with($docPath, '/') ? $docPath : '/storage/'.$docPath) : null;
                @endphp
                <article class="grid gap-4 rounded-lg border border-[#cfeafa] bg-[#eef9fd] p-4">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <strong class="text-sm font-black uppercase text-[#064782]">Documento {{ $docIndex + 1 }}</strong>
                        @if ($docUrl)
                            <a class="manta-action-outline px-4 py-2 text-xs" href="{{ $docUrl }}" target="_blank" rel="noopener">Ver PDF actual</a>
                        @endif
                    </div>
                    <input type="hidden" name="metadata[documents][{{ $docIndex }}][path]" value="{{ $docPath }}">
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="grid gap-2 text-sm font-bold text-slate-700">
                            Título del documento
                            <input class="{{ $inputClass }}" name="metadata[documents][{{ $docIndex }}][title]" value="{{ $doc['title'] ?? $defaultDocTitles[$docIndex] }}" placeholder="Ej: Bases Concurso Público Grúas 2026">
                        </label>
                        <label class="grid gap-2 text-sm font-bold text-slate-700">
                            Subtítulo del documento
                            <input class="{{ $inputClass }}" name="metadata[documents][{{ $docIndex }}][subtitle]" value="{{ $doc['subtitle'] ?? '' }}" placeholder="Ej: Documento base del concurso público">
                        </label>
                    </div>
                    <label class="grid gap-2 text-sm font-bold text-slate-700">
                        PDF del documento
                        <input class="rounded-lg border border-dashed border-[#149BD7] bg-white px-4 py-4 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#064782] file:px-4 file:py-2 file:text-sm file:font-black file:uppercase file:text-white hover:file:bg-[#149BD7]" name="convocatoria_documents[{{ $docIndex }}]" type="file" accept=".pdf">
                        <span class="text-xs font-medium text-slate-500">Sube un PDF para reemplazar el documento actual.</span>
                    </label>
                </article>
            @endforeach
        </div>
    </section>
@endif

<section class="grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-4">
    <div>
        <h3 class="text-sm font-black uppercase text-[#064782]">2. Archivo o enlace</h3>
        <p class="mt-1 text-xs text-slate-500">
            @if ($currentModule === 'popup_convocatorias')
                Sube la imagen de la convocatoria que se mostrará dentro del popup.
            @else
                Sube una imagen, PDF o video, o pega un enlace externo si no tienes el documento cargado.
            @endif
        </p>
    </div>

    @if ($assetUrl)
        <div class="flex flex-col gap-3 rounded-lg border border-[#aee0f6] bg-[#eef9fd] p-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <strong class="block text-sm text-[#064782]">Archivo actual</strong>
                <span class="block truncate text-xs text-slate-500">{{ $asset }}</span>
            </div>
            <a class="manta-action-outline shrink-0 px-4 py-2 text-xs" href="{{ $assetUrl }}" target="_blank" rel="noopener">Ver archivo</a>
        </div>
    @endif

    <label class="grid gap-2 text-sm font-bold text-slate-700">
        Subir nuevo archivo
        <input class="rounded-lg border border-dashed border-[#149BD7] bg-[#eef9fd] px-4 py-5 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#064782] file:px-4 file:py-2 file:text-sm file:font-black file:uppercase file:text-white hover:file:bg-[#149BD7]" name="asset_file" type="file" accept="{{ $fileAccept }}">
        <span class="text-xs font-medium text-slate-500">
            @if ($currentModule === 'popup_convocatorias')
                Formatos: JPG, PNG o WEBP. Máximo 50 MB.
            @else
                Formatos: JPG, PNG, WEBP, PDF o MP4. Máximo 50 MB.
            @endif
        </span>
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Enlace externo o página destino
            <input class="{{ $inputClass }}" name="metadata[url]" value="{{ $metadata['url'] ?? '' }}" placeholder="Ej: https://... o /contacto">
            <span class="text-xs font-medium text-slate-500">En LOTAIP puedes publicar solo enlace con título, subtítulo, año y fase.</span>
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Texto alternativo del archivo
            <input class="{{ $inputClass }}" name="metadata[alt]" value="{{ $metadata['alt'] ?? '' }}" placeholder="Descripción corta para accesibilidad">
        </label>
    </div>
</section>

<section class="grid gap-4 rounded-lg border border-[#cfeafa] bg-white p-4">
    <div>
        <h3 class="text-sm font-black uppercase text-[#064782]">3. Organización</h3>
        <p class="mt-1 text-xs text-slate-500">Ayuda a ordenar, filtrar y mostrar correctamente el contenido.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Orden
            <input class="{{ $inputClass }}" name="sort_order" type="number" min="0" value="{{ $value('sort_order', 0) }}" required>
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Categoría
            <input class="{{ $inputClass }}" name="metadata[category]" value="{{ $metadata['category'] ?? '' }}" placeholder="obras, eventos, pagos...">
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Fecha visible
            <input class="{{ $inputClass }}" name="metadata[date]" value="{{ $metadata['date'] ?? '' }}" placeholder="20 mayo, 2024">
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Fecha de inicio
            <input class="{{ $inputClass }}" name="metadata[start_date]" value="{{ $metadata['start_date'] ?? '' }}" placeholder="01 agosto, 2026">
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Fecha de cierre
            <input class="{{ $inputClass }}" name="metadata[end_date]" value="{{ $metadata['end_date'] ?? '' }}" placeholder="15 agosto, 2026">
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Año
            <input class="{{ $inputClass }}" name="metadata[year]" value="{{ $metadata['year'] ?? '' }}" placeholder="2026">
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Fase / grupo
            <input class="{{ $inputClass }}" name="metadata[phase]" value="{{ $metadata['phase'] ?? '' }}" placeholder="Documentos publicados">
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Icono
            <input class="{{ $inputClass }}" name="metadata[icon]" value="{{ $metadata['icon'] ?? '' }}" placeholder="doc, bus, mail...">
        </label>
    </div>
</section>

<details class="rounded-lg border border-[#cfeafa] bg-white p-4">
    <summary class="cursor-pointer text-sm font-black uppercase text-[#064782]">Opciones avanzadas</summary>
    <div class="mt-4 grid gap-4">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Clave técnica
            <input class="{{ $inputClass }}" name="key" value="{{ $value('key') }}" placeholder="Se genera sola si la dejas vacía">
        </label>
        <div class="grid gap-4 md:grid-cols-3">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Ruta manual del archivo
                <input class="{{ $inputClass }}" name="metadata[asset]" value="{{ $asset ?? '' }}" placeholder="cms/modulo/archivo.pdf">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Ubicación especial
                <input class="{{ $inputClass }}" name="metadata[placement]" value="{{ $metadata['placement'] ?? '' }}" placeholder="shortcut">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Tipo visible
                <input class="{{ $inputClass }}" name="metadata[type]" value="{{ $metadata['type'] ?? '' }}" placeholder="PDF, WEB, VIDEO">
            </label>
        </div>
    </div>
</details>
