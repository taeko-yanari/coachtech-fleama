<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Like;

class MylistTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_only_liked_items_are_displayed_in_mylist()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $likedItem->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $likedItem->id]);

        $notLikedItem = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $notLikedItem->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee($likedItem->name);
    }

    public function test_sold_item_is_displayed_as_sold_in_mylist()
    {
        $user = User::factory()->create();

        $soldItem = Item::factory()->create(['status' => 'sold']);
        ItemImage::factory()->create(['item_id' => $soldItem->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $soldItem->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('display:block', false);
    }

    public function test_mylist_items_is_empty_when_not_authenticated()
    {
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);
        Like::factory()->create(['item_id' => $item->id]);
        
        $response = $this->get('/');
        
        $response->assertViewHas('mylistItems', function ($mylistItems) {
            return $mylistItems->isEmpty();
        });
    }
}
