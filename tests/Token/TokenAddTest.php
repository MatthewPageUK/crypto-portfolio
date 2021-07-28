<?php

namespace Tests\Token;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenAddTest extends TestCase
{
    use RefreshDatabase;

    private String $table;
    private User $user;
    private Array $good;
    private Array $bad;

    /**
     * Setup some defaults, bad data and a user
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->table = (new Token())->getTable();
        $this->user = User::factory()->create();
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
                'short' => 'b', 
                'long' => str_repeat('a', 101), 
                'symbols' => 'b$a£d',
            ],
        ];
    }

    /**
     * Test the token create form is redirected for guests
     */
    public function test_token_create_form_is_redirected_to_login_for_guests()
    {
        $this->get(route('token.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the token create form is rendered for users
     */
    public function test_token_create_form_is_rendered_for_users()
    {
        $this->actingAs($this->user)->get(route('token.create'))
            ->assertStatus(200);
    }

    /**
     * Test the token create form has correct fields
     */
    public function test_token_create_form_has_correct_fields()
    {
        $this->actingAs($this->user)->get(route('token.create'))
            ->assertSee('name="symbol"', false)
            ->assertSee('name="name"', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('token.store'));
    }

    /**
     * Test the token can be stored with valid data
     */
    public function test_token_can_be_stored_with_valid_data()
    {
        $this->actingAs($this->user)->post(route('token.store', $this->good));
        $this->assertDatabaseHas($this->table, $this->good);
    }

    /**
     * Test the token can not be stored with invalid symbol
     */
    public function test_token_can_not_be_stored_with_invalid_symbol()
    {
        foreach($this->bad['symbol'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('token.store', ['symbol' => $value, 'name' => $key]));
        }
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test the token can not stored with invalid name
     */
    public function test_token_can_not_be_stored_with_invalid_name()
    {
        foreach($this->bad['name'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('token.store', ['symbol' => $key, 'name' => $value]));
        }
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test duplicate token symbols or names can not be stored
     */
    public function test_duplicate_token_can_not_be_stored()
    {
        $this->actingAs($this->user)->post(route('token.store', $this->good));
        $this->actingAs($this->user)->post(route('token.store', ['symbol' => $this->good['symbol'], 'name' => 'Duplicate symbol']));
        $this->actingAs($this->user)->post(route('token.store', ['symbol' => 'BAD', 'name' => $this->good['name']]));
        $this->assertDatabaseCount($this->table, 1);
    }

    /**
     * Test duplicate but deleted token symbols can be stored (soft delete)
     */
    public function test_duplicate_but_deleted_token_can_be_stored()
    {
        Token::factory()->create($this->good)->delete();
        $this->actingAs($this->user)->post(route('token.store', $this->good));
        $this->assertDatabaseCount($this->table, 2);
    }

}
