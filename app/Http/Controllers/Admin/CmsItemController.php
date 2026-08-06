<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsItem;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CmsItemController extends Controller
{
    public const MODULES = [
        'inicio' => 'Inicio',
        'mision_vision' => 'Misión y Visión',
        'boletines' => 'Boletines',
        'convocatorias' => 'Convocatorias',
        'textos' => 'Textos generales',
        'noticias' => 'Noticias',
        'servicios' => 'Servicios',
        'transparencia' => 'Rendición de Cuentas',
        'lotaip' => 'LOTAIP',
        'pdfs' => 'PDFs',
        'fotos' => 'Fotos',
        'foros' => 'Foros',
    ];

    public function index(Request $request): View
    {
        $modules = collect(self::MODULES)
            ->filter(fn ($label, $module) => $request->user()->canManageModule($module));

        $stats = CmsItem::query()
            ->selectRaw("module, count(*) as total, sum(case when status = 'published' then 1 else 0 end) as published")
            ->whereIn('module', $modules->keys())
            ->groupBy('module')
            ->get()
            ->keyBy('module');

        $mediaStats = MediaAsset::query()
            ->selectRaw('module, count(*) as total')
            ->whereIn('module', $modules->keys())
            ->groupBy('module')
            ->get()
            ->keyBy('module');

        return view('admin.content.index', [
            'modules' => $modules,
            'stats' => $stats,
            'mediaStats' => $mediaStats,
        ]);
    }

    public function module(Request $request, string $module): View
    {
        $this->ensureKnownModule($module);
        $this->authorizeModule($module);

        $search = $request->string('search')->toString();

        return view('admin.content.module', [
            'module' => $module,
            'moduleLabel' => self::MODULES[$module],
            'modules' => self::MODULES,
            'search' => $search,
            'items' => CmsItem::query()
                ->where('module', $module)
                ->when($search, fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")))
                ->orderBy('sort_order')
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'assets' => MediaAsset::query()
                ->where('module', $module)
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        unset($data['asset_file']);
        $this->authorizeModule($data['module']);

        $metadata = $this->metadataFromRequest($request);
        $metadata = $this->attachUploadedAsset($request, $data, $metadata);
        $metadata = $this->normalizeMetadata($data, $metadata);
        $key = $data['key'] ?: $this->uniqueKey($data['module'], $data['title']);

        $item = CmsItem::create([
            ...$data,
            'key' => $key,
            'metadata' => $metadata,
            'published_at' => $data['status'] === 'published' ? now() : null,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $this->keepOnlyOnePublishedCall($item);

        return back()->with('status', 'Contenido creado correctamente.');
    }

    public function storeForModule(Request $request, string $module): RedirectResponse
    {
        $this->ensureKnownModule($module);
        $request->merge(['module' => $module]);

        return $this->store($request);
    }

    public function update(Request $request, CmsItem $cmsItem): RedirectResponse
    {
        $data = $this->validated($request, $cmsItem);
        unset($data['asset_file']);
        $this->authorizeModule($cmsItem->module);
        $this->authorizeModule($data['module']);

        $cmsItem->update([
            ...$data,
            'metadata' => $this->normalizeMetadata($data, $this->attachUploadedAsset($request, $data, $this->metadataFromRequest($request))),
            'published_at' => $data['status'] === 'published' ? ($cmsItem->published_at ?? now()) : null,
            'updated_by' => $request->user()->id,
        ]);

        $this->keepOnlyOnePublishedCall($cmsItem);

        return back()->with('status', 'Contenido actualizado correctamente.');
    }

    public function destroy(Request $request, CmsItem $cmsItem): RedirectResponse
    {
        $this->authorizeModule($cmsItem->module);
        $cmsItem->delete();

        return back()->with('status', 'Contenido eliminado.');
    }

    private function validated(Request $request, ?CmsItem $item = null): array
    {
        return $request->validate([
            'module' => ['required', Rule::in(array_keys(self::MODULES))],
            'key' => [
                'nullable',
                'string',
                'max:140',
                Rule::unique('cms_items', 'key')->ignore($item?->id),
            ],
            'title' => ['required', 'string', 'max:180'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'asset_file' => ['nullable', 'file', 'max:51200', 'mimes:jpg,jpeg,png,webp,pdf,mp4'],
        ]);
    }

    private function metadataFromRequest(Request $request): array
    {
        return collect($request->input('metadata', []))
            ->filter(fn ($value) => filled($value))
            ->all();
    }

    private function authorizeModule(string $module): void
    {
        if (! request()->user()->canManageModule($module)) {
            abort(403, 'No tienes permiso para gestionar este módulo.');
        }
    }

    private function ensureKnownModule(string $module): void
    {
        abort_unless(array_key_exists($module, self::MODULES), 404);
    }

    private function uniqueKey(string $module, string $title): string
    {
        $base = Str::slug($module.'-'.$title) ?: $module.'-contenido';
        $key = $base;
        $suffix = 2;

        while (CmsItem::where('key', $key)->exists()) {
            $key = $base.'-'.$suffix;
            $suffix++;
        }

        return $key;
    }

    private function attachUploadedAsset(Request $request, array $data, array $metadata): array
    {
        if (! $request->hasFile('asset_file')) {
            return $metadata;
        }

        $file = $request->file('asset_file');
        $type = match (true) {
            str_starts_with($file->getMimeType(), 'image/') => 'image',
            $file->getMimeType() === 'application/pdf' => 'pdf',
            str_starts_with($file->getMimeType(), 'video/') => 'video',
            default => 'file',
        };

        $path = $file->store("cms/{$data['module']}", 'public');

        MediaAsset::create([
            'module' => $data['module'],
            'type' => $type,
            'title' => $data['title'],
            'alt' => $request->input('metadata.alt') ?: $data['title'],
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return [
            ...$metadata,
            'asset' => $path,
            'type' => $metadata['type'] ?? strtoupper($type),
        ];
    }

    private function normalizeMetadata(array $data, array $metadata): array
    {
        $asset = $metadata['asset'] ?? null;
        $isPdf = is_string($asset) && str_ends_with(strtolower($asset), '.pdf');

        if ($isPdf && in_array($data['module'], ['pdfs', 'transparencia', 'lotaip'], true)) {
            $metadata['year'] = $metadata['year'] ?? (string) now()->year;
            $metadata['phase'] = $metadata['phase'] ?? 'Documentos publicados';
            $metadata['type'] = 'PDF';
        }

        return $metadata;
    }

    private function keepOnlyOnePublishedCall(CmsItem $item): void
    {
        if ($item->module !== 'convocatorias' || $item->status !== 'published') {
            return;
        }

        CmsItem::where('module', 'convocatorias')
            ->where('id', '!=', $item->id)
            ->where('status', 'published')
            ->update([
                'status' => 'draft',
                'published_at' => null,
                'updated_by' => request()->user()->id,
            ]);
    }
}
