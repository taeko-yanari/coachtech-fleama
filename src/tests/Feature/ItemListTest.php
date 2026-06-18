<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;

class ItemListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_all_items_are_displayed()
    {
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    public function test_sold_item_is_displayed_as_sold()
    {
        $item = Item::factory()->create(['status' => 'sold']);
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->get('/');

        $response->assertSee('display:block', false);
    }

    public function test_own_items_are_not_displayed_in_list()
    {
        $user = User::factory()->create();
        $ownItem = Item::factory()->create(['user_id' => $user->id, 'name' => '自分専用テスト商品ABC123']);
        ItemImage::factory()->create(['item_id' => $ownItem->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee($ownItem->name);
    }
}
