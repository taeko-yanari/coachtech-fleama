<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use App\Models\Like;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_partial_match_search_by_item_name()
    {
        $matchedItem = Item::factory()->create(['name' => 'ヴィンテージ革ジャケット']);
        ItemImage::factory()->create(['item_id' => $matchedItem->id]);

        $notMatchedItem = Item::factory()->create(['name' => 'スニーカー']);
        ItemImage::factory()->create(['item_id' => $notMatchedItem->id]);

        $response = $this->get('/search?keyword=革ジャケット');

        $response->assertSee('ヴィンテージ革ジャケット');
        $response->assertDontSee('スニーカー');
    }

    public function test_search_keyword_is_kept_in_mylist_tab()
    {
        $user = User::factory()->create();

        $matchedItem = Item::factory()->create(['name' => 'ヴィンテージ革ジャケット']);
        ItemImage::factory()->create(['item_id' => $matchedItem->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $matchedItem->id]);

        $notMatchedItem = Item::factory()->create(['name' => 'スニーカー']);
        ItemImage::factory()->create(['item_id' => $notMatchedItem->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $notMatchedItem->id]);

        $response = $this->actingAs($user)->get('/search?keyword=革ジャケット&tab=mylist');

        $response->assertSee('value="革ジャケット"', false);
        $response->assertSee('ヴィンテージ革ジャケット');
        $response->assertDontSee('スニーカー');
    }
}
