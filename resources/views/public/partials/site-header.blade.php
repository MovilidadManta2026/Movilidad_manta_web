@php
    $externalAttrs = fn (?string $url) => str_starts_with($url ?? '', 'http') ? 'target="_blank" rel="noopener noreferrer"' : '';
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
        <a href="/" data-route>Inicio</a>
        <a href="/mision-vision" data-route>Misión y Visión</a>
        <div class="nav-dropdown">
            <button type="button" class="nav-dropdown-trigger active">Servicios</button>
            <div class="nav-dropdown-menu nav-dropdown-menu-wide">
                <a href="/servicios" data-route>Todos los servicios</a>
                <a href="/estructura-organica" data-route>Estructura Orgánica</a>
                @foreach ($quickLinks as $link)
                    @php $linkUrl = $link->actionUrl(); @endphp
                    <a href="{{ $linkUrl }}" {!! $externalAttrs($linkUrl) !!} data-route>{{ $link->title }}</a>
                @endforeach
            </div>
        </div>
        <a href="/noticias" data-route>Noticias</a>
        <div class="nav-dropdown">
            <button type="button" class="nav-dropdown-trigger">Transparencia</button>
            <div class="nav-dropdown-menu">
                <a href="/transparencia/lotaip" data-route>LOTAIP</a>
                <a href="/transparencia/rendicion-de-cuentas" data-route>Rendición de Cuentas</a>
            </div>
        </div>
        <a href="/contacto" data-route>Contacto</a>
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
            <a class="block rounded-lg px-4 py-3 hover:bg-[#eef9fd] hover:text-[#064782]" href="/estructura-organica" data-route>Estructura Orgánica</a>
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
                <label class="sr-only" for="global-search-public">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-[#149BD7]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21 21-4.3-4.3m2.3-5.2a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg>
                    </div>
                    <input id="global-search-public" name="q" class="block w-full rounded-lg border border-[#aee0f6] bg-[#eef9fd] p-4 pl-12 text-slate-900 outline-none focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20" placeholder="Escribe: pagos, permisos, certificados..." />
                </div>
                <button class="manta-action" type="submit">Ir a servicios <span>→</span></button>
            </form>
        </div>
    </div>
</div>
