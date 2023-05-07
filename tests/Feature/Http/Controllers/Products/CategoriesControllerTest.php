<?php

namespace Tests\Feature\Http\Controllers\Products;

use App\Models\Products\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category(): void
    {
        $data = Category::factory()->make()->toArray();
        $image = UploadedFile::fake()->create('cat.png', 100);
        Storage::fake('public');
        $data['path']   = $image;
        $response = $this->postJson('api/categories', $data, $this->header);
        $response->assertCreated()->json();
        $this->assertTrue($response['success']);
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_can_view_all_categories(): void
    {
        $categories = Category::factory()->count(2)->create();
        $response   = $this->getJson('api/categories', $this->header);
        $response->assertOk()->json();
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $response->assertJsonFragment([
            'name'  => $categories[0]->name,
        ]);
    }

    public function test_can_view_on_category(): void
    {
        $category   = Category::factory()->create();
        $response   = $this->getJson('api/categories/' . $category->id, $this->header);
        $response->assertOk()->json();
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $response->assertJsonFragment([
            'name'  => $category->name,
        ]);
    }

    public function test_can_update_category(): void
    {
        $category = Category::factory()->create();
        $image    = UploadedFile::fake()->create('cat.png');
        Storage::drive('public');
        $data     = [
            'name'          => fake()->name(),
            'description'   => fake()->sentence(),
            'path'          => $image
        ];
        $response = $this->patchJson('api/categories/' . $category->id, $data, $this->header);
        $response->assertOk()->json();
        $this->assertTrue($response['success']);
        $this->assertDatabaseHas('categories', [
            'name'      => $data['name'],
            'id'        => $category->id
        ]);
        $this->assertTrue(!Storage::drive('public')->exists($category->path));
    }

    public function test_can_delete_category(): void
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('api/categories/' . $category->id, [], $this->header);
        $response->assertNoContent();
        $this->assertDatabaseMissing('categories', [
            'id'            => $category->id,
            'deleted_at'    => NULL
        ]);
    }
}
