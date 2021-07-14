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
    public function test_welcome_screen_can_be_rendered_guest()
    {
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);
    }

    /**
     * Test the welcome page shows a log in link
     */
    public function test_welcome_screen_displays_login()
    {
        $response = $this->get(route('welcome'));
        $response->assertSee('login');
    }

    /**
     * Test the welcome page is redirected for authenticated users
     */
    public function test_welcome_screen_is_redirected_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('welcome'));
        $response->assertStatus(302);
    }

}
