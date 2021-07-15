<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the token create page is redirected for guests
     */
    public function test_token_create_page_is_redirected_for_guests()
    {
        $response = $this->get(route('token.create'));
        $response->assertStatus(302);
    }

    /**
     * Test the token create page is rendered for users
     */
    public function test_token_create_page_is_rendered_for_users()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('token.create'));
        $response->assertStatus(200);
    }

    /**
     * Test the token create page has correct fields
     */
    public function test_token_create_page_has_correct_fields()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('token.create'));
        $response->assertSee('name="symbol"', false)
            ->assertSee('name="name"', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('token.store'));
    }

    /**
     * Test the token can be stored with valid data
     */
    public function test_token_can_be_stored_with_valid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'GOOD', 'name' => 'Good token']));
        $this->assertDatabaseCount('crypto_tokens', 1);
    }

    /**
     * Test the token can not be stored with invalid symbol
     */
    public function test_token_can_not_be_stored_with_invalid_symbol()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'b a d', 'name' => 'Bad token']));
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'aaaaabbbbbdddddeeeefffffggggg', 'name' => 'Bad token long']));
        $this->assertDatabaseCount('crypto_tokens', 0);
    }

    /**
     * Test the token can not stored with invalid name
     */
    public function test_token_can_not_be_stored_with_invalid_name()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'ABC', 'name' => 'aaaaaaaaaabbbbbbbbbbaaaaaaaaaabbbbbbbbbbaaaaaaaaaabbbbbbbbbbaaaaaaaaaabbbbbbbbbbaaaaaaaaaabbbbbbbbbbz']));
        $this->assertDatabaseCount('crypto_tokens', 0);
    }

    /**
     * Test duplicate token symbols can not be stored
     */
    public function test_duplicate_token_can_not_be_stored()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'ABC', 'name' => 'First token ABC']));
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'ABC', 'name' => 'Second token ABC']));
        $repsonse = $this->actingAs($user)->post(route('token.store', ['symbol' => 'DEF', 'name' => 'First token ABC']));
        $this->assertDatabaseCount('crypto_tokens', 1);
    }

    /**
     * Test duplicate but deleted token symbols can be stored
     */
    public function test_duplicate_but_deleted_token_can_be_stored()
    {
        $user = User::factory()->create();
        CryptoToken::create(['symbol' => 'DEL', 'name' => 'Deleted token DEL'])->delete();
        $response = $this->actingAs($user)->post(route('token.store', ['symbol' => 'DEL', 'name' => 'Active token DEL']));
        $this->assertDatabaseCount('crypto_tokens', 2);
    }

}
