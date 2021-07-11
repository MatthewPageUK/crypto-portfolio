<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test we can save the data to the database table.
     *
     * @return void
     */
    public function test_create_cryptotoken_data()
    {
        $tokens = CryptoToken::factory(5)->create();
        $this->assertDatabaseCount('crypto_tokens', 5);
    }

    /**
     * Test the dashboard is redirected for guests
     */
    public function test_dashboard_is_redirected_for_guests()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }

    /**
     * Test the dashboard can be displayed for logged in user
     */
    public function test_dashboard_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    /**
     * Test the dashboard displays tokens
     */
    public function test_dashboard_displays_tokens()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create(['name' => 'DummyCoin']);
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertSee('DummyCoin');   
    }

    /** 
     * Test the token info page can be displayed
     */
    public function test_token_info_page_can_rendered()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $response = $this->actingAs($user)->get(route('token', ['token' => $token->id]));
        $response->assertSee($token->symbol);
    }
}
