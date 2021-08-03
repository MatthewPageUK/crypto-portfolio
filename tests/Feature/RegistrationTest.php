<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the registration screen can be rendered
     */
    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    /**
     * Test new user can register
     */
    public function test_new_users_can_register()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * Test only one user can be registered
     */
    public function test_only_one_user_can_register()
    {
        $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->post(route('register'), [
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseCount('Users', 1);
    }

    /**
     * Test a user can edit their profile
     */
    public function test_user_can_edit_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post( route('profile.update'), [
            'name' => 'Test User 9',
            'email' => 'test9@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas((new User())->getTable(), [
            'name' => 'Test User 9', 
            'email' => 'test9@example.com',
        ]);
    }    
}
