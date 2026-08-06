<?php

namespace App\Http\Controllers;

use App\Models\CmsItem;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function __invoke(Request $request): View
    {
        if (! Schema::hasTable('cms_items')) {
            $emptyPaginator = new LengthAwarePaginator([], 0, 6);

            return view('welcome', [
                'cms' => new Collection(),
                'featuredNews' => collect(),
                'missionVisionItems' => collect(),
                'newsItems' => $emptyPaginator,
                'newsCategory' => 'todas',
                'newsCategories' => collect(),
                'services' => collect(),
                'serviceCategories' => collect(),
                'quickLinks' => collect(),
                'bulletins' => collect(),
                'activeCall' => null,
                'latestDocuments' => collect(),
                'accountYears' => collect(),
                'accountYear' => now()->year,
                'accountPhases' => collect(),
                'lotaipYears' => collect(),
                'lotaipYear' => now()->year,
                'lotaipPhases' => collect(),
                'galleryAssets' => collect(),
            ]);
        }

        $cms = CmsItem::published()->get()->keyBy('key');
        $this->ensurePaymentServices();
        $newsCategory = $request->string('category', 'todas')->toString();
        $serviceSearch = $request->string('q')->toString();
        $accountYears = CmsItem::published()->whereIn('module', ['transparencia', 'pdfs'])->pluck('metadata')
            ->map(fn ($metadata) => $metadata['year'] ?? null)->filter()->unique()->sortDesc()->values();
        $accountYear = (int) ($request->integer('year') ?: ($accountYears->first() ?: now()->year));
        $lotaipYears = CmsItem::published()->where('module', 'lotaip')->pluck('metadata')
            ->map(fn ($metadata) => $metadata['year'] ?? null)->filter()->unique()->sortDesc()->values();
        $lotaipYear = (int) ($request->integer('year') ?: ($lotaipYears->first() ?: now()->year));

        $newsQuery = CmsItem::published()
            ->where('module', 'noticias')
            ->when($newsCategory !== 'todas', fn ($query) => $query->where('metadata->category', $newsCategory))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $accountQuery = CmsItem::published()
            ->whereIn('module', ['transparencia', 'pdfs'])
            ->when($accountYear, fn ($query) => $query->where('metadata->year', (string) $accountYear))
            ->orderBy('sort_order')
            ->orderBy('title');

        $lotaipQuery = CmsItem::published()
            ->where('module', 'lotaip')
            ->when($lotaipYear, fn ($query) => $query->where('metadata->year', (string) $lotaipYear))
            ->orderBy('sort_order')
            ->orderBy('title');

        return view('welcome', [
            'cms' => $cms,
            'featuredNews' => CmsItem::published()->where('module', 'noticias')->orderBy('sort_order')->take(3)->get(),
            'missionVisionItems' => CmsItem::published()->where('module', 'mision_vision')->orderBy('sort_order')->get(),
            'newsItems' => $newsQuery->paginate(6)->withQueryString(),
            'newsCategory' => $newsCategory,
            'newsCategories' => CmsItem::published()->where('module', 'noticias')->pluck('metadata')
                ->map(fn ($metadata) => $metadata['category'] ?? null)->filter()->unique()->values(),
            'services' => CmsItem::published()
                ->where('module', 'servicios')
                ->when($serviceSearch, fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('title', 'like', "%{$serviceSearch}%")
                    ->orWhere('content', 'like', "%{$serviceSearch}%")
                    ->orWhere('metadata->category', $serviceSearch)))
                ->orderBy('sort_order')
                ->get(),
            'serviceSearch' => $serviceSearch,
            'serviceCategories' => CmsItem::published()->where('module', 'servicios')->pluck('metadata')
                ->map(fn ($metadata) => $metadata['category'] ?? null)->filter()->unique()->values(),
            'quickLinks' => CmsItem::published()->where('module', 'inicio')->where('metadata->placement', 'shortcut')->orderBy('sort_order')->get(),
            'bulletins' => CmsItem::published()->where('module', 'boletines')->orderBy('sort_order')->latest('published_at')->take(12)->get(),
            'activeCall' => CmsItem::published()->where('module', 'convocatorias')->latest('published_at')->first(),
            'latestDocuments' => CmsItem::published()
                ->whereIn('module', ['transparencia', 'pdfs'])
                ->latest('published_at')
                ->take(12)
                ->get()
                ->filter(fn (CmsItem $item) => filled($item->metadataValue('asset')))
                ->take(4),
            'accountYears' => $accountYears,
            'accountYear' => $accountYear,
            'accountPhases' => $accountQuery->get()->groupBy(fn (CmsItem $item) => $item->metadataValue('phase', 'General')),
            'lotaipYears' => $lotaipYears,
            'lotaipYear' => $lotaipYear,
            'lotaipPhases' => $lotaipQuery->get()->groupBy(fn (CmsItem $item) => $item->metadataValue('phase', 'General')),
            'galleryAssets' => Schema::hasTable('media_assets')
                ? MediaAsset::whereIn('module', ['fotos', 'inicio'])->where('type', 'image')->latest()->take(6)->get()
                : collect(),
        ]);
    }

    private function ensurePaymentServices(): void
    {
        CmsItem::where('key', 'service.payments')->where('title', 'Pago de Impuestos')->update(['status' => 'draft']);

        $services = [
            [
                'key' => 'service.payments.fines',
                'title' => 'Pago de multas',
                'content' => 'Consulta y paga multas de tránsito emitidas en Manta de forma rápida y segura.',
                'sort_order' => 30,
                'metadata' => ['category' => 'pagos', 'icon' => 'bag', 'url' => 'https://autority.app.link/entidad_manta_multas'],
            ],
            [
                'key' => 'service.payments.rtv',
                'title' => 'Pago de RTV',
                'content' => 'Realiza el pago correspondiente a revisión técnica vehicular RTV Manta.',
                'sort_order' => 31,
                'metadata' => ['category' => 'pagos', 'icon' => 'tv', 'url' => 'https://autority.app.link/entidad_manta_RTV'],
            ],
        ];

        foreach ($services as $service) {
            CmsItem::firstOrCreate(
                ['key' => $service['key']],
                [
                    'module' => 'servicios',
                    'title' => $service['title'],
                    'content' => $service['content'],
                    'metadata' => $service['metadata'],
                    'status' => 'published',
                    'sort_order' => $service['sort_order'],
                    'published_at' => now(),
                ]
            );
        }
    }
}
