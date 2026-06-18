<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registered_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        ItemImage::factory()->create(['item_id' => $item->id]);

        $this->actingAs($user)
            ->withSession([
                'shipping_postal_code' => '123-4567',
                'shipping_address' => 'テスト県テスト市1-1-1',
                'shipping_building' => 'テストビル101',
                'last_item_id' => (string) $item->id,
        ])
        ->get('/purchase/' . $item->id)
        ->assertSee('123-4567')
        ->assertSee('テスト県テスト市1-1-1')
        ->assertSee('テストビル101');
    }

    public function test_purchase_is_linked_with_shipping_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'selling']);
        ItemImage::factory()->create(['item_id' => $item->id]);

        $payload = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_' . uniqid(),
                    'object' => 'checkout.session',
                    'payment_intent' => 'pi_test_' . uniqid(),
                    'metadata' => [
                        'item_id' => (string) $item->id,
                        'user_id' => (string) $user->id,
                        'payment_method' => 'カード支払い',
                        'shipping_postal_code' => '123-4567',
                        'shipping_address' => 'テスト県テスト市1-1-1',
                        'shipping_building' => 'テストビル101',
                        'price' => (string) $item->price,
                    ],
                ],
            ],
        ];

        $payloadJson = json_encode($payload);
        $secret = config('stripe.webhook_secret');
        $timestamp = time();
        $signedPayload = $timestamp . '.' . $payloadJson;
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        $sigHeader = 't=' . $timestamp . ',v1=' . $signature;

        $this->call('POST', '/webhook/stripe', [], [], [], [
            'HTTP_Stripe-Signature' => $sigHeader,
            'CONTENT_TYPE' => 'application/json',
        ], $payloadJson);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => 'テスト県テスト市1-1-1',
            'shipping_building' => 'テストビル101',
        ]);
    }
}
