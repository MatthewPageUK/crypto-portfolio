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
     * Test the token info page can be displayed
     */
    public function test_token_info_page_can_rendered()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $response = $this->actingAs($user)->get(route('token.show', ['token' => $token->id]));
        $response->assertSee($token->symbol);
    }

}
