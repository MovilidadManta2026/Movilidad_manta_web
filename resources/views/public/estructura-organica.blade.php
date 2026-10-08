<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estructura Orgánica | Movilidad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fcff] text-slate-900">
    @include('public.partials.site-header', ['quickLinks' => $quickLinks])

    <main>
        <x-inner-hero
            title="Estructura Orgánica"
            crumb="Inicio / Servicios / Estructura Orgánica"
            text="Conoce la organización institucional de la Empresa Pública Municipal Movilidad de Manta EP."
            image="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1800&q=85"
        />

        <section class="structure-section">
            <div class="section-head">
                <span>Institucional</span>
                <h2>Estructura Orgánica</h2>
                <p>Empresa Pública Municipal “Movilidad de Manta - EP”</p>
            </div>

            <figure class="structure-viewer">
                <img src="/assets/media/estructura-organica.png" alt="Estructura orgánica de Movilidad de Manta EP">
            </figure>
        </section>
    </main>
</body>
</html>
