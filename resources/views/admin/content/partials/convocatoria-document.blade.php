@php
    $docPath = $doc['path'] ?? null;
    $docUrl = $docPath ? (str_starts_with($docPath, 'http') || str_starts_with($docPath, '/') ? $docPath : '/storage/'.$docPath) : null;
@endphp

<article data-convocatoria-document class="grid gap-4 rounded-lg border border-[#cfeafa] bg-[#eef9fd] p-4">
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <strong data-document-label class="text-sm font-black uppercase text-[#064782]">Documento {{ is_numeric($docIndex) ? $docIndex + 1 : 1 }}</strong>
        <div class="flex flex-wrap gap-2">
            @if ($docUrl)
                <a class="manta-action-outline px-4 py-2 text-xs" href="{{ $docUrl }}" target="_blank" rel="noopener">Ver PDF actual</a>
            @endif
            <button type="button" data-remove-document class="manta-action-outline px-4 py-2 text-xs">Quitar documento</button>
        </div>
    </div>
    <input type="hidden" name="metadata[documents][{{ $docIndex }}][path]" value="{{ $docPath }}">
    <div class="grid gap-4 md:grid-cols-2">
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Título del documento
            <input class="{{ $inputClass }}" name="metadata[documents][{{ $docIndex }}][title]" value="{{ $doc['title'] ?? '' }}" placeholder="Ej: Bases Concurso Público Grúas 2026">
        </label>
        <label class="grid gap-2 text-sm font-bold text-slate-700">
            Subtítulo del documento
            <input class="{{ $inputClass }}" name="metadata[documents][{{ $docIndex }}][subtitle]" value="{{ $doc['subtitle'] ?? '' }}" placeholder="Ej: Documento base del concurso público">
        </label>
    </div>
    <label class="grid gap-2 text-sm font-bold text-slate-700">
        PDF del documento
        <input class="rounded-lg border border-dashed border-[#149BD7] bg-white px-4 py-4 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#064782] file:px-4 file:py-2 file:text-sm file:font-black file:uppercase file:text-white hover:file:bg-[#149BD7]" name="convocatoria_documents[{{ $docIndex }}]" type="file" accept=".pdf">
        <span class="text-xs font-medium text-slate-500">Selecciona un PDF. Si ya existe uno, el nuevo archivo lo reemplazará.</span>
    </label>
</article>
