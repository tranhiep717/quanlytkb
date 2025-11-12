<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPasswordPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_weak_password_is_rejected()
    {
        $resp = $this->post('/register', [
            'name' => 'Test Weak',
            'email' => 'weak@example.com',
            'password' => '12345678', // weak (no mixed case, numbers only)
            'password_confirmation' => '12345678',
        ]);

        $resp->assertSessionHasErrors();
    }

    public function test_strong_password_is_accepted()
    {
        $resp = $this->post('/register', [
            'name' => 'Test Strong',
            'email' => 'strong@example.com',
            'password' => 'S3cure!Passw0rd#2025',
            'password_confirmation' => 'S3cure!Passw0rd#2025',
        ]);

        $resp->assertRedirect('/dashboard');
    }
}
