<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal ciudadano de la Alcaldía de Manta">
    <title>Manta | Alcaldía Ciudadana</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef9fd] text-slate-900 antialiased">
    @php
        $homeHeroTitle = $cms['home.hero.title']->content ?? "Manta\nes progreso\nes tu ciudad";
        $homeHeroAccent = $cms['home.hero.accent']->content ?? 'es progreso';
        $homeHeroSubtitle = $cms['home.hero.subtitle']->content ?? 'Construimos juntos una ciudad moderna, sostenible y con oportunidades para todos.';
        $homeHeroButton = $cms['home.hero.button']->content ?? 'Conoce más';
        $iconMap = [
            'pagos' => 'bag',
            'permisos' => 'file',
            'tramites' => 'doc',
            'transito' => 'bus',
            'atencion' => 'user',
            'consultas' => 'info',
            'boletines' => 'doc',
            'resoluciones' => 'file',
            'convocatorias' => 'megaphone',
            'rtv' => 'tv',
            'terminal' => 'bus',
            'sgi' => 'chart',
            'intranet' => 'globe',
            'fotos' => 'star',
            'pdfs' => 'doc',
        ];
        $externalAttrs = fn (?string $url) => str_starts_with($url ?? '', 'http') ? 'target="_blank" rel="noopener noreferrer"' : '';
        $socialLinks = [
            ['label' => 'Facebook', 'url' => 'https://www.facebook.com/EPMovildadManta/', 'icon' => 'f'],
            ['label' => 'Instagram', 'url' => 'https://www.instagram.com/terminal_terrestre_de_manta/', 'icon' => '◎'],
            ['label' => 'X', 'url' => 'https://x.com/TerminaldeManta', 'icon' => 'X'],
            ['label' => 'TikTok', 'url' => 'https://www.tiktok.com/@terminalterrestredemanta', 'icon' => '▶'],
        ];
    @endphp
    <div id="page-loader" class="page-loader" aria-hidden="true">
        <img class="loader-logo" src="/assets/brand/logo.png" alt="Manta Intervención">
        <span class="loader-line"></span>
    </div>

    <header class="site-header border-b border-[#cfeafa] bg-white/95 shadow-sm backdrop-blur-xl">
        <a class="brand" href="/" data-route>
            <img src="/assets/brand/logo.png" alt="Manta Intervención">
        </a>
        <nav class="main-nav" id="main-nav" aria-label="Navegacion principal">
            <a href="/" data-route data-nav="home">Inicio</a>
            <a href="/mision-vision" data-route data-nav="missionVision">Misión y Visión</a>
            <div class="nav-dropdown">
                <button type="button" class="nav-dropdown-trigger" data-nav="services">Servicios</button>
                <div class="nav-dropdown-menu nav-dropdown-menu-wide">
                    <a href="/servicios" data-route data-nav="services">Todos los servicios</a>
                    @foreach ($quickLinks as $link)
                        @php $linkUrl = $link->actionUrl(); @endphp
                        <a href="{{ $linkUrl }}" {!! $externalAttrs($linkUrl) !!} data-route>{{ $link->title }}</a>
                    @endforeach
                </div>
            </div>
            <a href="/noticias" data-route data-nav="news">Noticias</a>
            <div class="nav-dropdown">
                <button type="button" class="nav-dropdown-trigger" data-nav="accountability">Transparencia</button>
                <div class="nav-dropdown-menu">
                    <a href="/transparencia/lotaip" data-route data-nav="lotaip">LOTAIP</a>
                    <a href="/transparencia/rendicion-de-cuentas" data-route data-nav="accountability">Rendición de Cuentas</a>
                </div>
            </div>
            <a href="/contacto" data-route data-nav="contact">Contacto</a>
        </nav>
        <div class="header-actions">
            <a class="online-btn" href="/servicios" data-route>Trámites en línea <span>→</span></a>
            <a class="icon-btn" href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" aria-label="{{ auth()->check() ? 'Ir al panel CMS' : 'Ingresar al CMS' }}" title="{{ auth()->check() ? 'Panel CMS' : 'Ingresar al CMS' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0"/></svg>
            </a>
            <button class="icon-btn" type="button" aria-label="Buscar" data-modal-target="search-modal" data-modal-toggle="search-modal">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m21 21-4.3-4.3m2.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
            </button>
            <button class="icon-btn menu-toggle" type="button" aria-label="Abrir menu" data-drawer-target="mobile-navigation" data-drawer-show="mobile-navigation" aria-controls="mobile-navigation">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </header>

    <aside id="mobile-navigation" class="fixed left-0 top-0 z-50 h-screen w-80 -translate-x-full overflow-y-auto border-r border-[#cfeafa] bg-white p-6 shadow-2xl transition-transform" tabindex="-1" aria-labelledby="mobile-navigation-label">
        <div class="mb-8 flex items-center justify-between">
            <a id="mobile-navigation-label" class="brand" href="/" data-route>
                <img src="/assets/brand/logo.png" alt="Manta Intervención">
            </a>
            <button type="button" data-drawer-hide="mobile-navigation" aria-controls="mobile-navigation" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-[#064782] hover:bg-[#eef9fd]">
                <span class="sr-only">Cerrar menú</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>
        <nav class="grid gap-2 text-sm font-black uppercase text-slate-800">
            <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/" data-route>Inicio</a>
            <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/mision-vision" data-route>Misión y Visión</a>
            <div class="mt-2 rounded-lg border border-[#cfeafa] p-2">
                <span class="block px-2 py-2 text-xs text-[#149BD7]">Servicios</span>
                <a class="block rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/servicios" data-route>Todos los servicios</a>
                @foreach ($quickLinks as $link)
                    @php $linkUrl = $link->actionUrl(); @endphp
                    <a class="block rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="{{ $linkUrl }}" {!! $externalAttrs($linkUrl) !!} data-route>{{ $link->title }}</a>
                @endforeach
            </div>
            <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/noticias" data-route>Noticias</a>
            <div class="mt-2 rounded-lg border border-[#cfeafa] p-2">
                <span class="block px-2 py-2 text-xs text-[#149BD7]">Transparencia</span>
                <a class="block rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/transparencia/lotaip" data-route>LOTAIP</a>
                <a class="block rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/transparencia/rendicion-de-cuentas" data-route>Rendición de Cuentas</a>
            </div>
            <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/contacto" data-route>Contacto</a>
            <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}">{{ auth()->check() ? 'Panel CMS' : 'Ingresar CMS' }}</a>
        </nav>
        <a class="manta-action mt-8 w-full" href="/servicios" data-route>Trámites en línea <span>→</span></a>
    </aside>

    <div id="search-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:inset-0">
        <div class="relative max-h-full w-full max-w-2xl">
            <div class="manta-card relative">
                <div class="flex items-center justify-between border-b border-[#cfeafa] p-5">
                    <h2 class="text-xl font-black uppercase text-[#064782]">Buscar servicios</h2>
                    <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-[#eef9fd] hover:text-[#064782]" data-modal-hide="search-modal">
                        <span class="sr-only">Cerrar búsqueda</span>
                        <svg class="h-4 w-4" viewBox="0 0 14 14" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form class="grid gap-4 p-6" data-modal-search-form>
                    <label class="sr-only" for="global-search">Buscar</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#149BD7]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21 21-4.3-4.3m2.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                        </div>
                        <input id="global-search" name="q" class="block w-full rounded-lg border border-[#aee0f6] bg-[#eef9fd] p-4 pl-12 text-slate-900 outline-none focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20" value="{{ $serviceSearch ?? '' }}" placeholder="Escribe: pagos, permisos, certificados..." />
                    </div>
                    <button class="manta-action" type="submit">Ir a servicios <span>→</span></button>
                </form>
            </div>
        </div>
    </div>

    <main>
        <section class="page-view" data-page="home">
            @php
                $homePanelOrder = collect(['pagos', 'rtv', 'terminal']);
                $homePanelLinks = $homePanelOrder
                    ->map(fn ($name) => $quickLinks->first(function ($link) use ($name) {
                        $haystack = str($link->title.' '.$link->metadataValue('category', ''))->lower()->ascii();

                        return $haystack->contains((string) str($name)->lower()->ascii());
                    }))
                    ->filter()
                    ->unique('id')
                    ->values();

                if ($homePanelLinks->isEmpty()) {
                    $homePanelLinks = $quickLinks->take(3);
                }

                $homePanelTitles = [
                    'pagos' => 'Pagos en línea',
                    'rtv' => 'Revisión Técnica Vehicular RTV',
                    'terminal' => 'Terminal Terrestre',
                ];
            @endphp
            <div class="hero home-hero">
                <video class="hero-video" autoplay muted loop playsinline preload="metadata" poster="/assets/brand/logo.png">
                    <source src="/assets/media/home-hero.mp4" type="video/mp4">
                </video>
                <div class="hero-bg video-overlay"></div>
                <div class="social-rail" aria-label="Redes sociales">
                    @foreach ($socialLinks as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">{{ $social['icon'] }}</a>
                    @endforeach
                </div>
                <div class="hero-content">
                    <h1>
                        @foreach (preg_split('/\r\n|\r|\n/', $homeHeroTitle) as $line)
                            @if (trim($line) === trim($homeHeroAccent))
                                <span>{{ $line }}</span>
                            @else
                                {{ $line }}
                            @endif
                            @if (! $loop->last)<br>@endif
                        @endforeach
                    </h1>
                    <p>{{ $homeHeroSubtitle }}</p>
                    <a class="primary-btn" href="/mision-vision" data-route>{{ $homeHeroButton }} <span>→</span></a>
                </div>
                <button class="video-cta" type="button" data-toast="Video institucional disponible próximamente">
                    <span>▶</span>
                    <strong>Ver video<br>institucional</strong>
                </button>
                <div class="hero-steps"><span>01</span><i></i><span>02</span><i></i><span>03</span></div>
            </div>

            <div class="quick-panel overlap">
                @forelse ($homePanelLinks as $link)
                    @php
                        $panelCategory = $link->metadataValue('category', '');
                        $panelIcon = $iconMap[$panelCategory] ?? $link->metadataValue('icon', 'doc');
                        $panelTitle = $homePanelTitles[$panelCategory] ?? $link->title;
                    @endphp
                    @php $linkUrl = $link->actionUrl(); @endphp
                    <a href="{{ $linkUrl }}" {!! $externalAttrs($linkUrl) !!} data-route><x-icon :name="$panelIcon" /> <strong>{{ $panelTitle }}</strong><span>{{ $link->content }}</span></a>
                @empty
                    <a href="/servicios" data-route><x-icon name="doc" /> <strong>Sin accesos publicados</strong><span>Agrega accesos desde el módulo Inicio del administrador.</span></a>
                @endforelse
            </div>

            <div class="content-grid two home-feature-grid">
                <article class="image-card calendar-card" aria-label="Calendario de matriculación 2026">
                    <img src="/assets/media/calendario-matriculacion-2026.png" alt="Calendario de matriculación 2026">
                </article>
                <section id="boletines" class="blue-feature bulletin-feature">
                    <div class="section-head">
                        <h2>Boletines</h2>
                    </div>
                    <div class="news-strip bulletin-feature-carousel" aria-label="Carrusel automático de boletines" data-bulletin-carousel>
                        @forelse ($bulletins as $bulletin)
                            <article class="bulletin-card" role="button" tabindex="0" data-bulletin-target="bulletin-modal-{{ $bulletin->id }}" aria-label="Ver boletín {{ $bulletin->title }}">
                                @if ($bulletin->assetUrl())
                                    <img src="{{ $bulletin->assetUrl() }}" alt="{{ $bulletin->title }}">
                                @else
                                    <div class="bulletin-card-no-media">
                                        <img src="/assets/brand/logo.png" alt="Movilidad de Manta">
                                    </div>
                                @endif
                                <h3>{{ $bulletin->title }}</h3>
                                <p>{{ $bulletin->metadataValue('subtitle', 'Comunicado oficial') }}</p>
                                <span>{{ $bulletin->displayDate() }}</span>
                            </article>
                        @empty
                            <p class="rounded-lg border border-white/30 p-5 text-sm">No hay boletines publicados.</p>
                        @endforelse
                    </div>
                    <a class="outline-light" href="#boletines">Ver boletines <span>→</span></a>
                </section>
            </div>

            <section class="section">
                <h2>Accesos directos</h2>
                <div class="shortcut-grid">
                    @forelse ($quickLinks as $link)
                        @php $shortcutIcon = $iconMap[$link->metadataValue('category', '')] ?? $link->metadataValue('icon', 'doc'); @endphp
                        @php $linkUrl = $link->actionUrl(); @endphp
                        <a href="{{ $linkUrl }}" {!! $externalAttrs($linkUrl) !!}><x-icon :name="$shortcutIcon" />{{ $link->title }}</a>
                    @empty
                        <article class="manta-card p-6 text-slate-500">No hay accesos directos publicados.</article>
                    @endforelse
                </div>
            </section>
            @foreach ($bulletins as $bulletin)
                <div id="bulletin-modal-{{ $bulletin->id }}" tabindex="-1" aria-hidden="true" class="bulletin-modal-shell hidden">
                    <div class="relative max-h-full w-full max-w-3xl">
                        <article class="bulletin-modal">
                            <button type="button" class="bulletin-close" data-bulletin-hide aria-label="Cerrar comunicado">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                            </button>
                            <div class="bulletin-stamp">
                                <img src="/assets/brand/logo.png" alt="Manta Intervención">
                                <span>Comunicado oficial</span>
                            </div>
                            <span class="bulletin-date">{{ $bulletin->displayDate() }}</span>
                            <h2>{{ $bulletin->title }}</h2>
                            <h3>{{ $bulletin->metadataValue('subtitle', 'Boletín informativo') }}</h3>
                            <div class="bulletin-copy">
                                {!! nl2br(e($bulletin->content)) !!}
                            </div>
                        </article>
                    </div>
                </div>
            @endforeach

            @if (($latestDocuments ?? collect())->isNotEmpty())
                <section class="section">
                    <h2>Documentos publicados</h2>
                    <div class="service-grid">
                        @foreach ($latestDocuments as $document)
                            <article class="service-card">
                                <x-icon name="doc" />
                                <h3>{{ $document->title }}</h3>
                                <p>{{ $document->content }}</p>
                                <a href="{{ $document->assetUrl() ?: route('public.content.show', $document) }}" target="_blank" rel="noopener">Abrir PDF <span>→</span></a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
            <x-stats />
        </section>

        <section class="page-view" data-page="missionVision">
            <x-inner-hero title="Misión y Visión" crumb="Inicio / Misión y Visión" text="Conoce el propósito institucional y la ruta de trabajo que guía nuestra gestión ciudadana." image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85" />
            <div class="mission-layout">
                @forelse ($missionVisionItems as $item)
                    <article class="mission-card">
                        @if ($item->assetUrl())
                            <img src="{{ $item->assetUrl() }}" alt="{{ $item->title }}">
                        @endif
                        <div>
                            <span>{{ $item->metadataValue('subtitle', 'Identidad institucional') }}</span>
                            <h2>{{ $item->title }}</h2>
                            <p>{!! nl2br(e($item->content)) !!}</p>
                        </div>
                    </article>
                @empty
                    <article class="mission-card">
                        <div>
                            <span>CMS</span>
                            <h2>Contenido pendiente</h2>
                            <p>Crea la misión y visión desde el módulo Misión y Visión del panel administrativo.</p>
                        </div>
                    </article>
                @endforelse
            </div>
            <x-stats />
        </section>

        <section class="page-view" data-page="city">
            <x-inner-hero title="La Ciudad" crumb="Inicio / La Ciudad" text="Manta es una ciudad vibrante, moderna y con constante desarrollo. Conoce más sobre nuestra historia, cultura, turismo y belleza natural." image="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=85" />
            <div class="quick-panel compact">
                <a><x-icon name="doc" /><strong>Historia</strong></a><a><x-icon name="globe" /><strong>Cultura</strong></a><a><x-icon name="building" /><strong>Turismo</strong></a><a><x-icon name="umbrella" /><strong>Playas</strong></a><a><x-icon name="star" /><strong>Gastronomía</strong></a>
            </div>
            <div class="content-grid two city-layout">
                <article>
                    <h2>Nuestra historia</h2>
                    <p>Fundada el 4 de noviembre de 1534, Manta es una de las ciudades más antiguas del Ecuador y un puerto estratégico para el desarrollo del país.</p>
                    <a class="ghost-btn" href="/noticias" data-route>Conoce más <span>→</span></a>
                </article>
                <img class="rounded-photo" src="https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=900&q=80" alt="Arquitectura ciudadana">
            </div>
            <x-stats />
            <section class="section">
                <h2>Galería de Manta</h2>
                <div class="gallery">
                    @forelse ($galleryAssets as $asset)
                        <img src="{{ $asset->url }}" alt="{{ $asset->alt ?? $asset->title }}">
                    @empty
                        <article class="manta-card p-6 text-slate-500">No hay fotos publicadas en la galería.</article>
                    @endforelse
                </div>
            </section>
        </section>

        <section class="page-view" data-page="services">
            <x-inner-hero title="Servicios" crumb="Inicio / Servicios" text="Encuentra todos los servicios que la Alcaldía de Manta tiene para ti. Accede de manera rápida y sencilla a trámites y consultas." image="https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1800&q=85" />
            @php
                $servicePageTitle = match ($serviceSearch ?? '') {
                    'pagos' => 'Pagos en línea',
                    'rtv' => 'Revisión Técnica Vehicular RTV',
                    'terminal' => 'Terminal Terrestre',
                    default => 'Servicios disponibles',
                };
            @endphp
            <form class="service-search" action="{{ route('services') }}" method="GET">
                <input name="q" type="search" value="{{ $serviceSearch ?? '' }}" placeholder="Buscar servicio..." data-service-search>
                <button class="icon-search" aria-label="Buscar" type="submit"><svg viewBox="0 0 24 24"><path d="m21 21-4.3-4.3m2.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg></button>
            </form>
            <div class="service-heading">
                <span>{{ filled($serviceSearch ?? '') ? 'Módulo ciudadano' : 'Directorio ciudadano' }}</span>
                <h2>{{ $servicePageTitle }}</h2>
            </div>
            <div class="tabs" data-tabs>
                <button class="active" data-filter="all">Todos</button>
                @foreach ($serviceCategories as $category)
                    <button data-filter="{{ $category }}">{{ ucfirst($category) }}</button>
                @endforeach
            </div>
            <div class="service-grid">
                @forelse ($services as $service)
                    @php
                        $serviceCategory = $service->metadataValue('category', 'general');
                        $serviceIcon = $iconMap[$serviceCategory] ?? $service->metadataValue('icon', 'doc');
                    @endphp
                    <article class="service-card" data-service-card data-category="{{ $serviceCategory }}">
                        <x-icon :name="$serviceIcon" />
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->content }}</p>
                        @php $serviceUrl = $service->actionUrl(); @endphp
                        <a href="{{ $serviceUrl }}" {!! $externalAttrs($serviceUrl) !!}>Acceder <span>→</span></a>
                    </article>
                @empty
                    <article class="manta-card p-6 text-slate-500">No hay servicios publicados.</article>
                @endforelse
            </div>
            <aside class="help-band"><strong>¿No encuentras lo que buscas?</strong><span>Contáctanos y te ayudaremos a resolver tus dudas.</span><a href="/contacto" data-route>Contáctanos <span>→</span></a></aside>
        </section>

        <section class="page-view" data-page="news">
            <x-inner-hero title="Noticias" crumb="Inicio / Noticias" text="Entérate de las últimas noticias, obras y actividades que impulsa la Alcaldía de Manta para el desarrollo de la ciudad." image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85" />
            <div class="tabs news-tabs">
                <a class="{{ $newsCategory === 'todas' ? 'active' : '' }}" href="{{ route('news', ['category' => 'todas']) }}">Todas</a>
                @foreach ($newsCategories as $category)
                    <a class="{{ $newsCategory === $category ? 'active' : '' }}" href="{{ route('news', ['category' => $category]) }}">{{ ucfirst($category) }}</a>
                @endforeach
            </div>
            <div class="post-grid">
                @forelse ($newsItems as $post)
                    <article class="post-card">
                        <img src="{{ $post->assetUrl('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $post->title }}">
                        <div><h3>{{ $post->title }}</h3><span>{{ $post->displayDate() }}</span><a href="{{ route('public.content.show', $post) }}">Leer más →</a></div>
                    </article>
                @empty
                    <article class="manta-card p-8 text-center text-slate-500">No hay noticias publicadas en esta categoría.</article>
                @endforelse
            </div>
            <div class="cms-pagination">
                {{ $newsItems->links() }}
            </div>
            <aside class="help-band pale"><strong>¿Necesitas ayuda?</strong><span>Contáctanos para brindarte asistencia en tus trámites.</span><a href="/contacto" data-route>Contáctanos <span>→</span></a></aside>
        </section>

        <section class="page-view" data-page="accountability">
            <div class="account-hero">
                <div class="hero-bg" style="--image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=85');"></div>
                <div class="hero-content">
                    <h1>Rendición de<br> cuentas</h1>
                    <p>Transparencia en cada acción, confianza en cada compromiso con nuestra ciudad.</p>
                    <span class="breadcrumb">Inicio / Transparencia / Rendición de Cuentas</span>
                </div>
                <aside class="floating-help"><x-icon name="doc" /><strong>¿Necesitas ayuda?</strong><p>Contáctanos si tienes dudas sobre la información publicada.</p><a class="primary-btn" href="/contacto" data-route>Contáctanos <span>→</span></a></aside>
            </div>
            <div class="account-content">
                <aside class="year-card">
                    <h2>Años</h2>
                    @forelse ($accountYears as $year)
                        <a class="{{ (int) $year === (int) $accountYear ? 'active' : '' }}" href="{{ route('accountability', ['year' => $year]) }}">{{ $year }} <span>›</span></a>
                    @empty
                        <p class="text-sm text-slate-500">No hay años publicados.</p>
                    @endforelse
                </aside>
                <section class="accordion" data-accordion>
                    @forelse ($accountPhases as $phase => $documents)
                        <article class="{{ $loop->first ? 'open' : '' }}">
                            <button type="button"><span>{{ $phase }}</span><i>{{ $loop->first ? '⌃' : '⌄' }}</i></button>
                            <div class="accordion-body">
                                @foreach ($documents as $doc)
                                    <a href="{{ $doc->metadataValue('asset') ? $doc->assetUrl() : route('public.content.show', $doc) }}" @if($doc->metadataValue('asset')) target="_blank" rel="noopener" @endif>
                                        <x-icon name="doc" /><span>{{ $loop->iteration }}. {{ $doc->title }}</span><b>{{ strtoupper($doc->metadataValue('type', $doc->module === 'pdfs' ? 'PDF' : 'WEB')) }}</b><em>↓</em>
                                    </a>
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <article class="open">
                            <button type="button"><span>Sin documentos publicados</span><i>⌃</i></button>
                            <div class="accordion-body"><p class="p-5 text-slate-500">No hay documentos para el año seleccionado.</p></div>
                        </article>
                    @endforelse
                </section>
            </div>
            <aside class="help-band pale"><x-icon name="file" /><span>Consulta y descarga los informes completos de rendición de cuentas de años anteriores.</span><a href="{{ route('accountability') }}">Ver informes históricos <span>→</span></a></aside>
        </section>

        <section class="page-view" data-page="lotaip">
            <div class="account-hero">
                <div class="hero-bg" style="--image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=85');"></div>
                <div class="hero-content">
                    <h1>LOTAIP</h1>
                    <p>Accede a la información pública institucional organizada por año y categoría.</p>
                    <span class="breadcrumb">Inicio / Transparencia / LOTAIP</span>
                </div>
                <aside class="floating-help"><x-icon name="doc" /><strong>¿Necesitas ayuda?</strong><p>Contáctanos si tienes dudas sobre la información publicada.</p><a class="primary-btn" href="/contacto" data-route>Contáctanos <span>→</span></a></aside>
            </div>
            <div class="account-content">
                <aside class="year-card">
                    <h2>Años</h2>
                    @forelse ($lotaipYears as $year)
                        <a class="{{ (int) $year === (int) $lotaipYear ? 'active' : '' }}" href="{{ route('lotaip', ['year' => $year]) }}">{{ $year }} <span>›</span></a>
                    @empty
                        <p class="text-sm text-slate-500">No hay años publicados.</p>
                    @endforelse
                </aside>
                <section class="accordion" data-accordion>
                    @forelse ($lotaipPhases as $phase => $documents)
                        <article class="{{ $loop->first ? 'open' : '' }}">
                            <button type="button"><span>{{ $phase }}</span><i>{{ $loop->first ? '⌃' : '⌄' }}</i></button>
                            <div class="accordion-body">
                                @foreach ($documents as $doc)
                                    <a href="{{ $doc->metadataValue('asset') ? $doc->assetUrl() : route('public.content.show', $doc) }}" @if($doc->metadataValue('asset')) target="_blank" rel="noopener" @endif>
                                        <x-icon name="doc" /><span>{{ $loop->iteration }}. {{ $doc->title }}</span><b>{{ strtoupper($doc->metadataValue('type', 'PDF')) }}</b><em>↓</em>
                                    </a>
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <article class="open">
                            <button type="button"><span>Sin documentos publicados</span><i>⌃</i></button>
                            <div class="accordion-body"><p class="p-5 text-slate-500">No hay documentos LOTAIP para el año seleccionado.</p></div>
                        </article>
                    @endforelse
                </section>
            </div>
            <aside class="help-band pale"><x-icon name="file" /><span>Consulta y descarga los documentos LOTAIP publicados por la institución.</span><a href="{{ route('lotaip') }}">Ver documentos LOTAIP <span>→</span></a></aside>
        </section>

        <section class="page-view" data-page="contact">
            <x-inner-hero title="Contacto" crumb="Inicio / Contacto" text="Estamos aquí para ayudarte. Contáctanos por cualquier medio o visítanos en nuestras oficinas." image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=85" />
            <div class="contact-layout">
                <aside class="contact-card">
                    <p><x-icon name="pin" /><span><strong>Dirección</strong>Vía Puerto - Aeropuerto<br>Sector El Palmar</span></p>
                    <p><x-icon name="mail" /><span><strong>Correo Electrónico</strong>denuncias@movilidadmanta.gob.ec</span></p>
                    <p><x-icon name="clock" /><span><strong>Horario de Atención</strong>Lunes a Viernes: 08h00 - 17h00</span></p>
                    <strong>Síguenos en nuestras redes</strong>
                    <div class="social-inline">
                        @foreach ($socialLinks as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">{{ $social['icon'] }}</a>
                        @endforeach
                    </div>
                </aside>
                <form class="contact-form" data-contact-form>
                    <h2>Envíanos un mensaje</h2>
                    <input required placeholder="Nombre completo">
                    <input required type="email" placeholder="Correo electrónico">
                    <input required placeholder="Asunto">
                    <textarea required rows="6" placeholder="Mensaje"></textarea>
                    <button class="primary-btn" type="submit">Enviar mensaje <span>→</span></button>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div><a class="brand" href="/" data-route><img src="/assets/brand/logo.png" alt="Manta Intervención"></a><p>Construimos juntos una ciudad moderna, sostenible y con oportunidades para todos.</p><div class="social-inline">@foreach ($socialLinks as $social)<a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">{{ $social['icon'] }}</a>@endforeach</div></div>
            <div><h3>Enlaces rápidos</h3><a href="/" data-route>Inicio</a><a href="/la-ciudad" data-route>La Ciudad</a><a href="/servicios" data-route>Servicios</a><a href="/noticias" data-route>Noticias</a></div>
            <div><h3>Transparencia</h3><a href="/transparencia/rendicion-de-cuentas" data-route>Rendición de Cuentas</a><a href="/transparencia" data-route>Normativas</a><a href="/transparencia/rendicion-de-cuentas" data-route>Convocatorias</a><a href="/transparencia/rendicion-de-cuentas" data-route>Resoluciones</a></div>
            <div><h3>Contacto</h3><p>denuncias@movilidadmanta.gob.ec<br>Vía Puerto - Aeropuerto<br>Sector El Palmar<br>Lunes a Viernes: 08h00 - 17h00</p></div>
            <form class="subscribe" data-subscribe-form><h3>Suscríbete a nuestro boletín</h3><p>Recibe las últimas noticias y novedades de nuestra ciudad.</p><input type="email" required placeholder="Tu correo electrónico"><button>Suscribirme <span>→</span></button></form>
        </div>
        <div class="footer-bottom"><span>© 2025 Alcaldía Ciudadana de Manta. Todos los derechos reservados.</span><span>Términos de uso | Política de privacidad | Mapa del sitio</span></div>
    </footer>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
    @if ($activeCall)
        <div id="announcement-modal" class="announcement-modal-shell hidden" aria-hidden="true" role="dialog" aria-modal="true">
            <article class="announcement-modal">
                <button type="button" class="announcement-close" data-announcement-hide aria-label="Cerrar convocatoria">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
                <div class="announcement-stamp">
                    <img src="/assets/brand/logo.png" alt="Manta Intervención">
                    <span>Convocatoria oficial</span>
                </div>
                <span class="announcement-kicker">{{ $activeCall->metadataValue('subtitle', 'Información ciudadana') }}</span>
                <h2>{{ $activeCall->title }}</h2>
                @if ($activeCall->metadataValue('start_date') || $activeCall->metadataValue('end_date') || $activeCall->metadataValue('date'))
                    <div class="announcement-dates">
                        @if ($activeCall->metadataValue('date'))<span>{{ $activeCall->metadataValue('date') }}</span>@endif
                        @if ($activeCall->metadataValue('start_date'))<span>Inicio: {{ $activeCall->metadataValue('start_date') }}</span>@endif
                        @if ($activeCall->metadataValue('end_date'))<span>Cierre: {{ $activeCall->metadataValue('end_date') }}</span>@endif
                    </div>
                @endif
                <div class="announcement-copy">{!! nl2br(e($activeCall->content)) !!}</div>
                @if ($activeCall->metadataValue('url'))
                    <a class="primary-btn" href="{{ $activeCall->metadataValue('url') }}" target="_blank" rel="noopener">Ver más <span>→</span></a>
                @endif
            </article>
        </div>
    @endif
</body>
</html>
