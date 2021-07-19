<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the welcome page is shown for guests
     */
    public function test_welcome_screen_can_be_rendered_for_guests()
    {
        $this->get(route('welcome'))
            ->assertStatus(200);
    }

    /**
     * Test the welcome page shows a log in link
     */
    public function test_welcome_screen_displays_login_link()
    {
        $this->get(route('welcome'))
            ->assertSee(route('login'));
    }

    /**
     * Test the welcome page shows a register link
     */
    public function test_welcome_screen_displays_register_link()
    {
        $this->get(route('welcome'))
            ->assertSee(route('register'));
    }

    /**
     * Test the welcome page is redirected to dashboard for authenticated users
     */
    public function test_welcome_screen_is_redirected_to_dashboard_for_users()
    {
        $this->actingAs( User::factory()->create() )->get(route('welcome'))
            ->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }
}
