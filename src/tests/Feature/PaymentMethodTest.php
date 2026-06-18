<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_selected_payment_method_is_saved_to_session()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/purchase/save-payment-method', [
            'payment_method' => 'コンビニ支払い',
        ]);

        $response->assertJson(['status' => 'ok']);
        $response->assertSessionHas('payment_method', 'コンビニ支払い');
    }
}
