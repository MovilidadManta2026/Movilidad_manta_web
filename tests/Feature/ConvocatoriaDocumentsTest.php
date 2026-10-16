<?php

namespace Tests\Feature;

use App\Models\CmsItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConvocatoriaDocumentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_form_starts_with_one_blank_document(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'administrador']))
            ->get(route('admin.modules.show', 'convocatorias'))
            ->assertOk()
            ->assertSee('Agregar documento')
            ->assertSee('name="metadata[documents][0][title]"', false)
            ->assertDontSee('name="metadata[documents][1][title]"', false);
    }

    public function test_more_than_four_documents_can_be_uploaded_and_published(): void
    {
        Storage::fake('public');
        $documents = [];
        $uploads = [];
        foreach (range(0, 5) as $index) {
            $documents[$index] = ['title' => "Documento $index", 'subtitle' => "Detalle $index"];
            $uploads[$index] = UploadedFile::fake()->create("documento-$index.pdf", 10, 'application/pdf');
        }

        $this->actingAs(User::factory()->create(['role' => 'administrador']))
            ->post(route('admin.modules.store', 'convocatorias'), [
                'key' => '', 'title' => 'Convocatoria', 'status' => 'published', 'sort_order' => 0,
                'metadata' => ['documents' => $documents], 'convocatoria_documents' => $uploads,
            ])->assertSessionHasNoErrors()->assertRedirect();

        $item = CmsItem::firstOrFail();
        $this->assertCount(6, $item->metadata['documents']);
        foreach ($item->metadata['documents'] as $index => $document) {
            $this->assertSame("Documento $index", $document['title']);
            $this->assertSame("Detalle $index", $document['subtitle']);
            Storage::disk('public')->assertExists($document['path']);
        }
        $this->get(route('convocatorias.show', $item))->assertOk()->assertSee('Documento 5');
    }

    public function test_edit_preserves_existing_pdf_and_does_not_recreate_removed_or_empty_rows(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'administrador']);
        $item = CmsItem::create([
            'module' => 'convocatorias', 'key' => 'convocatoria', 'title' => 'Convocatoria',
            'status' => 'draft', 'sort_order' => 0,
            'metadata' => ['documents' => [
                ['title' => 'Anterior', 'subtitle' => '', 'path' => 'cms/anterior.pdf'],
                ['title' => 'Conservar', 'subtitle' => 'Detalle', 'path' => 'cms/conservar.pdf'],
            ]],
        ]);

        $this->actingAs($user)->put(route('admin.content.update', $item), [
            'module' => 'convocatorias', 'key' => 'convocatoria', 'title' => 'Convocatoria',
            'status' => 'draft', 'sort_order' => 0,
            'metadata' => ['documents' => [
                ['title' => 'Conservar', 'subtitle' => 'Actualizado', 'path' => 'cms/conservar.pdf'],
                ['title' => '', 'subtitle' => '', 'path' => ''],
            ]],
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame([
            ['title' => 'Conservar', 'subtitle' => 'Actualizado', 'path' => 'cms/conservar.pdf'],
        ], $item->fresh()->metadata['documents']);
    }
}
