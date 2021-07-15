<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the edit token link is displayed on token info page
     */
    public function test_edit_token_link_is_rendered_on_token_info_page()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->get(route('token.show', $token->id));
        $response->assertSee(route('token.edit', $token->id));
    }

    /**
     * Test the token edit page is redirected for guests
     */
    public function test_token_edit_page_is_redirected_for_guests()
    {
        $token = CryptoToken::factory()->create();

        $response = $this->get(route('token.edit', $token->id));
        $response->assertStatus(302);
    }

    /**
     * Test the token edit page is rendered for users
     */
    public function test_token_edit_page_is_rendered_for_users()
    {
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('token.edit', $token->id));
        $response->assertStatus(200);
    }

    /**
     * Test the token edit page has correct fields
     */
    public function test_token_edit_page_has_correct_fields()
    {
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('token.edit', $token->id));
        $response->assertSee('name="symbol"', false)
            ->assertSee('name="name"', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('token.update', $token->id));
    }

    /**
     * Test the token can be updated with valid data
     */
    public function test_token_can_be_updated_with_valid_data()
    {
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'GOOD', 'name' => 'Good token']));
        $this->assertDatabaseHas('crypto_tokens', ['symbol' => 'GOOD', 'name' => 'Good token']);
    }

    /**
     * Test the token can not be updated with invalid symbol
     */
    public function test_token_can_not_be_updated_with_invalid_symbol()
    {
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'b a d', 'name' => 'Bad token symbol']));
        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'badbadbadbadbadbadbadbadbadbadbadb', 'name' => 'Bad token long']));
        $this->assertDatabaseMissing('crypto_tokens', ['symbol' => 'b a d'])->assertDatabaseMissing('crypto_tokens', ['symbol' => 'badbadbadbadbadbadbadbadbadbadbadb']);
    }

    /**
     * Test the token can not updated with invalid name
     */
    public function test_token_can_not_be_updated_with_invalid_name()
    {
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();
        $badString = "badbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbad";

        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => $badString]));
        $this->assertDatabaseMissing('crypto_tokens', ['name' => $badString]);
    }

    /**
     * Test duplicate token symbols can not be updated
     */
    public function test_duplicate_token_can_not_be_updated()
    {
        CryptoToken::create([
            'symbol' => 'ABC',
            'name' => 'Original ABC token',
        ]);
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => 'Second ABC token']));
        $this->assertDatabaseMissing('crypto_tokens', ['name' => 'Second ABC token']);
    }

    /**
     * Test duplicate but deleted token symbols can be updated
     */
    public function test_duplicate_but_deleted_token_can_be_updated()
    {
        CryptoToken::create([
            'symbol' => 'ABC',
            'name' => 'Original ABC token',
        ])->delete();
        $token = CryptoToken::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('token.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => 'A new ABC token']));
        $this->assertDatabaseHas('crypto_tokens', ['symbol' => 'ABC', 'name' => 'A new ABC token']);
    }

}
