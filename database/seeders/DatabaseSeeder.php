<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CmsItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Administrador', 'email' => 'admin@manta.gob.ec', 'password' => 'AdminManta2026', 'role' => 'administrador'],
            ['name' => 'Planificación', 'email' => 'planificacion@manta.gob.ec', 'password' => 'PlanManta2026', 'role' => 'planificacion'],
            ['name' => 'Comunicación', 'email' => 'comunicacion@manta.gob.ec', 'password' => 'ComManta2026', 'role' => 'comunicacion'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [...$user, 'is_active' => true]
            );
        }

        $admin = User::where('role', 'administrador')->first();

        $items = [
            ['inicio', 'home.hero.title', 'Título principal del inicio', "Manta\nes progreso\nes tu ciudad", 1, []],
            ['inicio', 'home.hero.accent', 'Línea celeste del título', 'es progreso', 2, []],
            ['inicio', 'home.hero.subtitle', 'Texto descriptivo del inicio', 'Construimos juntos una ciudad moderna, sostenible y con oportunidades para todos.', 3, []],
            ['inicio', 'home.hero.button', 'Texto del botón principal', 'Conoce más', 4, []],
            ['inicio', 'shortcut.payments', 'Pagos', 'Consulta y paga tus impuestos, tasas y contribuciones.', 5, ['placement' => 'shortcut', 'category' => 'pagos', 'url' => '/servicios?q=pagos']],
            ['inicio', 'shortcut.permissions', 'Permisos', 'Solicita permisos de construcción, eventos y más.', 6, ['placement' => 'shortcut', 'category' => 'permisos', 'url' => '/servicios?q=permisos']],
            ['inicio', 'shortcut.transit', 'Tránsito', 'Consulta multas, revisión vehicular y más.', 7, ['placement' => 'shortcut', 'category' => 'transito', 'url' => '/servicios?q=transito']],
            ['inicio', 'shortcut.contact', 'Contáctanos', 'Comunícate con nuestro equipo de atención ciudadana.', 8, ['placement' => 'shortcut', 'category' => 'atencion', 'url' => '/contacto']],
            ['inicio', 'shortcut.bulletins', 'Boletines', 'Consulta los boletines y comunicados publicados.', 12, ['placement' => 'shortcut', 'category' => 'boletines', 'icon' => 'doc', 'url' => '/#boletines']],
            ['inicio', 'shortcut.resolutions', 'Resoluciones', 'Accede a resoluciones y documentos oficiales.', 13, ['placement' => 'shortcut', 'category' => 'resoluciones', 'icon' => 'file', 'url' => '/transparencia/rendicion-de-cuentas']],
            ['inicio', 'shortcut.calls', 'Convocatorias', 'Revisa convocatorias informativas y procesos vigentes.', 14, ['placement' => 'shortcut', 'category' => 'convocatorias', 'icon' => 'megaphone', 'url' => '/']],
            ['inicio', 'shortcut.rtv', 'RTV Matriculación', 'Información de revisión técnica vehicular y matriculación.', 15, ['placement' => 'shortcut', 'category' => 'rtv', 'icon' => 'tv', 'url' => 'https://wt.movilidadmanta.gob.ec/TRAMITESLINEA/login.aspx']],
            ['inicio', 'shortcut.terminal', 'Terminal Terrestre', 'Información y servicios del Terminal Terrestre de Manta.', 16, ['placement' => 'shortcut', 'category' => 'terminal', 'icon' => 'bus', 'url' => '/servicios?q=terminal']],
            ['inicio', 'shortcut.rtv.history', 'Historial y reporte de RTV', 'Consulta el historial y reporte de revisión técnica vehicular.', 17, ['placement' => 'shortcut', 'category' => 'rtv_historial', 'icon' => 'file', 'url' => 'https://consultartv.movilidadmanta.gob.ec/web/webpanel.consulta.aspx']],
            ['inicio', 'shortcut.sgi', 'Sistema de Gestión Interno SGI', 'Acceso al sistema de gestión interno institucional.', 18, ['placement' => 'shortcut', 'category' => 'sgi', 'icon' => 'chart', 'url' => 'https://sgi.movilidadmanta.gob.ec/login']],
            ['inicio', 'shortcut.intranet', 'Intranet', 'Ingreso a recursos internos y plataformas institucionales.', 19, ['placement' => 'shortcut', 'category' => 'intranet', 'icon' => 'globe', 'url' => 'https://intranet.movilidadmanta.gob.ec:8080/']],
            ['mision_vision', 'mission.statement', 'Misión', "Brindar servicios eficientes, innovadores y sustentables para la administración, regulación y control del tránsito, transporte terrestre, movilidad y gestión del Terminal Terrestre del Cantón Manta, mediante una gestión transparente, tecnológica y orientada al ciudadano, con talento humano de alto desempeño que contribuya a la seguridad vial, el ordenamiento territorial y la satisfacción de la ciudadanía y de los grupos de interés.", 9, ['subtitle' => 'Nuestro propósito institucional']],
            ['mision_vision', 'vision.statement', 'Visión', "Ser, al 2035, la empresa pública referente a nivel nacional en la gestión y regulación del tránsito, transporte terrestre y seguridad vial, mediante soluciones integrales, innovadoras y sustentables que garanticen una movilidad eficiente, segura y ordenada, contribuyendo al desarrollo territorial y al mejoramiento de la calidad de vida de la ciudadanía del Cantón Manta.", 10, ['subtitle' => 'Hacia dónde avanzamos']],
            ['mision_vision', 'values.statement', 'Valores', "El personal de todos los niveles administrativos y operativos del cuerpo social de la Empresa Pública Municipal “MOVILIDAD DE MANTA-EP”, se caracterizará por desarrollar sus actividades bajo los siguientes valores:\n✓ Apertura al cambio.\n✓ Compromiso.\n✓ Equidad.\n✓ Transparencia.\n✓ Orientación al servicio.\n✓ Capacidad y excelencia para la prestación de un servicio integral e integrado.\n✓ Vocación de trabajo en equipo.\n✓ Respeto y amabilidad en la relación con el cliente usuario.\n✓ Conciencia del empoderamiento de la responsabilidad ambiental.\n✓ Responsabilidad social.\n✓ Tolerancia.", 11, ['subtitle' => 'Valores institucionales']],
            ['mision_vision', 'objectives.statement', 'Objetivos', "El personal de todos los niveles administrativos y operativos del cuerpo social de la Empresa Pública Municipal “MOVILIDAD DE MANTA-EP”, se caracterizará por desarrollar sus actividades bajo los lineamientos enfocados en los siguientes objetivos:\n✓ Aumentar y mejorar los ingresos de “MOVILIDAD DE MANTA-EP”, mediante estrategias de comercialización, mercadeo y sistematización de trámites de atención ciudadana.\n✓ Brindar asesoría y atención a clientes y usuarios para mejorar los procesos operativos del Terminal Terrestre y gestionar adecuadamente los trámites de atención ciudadana.\n✓ Incrementar la eficiencia en las operaciones territoriales del Terminal Terrestre y de la gestión de tránsito, transporte terrestre y seguridad vial de Manta.\n✓ Establecer planes de comercialización y mercadotecnia para dinamizar el centro comercial, patio de comidas, islas, boleterías, encomiendas y locales comerciales del Terminal Terrestre.\n✓ Implementar mejoras en los servicios tecnológicos para automatizar la información y facilitar el conocimiento de frecuencias de unidades de transporte.\n✓ Potencializar el plan de señalización y semaforización en sectores rurales, urbanos, parroquias, comunidades y zona céntrica de Manta.\n✓ Generar eficiencia en los programas de matriculación vehicular, retención vehicular y seguridad vial.\n✓ Gestionar la operatividad de la Revisión Técnica Vehicular para contar con un sistema de movilidad eficiente, eficaz y seguro.", 12, ['subtitle' => 'Lineamientos estratégicos']],
            ['boletines', 'bulletin.mobility.schedule', 'Horario especial de atención', 'Informamos a la ciudadanía que los puntos de atención de Movilidad de Manta mantendrán una jornada especial para trámites vehiculares y consultas ciudadanas. Recomendamos revisar los canales oficiales antes de asistir.', 9, ['subtitle' => 'Comunicado sobre atención ciudadana', 'date' => '30 junio, 2026']],
            ['boletines', 'bulletin.safe.mobility', 'Movilidad segura para todos', 'La Dirección de Movilidad recuerda a conductores y peatones respetar las normas de tránsito, zonas escolares y pasos peatonales. La seguridad vial es una responsabilidad compartida.', 10, ['subtitle' => 'Campaña de seguridad vial', 'date' => '30 junio, 2026']],
            ['boletines', 'bulletin.digital.services', 'Trámites digitales disponibles', 'Los usuarios pueden acceder a servicios informativos y solicitudes desde el portal ciudadano. Esta medida busca reducir tiempos de atención y mejorar la experiencia de los contribuyentes.', 11, ['subtitle' => 'Servicios en línea para la ciudadanía', 'date' => '30 junio, 2026']],
            ['noticias', 'news.featured.1', 'Manta avanza con obras que transforman', 'Actualización destacada para la portada de noticias.', 10, ['category' => 'obras', 'date' => '20 mayo, 2024']],
            ['noticias', 'news.featured.2', 'Nuevo boletín de movilidad', 'Información publicada desde el CMS para validar la paginación y filtros.', 11, ['category' => 'comunidad', 'date' => '21 mayo, 2024']],
            ['transparencia', 'accountability.phase.1', 'Listado de Temas para Rendición de Cuentas', 'Documentos principales del proceso de rendición de cuentas.', 20, ['year' => '2025', 'phase' => 'Fase 1 - Planificación y preparación', 'type' => 'WEB']],
            ['lotaip', 'lotaip.2026.enero', 'LOTAIP enero 2026', 'Documento mensual de información pública correspondiente a enero.', 21, ['year' => '2026', 'phase' => 'Información mensual', 'type' => 'PDF']],
            ['lotaip', 'lotaip.2026.febrero', 'LOTAIP febrero 2026', 'Documento mensual de información pública correspondiente a febrero.', 22, ['year' => '2026', 'phase' => 'Información mensual', 'type' => 'PDF']],
            ['lotaip', 'lotaip.normativa', 'Normativa LOTAIP', 'Marco normativo y documentos referenciales de transparencia activa.', 23, ['year' => '2026', 'phase' => 'Normativa institucional', 'type' => 'WEB']],
            ['servicios', 'service.payments.fines', 'Pago de multas', 'Consulta y paga multas de tránsito emitidas en Manta de forma rápida y segura.', 30, ['category' => 'pagos', 'icon' => 'bag', 'url' => 'https://autority.app.link/entidad_manta_multas']],
            ['servicios', 'service.payments.rtv', 'Pago de RTV', 'Realiza el pago correspondiente a revisión técnica vehicular RTV Manta.', 31, ['category' => 'pagos', 'icon' => 'tv', 'url' => 'https://autority.app.link/entidad_manta_RTV']],
            ['foros', 'forum.citizen.feedback', 'Foro ciudadano', 'Espacio para recopilar aportes y solicitudes ciudadanas.', 40, ['category' => 'comunidad']],
        ];

        foreach ($items as [$module, $key, $title, $content, $sort, $metadata]) {
            CmsItem::updateOrCreate(
                ['key' => $key],
                [
                    'module' => $module,
                    'title' => $title,
                    'content' => $content,
                    'metadata' => $metadata,
                    'status' => 'published',
                    'sort_order' => $sort,
                    'published_at' => now(),
                    'created_by' => $admin?->id,
                    'updated_by' => $admin?->id,
                ]
            );
        }
    }
}
