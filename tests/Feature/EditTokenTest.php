<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTokenTest extends TestCase
{
    use RefreshDatabase;

    private String $table;
    private User $user;
    private CryptoToken $token;
    private Array $good;
    private Array $bad;

    /**
     * Setup some defaults and a new user
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->table = (new CryptoToken())->getTable();
        $this->user = User::factory()->create();
        $this->token = CryptoToken::factory()->create();
        $this->good = ['symbol' => 'GOOD', 'name' => 'Good token'];
        $this->bad = [
            'symbol' => [
                'empty' => '',
                'space' => 'b a d', 
                'long' => str_repeat('a', 26), 
                'symbols' => 'b$a£d',
            ],
            'name' => [
                'empty' => '',
                'short' => 'a', 
                'long' => str_repeat('a', 101), 
                'symbols' => 'b$a£d',
            ],
        ];
    }

    /**
     * Test the edit token link is displayed on token info page
     */
    public function test_edit_token_link_is_rendered_on_token_info_page()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee(route('token.edit', $this->token->id));
    }

    /**
     * Test the token edit page is redirected for guests
     */
    public function test_token_edit_page_is_redirected_for_guests()
    {
        $this->get(route('token.edit', $this->token->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the token edit page is rendered for users
     */
    public function test_token_edit_page_is_rendered_for_users()
    {
        $this->actingAs($this->user)->get(route('token.edit', $this->token->id))
            ->assertStatus(200);
    }

    /**
     * Test the token edit page has correct fields
     */
    public function test_token_edit_page_has_correct_fields()
    {
        $this->actingAs($this->user)->get(route('token.edit', $this->token->id))
            ->assertSee('name="symbol"', false)
            ->assertSee('name="name"', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('token.update', $this->token->id));
    }

    /**
     * Test the token can be updated with valid data
     */
    public function test_token_can_be_updated_with_valid_data()
    {
        $this->actingAs($this->user)->post(route('token.update', array_merge($this->good, ['token' => $this->token->id])));
        $this->assertDatabaseHas($this->table, $this->good);
    }

    /**
     * Test the token can not be updated with invalid symbol
     */
    public function test_token_can_not_be_updated_with_invalid_symbol()
    {
        foreach($this->bad['symbol'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('token.update', ['token' => $this->token->id, 'symbol' => $value, 'name' => $key]));
        }
        $this->assertDatabaseHas($this->table, ['symbol' => $this->token['symbol']]);
    }

    /**
     * Test the token can not updated with invalid name
     */
    public function test_token_can_not_be_updated_with_invalid_name()
    {
        foreach($this->bad['name'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('token.update', ['token' => $this->token->id, 'symbol' => $key, 'name' => $value]));
        }
        $this->assertDatabaseHas($this->table, ['name' => $this->token['name']]);
    }

    /**
     * Test duplicate token symbols can not be updated
     */
    public function test_duplicate_token_can_not_be_updated()
    {
        $token2 = CryptoToken::factory()->create();

        $this->actingAs($this->user)->post(route('token.update', ['token' => $token2->id, 'symbol' => $this->token->symbol, 'name' => 'Second token']));
        $this->assertDatabaseMissing($this->table, ['name' => 'Second token']);
    }
    
    /**
     * Test duplicate but deleted token symbols can be updated
     */
    public function test_duplicate_but_deleted_token_can_be_updated()
    {
        $symbol = $this->token->symbol;
        $this->token->delete();
        $newToken = CryptoToken::factory()->create();

        $this->actingAs($this->user)->post(route('token.update', ['token' => $newToken->id, 'symbol' => $symbol, 'name' => 'A new token']));
        $this->assertDatabaseHas($this->table, ['name' => 'A new token']);
    }

}
