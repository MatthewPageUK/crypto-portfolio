<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
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
    public function test_create_token_factory()
    {
        CryptoToken::factory(5)->create();
        $this->assertDatabaseCount((new CryptoToken())->getTable(), 5);
    }

    /**
     * Test the token info page is redirected for guests
     */
    public function test_token_info_page_is_redirected_to_login_for_guests()
    {
        $token = CryptoToken::factory()->create();
        $this->get(route('token.show', ['token' => $token->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** 
     * Test the token info page can be displayed
     */
    public function test_token_info_page_can_rendered_for_user()
    {
        $token = CryptoToken::factory()->create();
        $this->actingAs(User::factory()->create())->get(route('token.show', ['token' => $token->id]))
            ->assertSee($token->symbol)
            ->assertSee($token->name);
    }

}
