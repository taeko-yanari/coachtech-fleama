<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_required_user_information_is_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
        ]);

        $sellItem = Item::factory()->create(['user_id' => $user->id, 'name' => '出品商品テスト']);
        ItemImage::factory()->create(['item_id' => $sellItem->id]);

        $purchasedItem = Item::factory()->create(['name' => '購入商品テスト', 'status' => 'sold']);
        ItemImage::factory()->create(['item_id' => $purchasedItem->id]);
        \App\Models\Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('出品商品テスト');
        $response->assertSee('購入商品テスト');
    }

    public function test_edit_form_shows_current_values_as_defaults()
    {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト市1-1-1',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('value="テスト太郎"', false);
        $response->assertSee('value="123-4567"', false);
        $response->assertSee('value="テスト県テスト市1-1-1"', false);
    }
}
