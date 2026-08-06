<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | Manta Intervención</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef9fd] text-slate-900">
    <main class="grid min-h-screen place-items-center px-4 py-10">
        <section class="manta-card w-full max-w-md p-8">
            <img class="mx-auto mb-8 h-auto w-56" src="/assets/brand/logo.png" alt="Manta Intervención">
            <h1 class="text-center text-2xl font-black uppercase text-[#064782]">Panel de administración</h1>
            <p class="mt-2 text-center text-sm text-slate-500">Ingresa para gestionar publicaciones, textos, fotos, PDFs y módulos.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="mt-8 grid gap-5" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Correo electrónico
                    <input class="rounded-lg border border-[#aee0f6] bg-white px-4 py-3 outline-none focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20" name="email" type="email" value="{{ old('email') }}" required autofocus>
                </label>
                <label class="grid gap-2 text-sm font-bold text-slate-700">
                    Contraseña
                    <input class="rounded-lg border border-[#aee0f6] bg-white px-4 py-3 outline-none focus:border-[#149BD7] focus:ring-4 focus:ring-[#149BD7]/20" name="password" type="password" required>
                </label>
                <button class="manta-action w-full" type="submit">Ingresar <span>→</span></button>
            </form>
        </section>
    </main>
</body>
</html>
