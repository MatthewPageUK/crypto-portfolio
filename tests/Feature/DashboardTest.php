<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

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
     * Test the dashboard contains the add token button
     */
    public function test_dashboard_displays_addtoken_link()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertSee(route('token.create'));
    }

    /**
     * Test the dashboard contains the log out button
     */
    public function test_dashboard_displays_logout_link()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertSee(route('logout'));
    }

}
