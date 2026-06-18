<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_email_required_validation_message()
    {
        $data = [
            'email' => '',
            'password' => 'password123',
        ];

        $response = $this->post('/login', $data);

        $response->assertInvalid(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_required_validation_message()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => '',
        ];

        $response = $this->post('/login', $data);

        $response->assertInvalid(['password' => 'パスワードを入力してください']);
    }

    public function test_invalid_credentials_validation_message()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->post('/login', $data);

        $response->assertInvalid(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_login_success()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('/login', $data);

        $this->assertAuthenticated();
    }
}
