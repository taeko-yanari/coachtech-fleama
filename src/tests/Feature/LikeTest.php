<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/like');

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect(route('items.show', $item->id));

        $followUp = $this->actingAs($user)->get('/item/' . $item->id);
        $followUp->assertSee('1');
    }

    public function test_liked_icon_changes_color()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->actingAs($user)->get('/item/' . $item->id);

        $response->assertSee('heart-pink.png');
    }

    public function test_can_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->actingAs($user)->delete('/item/' . $item->id . '/like');

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect(route('items.show', $item->id));
    }
}
