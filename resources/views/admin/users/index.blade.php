@extends('admin.layout')

@section('title', 'Usuarios y roles')

@section('content')
    <section class="grid gap-6 xl:grid-cols-[420px_minmax(0,1fr)]">
        <aside class="manta-card h-max p-6">
            <h2 class="text-xl font-black uppercase text-[#064782]">Crear usuario</h2>
            <form class="mt-5 grid gap-4" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="name" placeholder="Nombre" required>
                <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="email" type="email" placeholder="Correo" required>
                <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="password" type="password" placeholder="Contraseña" required>
                <select class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <button class="manta-action" type="submit">Crear usuario</button>
            </form>
        </aside>

        <div class="grid gap-4">
            @foreach ($users as $user)
                <form class="manta-card grid gap-4 p-5 md:grid-cols-[1fr_1fr_190px_140px_auto]" method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="name" value="{{ $user->name }}" required>
                    <input class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="email" type="email" value="{{ $user->email }}" required>
                    <select class="rounded-lg border border-[#aee0f6] px-4 py-3 outline-none focus:border-[#149BD7]" name="role" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    <label class="inline-flex items-center gap-2 text-sm font-bold text-slate-600">
                        <input class="rounded border-[#aee0f6] text-[#149BD7]" name="is_active" type="checkbox" value="1" @checked($user->is_active)>
                        Activo
                    </label>
                    <div class="grid gap-2">
                        <input class="rounded-lg border border-[#aee0f6] px-4 py-3 text-sm outline-none focus:border-[#149BD7]" name="password" type="password" placeholder="Nueva clave">
                        <button class="manta-action !py-2" type="submit">Guardar</button>
                    </div>
                </form>
            @endforeach
        </div>
    </section>
@endsection
