<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_name_required_validation_message()
{
    $data = [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/register', $data);

    $response->assertInvalid(['name' => 'お名前を入力してください']);
}

public function test_email_required_validation_message()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $data);

        $response->assertInvalid(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_required_validation_message()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->post('/register', $data);

        $response->assertInvalid(['password' => 'パスワードを入力してください']);
    }

    public function test_password_minimum_length_validation_message()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass12',
            'password_confirmation' => 'pass12',
        ];

        $response = $this->post('/register', $data);

        $response->assertInvalid(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_password_confirmation_mismatch_validation_message()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpass',
        ];

        $response = $this->post('/register', $data);

        $response->assertInvalid(['password_confirmation' => 'パスワードと一致しません']);
    }

    public function test_register_success_redirects_to_verification_notice()
    {
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $data);

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('mypage.edit'));
    }
}
