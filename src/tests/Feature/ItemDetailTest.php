<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_all_required_information_is_displayed()
    {
        $item = Item::factory()->create([
            'name' => 'ヴィンテージ革ジャケット',
            'brand_name' => 'テストブランド',
            'price' => 12000,
            'description' => 'これはテスト用の商品説明です',
            'condition' => '良好',
        ]);
        ItemImage::factory()->create(['item_id' => $item->id]);

        $category = Category::factory()->create(['name' => 'メンズ']);
        $item->categories()->attach($category->id);

        $commenter = User::factory()->create(['name' => 'コメント太郎']);
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commenter->id,
            'comment' => 'これはテストコメントです',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('ヴィンテージ革ジャケット');
        $response->assertSee('テストブランド');
        $response->assertSee('12,000');
        $response->assertSee('これはテスト用の商品説明です');
        $response->assertSee('良好');
        $response->assertSee('メンズ');
        $response->assertSee('コメント太郎');
        $response->assertSee('これはテストコメントです');
    }

    public function test_multiple_selected_categories_are_displayed()
    {
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $category1 = Category::factory()->create(['name' => 'メンズ']);
        $category2 = Category::factory()->create(['name' => 'ジャケット']);
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('メンズ');
        $response->assertSee('ジャケット');
    }
}
