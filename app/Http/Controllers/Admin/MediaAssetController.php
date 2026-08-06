<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsItem;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(Request $request): View
    {
        $module = $request->string('module')->toString();
        $modules = collect(\App\Http\Controllers\Admin\CmsItemController::MODULES)
            ->filter(fn ($label, $key) => $request->user()->canManageModule($key));

        if ($module && ! $modules->has($module)) {
            abort(403, 'No tienes permiso para ver archivos de este módulo.');
        }

        return view('admin.media.index', [
            'assets' => MediaAsset::query()
                ->whereIn('module', $modules->keys())
                ->when($module, fn ($query) => $query->where('module', $module))
                ->latest()
                ->paginate(16)
                ->withQueryString(),
            'selectedModule' => $module,
            'modules' => $modules,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'module' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:180'],
            'alt' => ['nullable', 'string', 'max:220'],
            'file' => ['required', 'file', 'max:51200', 'mimes:jpg,jpeg,png,webp,pdf,mp4'],
        ]);

        if (! $request->user()->canManageModule($data['module'])) {
            abort(403, 'No tienes permiso para cargar archivos en este módulo.');
        }

        $file = $request->file('file');
        $type = match (true) {
            str_starts_with($file->getMimeType(), 'image/') => 'image',
            $file->getMimeType() === 'application/pdf' => 'pdf',
            str_starts_with($file->getMimeType(), 'video/') => 'video',
            default => 'file',
        };

        $path = $file->store("cms/{$data['module']}", 'public');

        $asset = MediaAsset::create([
            ...$data,
            'type' => $type,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        $this->publishAssetIfNeeded($request, $asset);

        return back()->with('status', 'Archivo cargado correctamente.');
    }

    public function destroy(Request $request, MediaAsset $mediaAsset): RedirectResponse
    {
        if (! $request->user()->canManageModule($mediaAsset->module)) {
            abort(403);
        }

        Storage::disk('public')->delete($mediaAsset->path);
        $mediaAsset->delete();

        return back()->with('status', 'Archivo eliminado.');
    }

    private function publishAssetIfNeeded(Request $request, MediaAsset $asset): void
    {
        if ($asset->type !== 'pdf' || ! in_array($asset->module, ['pdfs', 'transparencia', 'lotaip'], true)) {
            return;
        }

        $year = now()->year;
        $baseKey = Str::slug($asset->module.'-'.$year.'-'.$asset->title) ?: $asset->module.'-'.$asset->id;
        $key = $baseKey;
        $suffix = 2;

        while (CmsItem::where('key', $key)->exists()) {
            $key = $baseKey.'-'.$suffix;
            $suffix++;
        }

        CmsItem::create([
            'module' => $asset->module,
            'key' => $key,
            'title' => $asset->title,
            'content' => $asset->alt ?: 'Documento publicado desde la biblioteca de archivos.',
            'metadata' => [
                'asset' => $asset->path,
                'year' => (string) $year,
                'phase' => 'Documentos publicados',
                'type' => 'PDF',
                'alt' => $asset->alt,
            ],
            'status' => 'published',
            'sort_order' => 0,
            'published_at' => now(),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);
    }
}
