<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/en/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/en/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'customer_group' => 'retail',
            'phone' => '0123456789',
            'address' => '123 Main Street',
            'city' => 'Johor Bahru',
            'state' => 'Johor',
            'postcode' => '80000',
            'terms_consent' => '1',
        ]);

        $response->assertRedirect(route('otp.verify'));
        $this->assertEquals(session('otp_verify_user_id'), \App\Models\User::where('email', 'test@example.com')->first()->id);
    }
}
