<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authenticated_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'comment' => 'これはテストコメントです',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです',
        ]);

        $response->assertRedirect(route('items.show', $item->id));
    }

    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->post('/item/' . $item->id . '/comment', [
            'comment' => 'これはテストコメントです',
        ]);

        $this->assertDatabaseMissing('comments', [
            'comment' => 'これはテストコメントです',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_comment_required_validation_message()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'comment' => '',
        ]);

        $response->assertInvalid(['comment' => 'コメントを入力してください。']);
    }

    public function test_comment_max_length_validation_message()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comment', [
            'comment' => str_repeat('あ', 256),
        ]);

        $response->assertInvalid(['comment' => 'コメントは255文字以内で入力してください']);
    }
}
