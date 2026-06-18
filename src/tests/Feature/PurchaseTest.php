<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private function buildWebhookPayload($item, $user)
    {
        return [
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
    }

    private function postWebhook(array $payload)
    {
        $payloadJson = json_encode($payload);
        $secret = config('stripe.webhook_secret');
        $timestamp = time();

        $signedPayload = $timestamp . '.' . $payloadJson;
        $signature = hash_hmac('sha256', $signedPayload, $secret);
        $sigHeader = 't=' . $timestamp . ',v1=' . $signature;

        return $this->call('POST', '/webhook/stripe', [], [], [], [
            'HTTP_Stripe-Signature' => $sigHeader,
            'CONTENT_TYPE' => 'application/json',
        ], $payloadJson);
    }

    public function test_purchase_is_completed_via_webhook()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'selling']);
        ItemImage::factory()->create(['item_id' => $item->id]);

        $payload = $this->buildWebhookPayload($item, $user);
        $response = $this->postWebhook($payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_method' => 'カード支払い',
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold_in_list()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'selling']);
        ItemImage::factory()->create(['item_id' => $item->id]);

        $payload = $this->buildWebhookPayload($item, $user);
        $this->postWebhook($payload);

        $response = $this->get('/');

        $response->assertSee('display:block', false);
    }

    public function test_purchased_item_is_added_to_profile_purchase_list()
    {
        $user = User::factory()->create();
    $item = Item::factory()->create(['status' => 'selling', 'name' => '購入済みテスト商品']);
    ItemImage::factory()->create(['item_id' => $item->id]);

    $payload = $this->buildWebhookPayload($item, $user);
    $this->postWebhook($payload);

    $response = $this->actingAs($user)->get('/mypage');

    $response->assertSee('購入済みテスト商品');
    }
}
