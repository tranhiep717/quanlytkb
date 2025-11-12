<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class PasswordResetRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure sqlite in memory or file is migrated
        $this->artisan('migrate');
    }

    public function test_generic_message_returned_for_unknown_email()
    {
        $resp = $this->post('/forgot-password', ['email' => 'unknown@example.com']);
        $resp->assertSessionHas('status');
        $resp->assertRedirect();
    }

    public function test_reset_request_creates_token_for_existing_user()
    {
        $user = User::factory()->create(['email' => 'known@example.com']);
        $this->post('/forgot-password', ['email' => 'known@example.com'])->assertRedirect();

        $row = DB::table('password_reset_tokens')->where('email', 'known@example.com')->first();
        $this->assertNotNull($row);
        $this->assertNotEmpty($row->token);
    }
}
