<?php

namespace App\Http\Controllers;

use App\Models\CmsItem;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    public function convocatorias(): View
    {
        return view('public.convocatorias-index', [
            'convocatorias' => CmsItem::published()
                ->where('module', 'convocatorias')
                ->orderByDesc('published_at')
                ->orderBy('sort_order')
                ->paginate(10),
            'quickLinks' => $this->quickLinks(),
        ]);
    }

    public function convocatoria(CmsItem $cmsItem): View
    {
        abort_unless($cmsItem->status === 'published' && $cmsItem->module === 'convocatorias', 404);

        return view('public.convocatorias-show', [
            'convocatoria' => $cmsItem,
            'documents' => collect($cmsItem->metadata['documents'] ?? [])->filter(fn ($document) => filled($document['path'] ?? null)),
            'quickLinks' => $this->quickLinks(),
        ]);
    }

    public function estructuraOrganica(): View
    {
        return view('public.estructura-organica', [
            'quickLinks' => $this->quickLinks(),
        ]);
    }

    public function show(CmsItem $cmsItem): View
    {
        abort_unless($cmsItem->status === 'published', 404);

        return view('public.content-show', [
            'item' => $cmsItem,
            'quickLinks' => $this->quickLinks(),
        ]);
    }

    private function quickLinks()
    {
        return CmsItem::published()
            ->where('module', 'inicio')
            ->where('metadata->placement', 'shortcut')
            ->orderBy('sort_order')
            ->get();
    }
}
