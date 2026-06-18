<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ItemRegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_required_item_information_is_saved()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $tempFile = UploadedFile::fake()->image('sample.jpg');
        $tempPath = $tempFile->store('temp', 'public');

        $data = [
            'name' => 'ヴィンテージ革ジャケット',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'category_ids' => [$category1->id, $category2->id],
            'condition' => '良好',
            'price' => 12000,
        ];

        $response = $this->actingAs($user)
            ->withSession(['temp_image_path' => $tempPath])
            ->post('/sell/store', $data);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'ヴィンテージ革ジャケット',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト用の商品説明です',
            'condition' => '良好',
            'price' => 12000,
        ]);

        $item = \App\Models\Item::where('name', 'ヴィンテージ革ジャケット')->first();

        $this->assertDatabaseHas('item_categories', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);
        $this->assertDatabaseHas('item_categories', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);

        $response->assertRedirect(route('mypage.index'));
    }
}
