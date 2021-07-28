<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Token $token;

    /**
     * Setup some defaults, bad data and a user
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = Token::factory()->create();
    }

    /**
     * Test the dashboard is redirected for guests
     */
    public function test_dashboard_is_redirected_for_guests()
    {
        $this->get(route('dashboard'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the dashboard can be displayed for logged in user
     */
    public function test_dashboard_screen_can_be_rendered_for_users()
    {
        $this->actingAs($this->user)->get(route('dashboard'))
            ->assertStatus(200);
    }

    /**
     * Test the dashboard displays tokens
     */
    public function test_dashboard_displays_tokens()
    {
        $this->actingAs($this->user)->get(route('dashboard'))
            ->assertSee($this->token->symbol)
            ->assertSee($this->token->name)
            ->assertSee(route('token.show', $this->token->id));   
    }

    /**
     * Test the dashboard contains the add token button
     */
    public function test_dashboard_displays_addtoken_link()
    {
        $this->actingAs($this->user)->get(route('dashboard'))
            ->assertSee(route('token.create'));
    }

    /**
     * Test the dashboard contains the log out button
     */
    public function test_dashboard_displays_logout_link()
    {
        $this->actingAs($this->user)->get(route('dashboard'))
            ->assertSee(route('logout'));
    }

}
