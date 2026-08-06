<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') | Manta Intervención</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef9fd] text-slate-900">
    <div class="min-h-screen lg:grid lg:grid-cols-[300px_minmax(0,1fr)]">
        <aside class="border-r border-[#cfeafa] bg-white p-5 shadow-sm lg:min-h-screen">
            <div class="flex items-center justify-between lg:block">
                <a href="{{ route('admin.dashboard') }}">
                    <img class="h-auto w-44" src="/assets/brand/logo.png" alt="Manta Intervención">
                </a>
                <a class="rounded-lg border border-[#aee0f6] px-3 py-2 text-sm font-bold text-[#064782] lg:hidden" href="{{ route('home') }}">Web</a>
            </div>

            <div class="mt-6 rounded-lg border border-[#cfeafa] bg-[#eef9fd] p-4 text-sm">
                <span class="text-xs font-black uppercase text-[#149BD7]">Sesión activa</span>
                <strong class="block text-[#064782]">{{ auth()->user()->name }}</strong>
                <span class="capitalize text-slate-500">{{ auth()->user()->role }} · expira en 10 min de inactividad</span>
            </div>

            <nav class="mt-8 grid gap-2 text-sm font-black uppercase">
                <span class="px-4 text-[11px] font-black uppercase tracking-wide text-slate-400">Principal</span>
                <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] {{ request()->routeIs('admin.dashboard') ? 'bg-[#149BD7] text-white' : 'text-slate-700' }}" href="{{ route('admin.dashboard') }}">Resumen</a>
                <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] {{ request()->routeIs('admin.content.index') ? 'bg-[#149BD7] text-white' : 'text-slate-700' }}" href="{{ route('admin.content.index') }}">Editar web</a>
                <span class="mt-3 px-4 text-[11px] font-black uppercase tracking-wide text-slate-400">Módulos</span>
                <div class="grid gap-1 border-l border-[#cfeafa] pl-3">
                    @foreach (\App\Http\Controllers\Admin\CmsItemController::MODULES as $moduleKey => $moduleLabel)
                        @if (auth()->user()->canManageModule($moduleKey))
                            <a class="rounded-lg px-3 py-2 text-xs hover:bg-[#eef9fd] {{ request()->routeIs('admin.modules.show') && request()->route('module') === $moduleKey ? 'bg-[#149BD7] text-white' : 'text-slate-600' }}" href="{{ route('admin.modules.show', $moduleKey) }}">{{ $moduleLabel }}</a>
                        @endif
                    @endforeach
                </div>
                <span class="mt-3 px-4 text-[11px] font-black uppercase tracking-wide text-slate-400">Herramientas</span>
                <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] {{ request()->routeIs('admin.media.*') ? 'bg-[#149BD7] text-white' : 'text-slate-700' }}" href="{{ route('admin.media.index') }}">Biblioteca de archivos</a>
                @if (auth()->user()->isAdmin())
                    <a class="rounded-lg px-4 py-3 hover:bg-[#eef9fd] {{ request()->routeIs('admin.users.*') ? 'bg-[#149BD7] text-white' : 'text-slate-700' }}" href="{{ route('admin.users.index') }}">Usuarios</a>
                @endif
                <a class="rounded-lg px-4 py-3 text-slate-700 hover:bg-[#eef9fd]" href="{{ route('home') }}">Ver sitio web</a>
            </nav>

            <form class="mt-8" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full rounded-lg border border-[#aee0f6] px-4 py-3 text-sm font-black uppercase text-[#064782] hover:bg-[#eef9fd]" type="submit">Cerrar sesión</button>
            </form>
        </aside>

        <main class="min-w-0 p-4 md:p-8">
            <header class="mb-8 flex flex-col gap-3 rounded-lg border border-[#cfeafa] bg-white p-5 shadow-xl shadow-[#064782]/10 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-black uppercase text-[#149BD7]">Sistema CMS</p>
                    <h1 class="text-3xl font-black uppercase text-[#064782]">@yield('title', 'Panel')</h1>
                </div>
                @yield('actions')
            </header>

            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <strong class="block">Revisa estos campos:</strong>
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
