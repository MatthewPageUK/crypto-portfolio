<?php

namespace Tests\Token;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenDeleteTest extends TestCase
{
    use RefreshDatabase;

    private CryptoToken $token;
    private String $table;
    private User $user;

    /**
     * Setup some defaults, bad data and a user
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->token = CryptoToken::factory()->create();
        $this->table = $this->token->getTable();
        $this->user = User::factory()->create();
    }

    /**
     * Test the delete token link is shown on token info page
     */
    public function test_delete_token_link_is_on_token_info_page()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee(route('token.delete', $this->token->id));
    }

    /**
     * Test the delete token link has a JS confirm
     */
    public function test_delete_token_link_has_js_confirm()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee('Delete this token and ALL transactions');
    }

    /**
     * Test the delete token page is redirected for guests
     */
    public function test_delete_token_page_is_redirected_for_guests()
    {
        $this->get(route('token.delete', $this->token->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the token is soft deleted
     */
    public function test_token_is_soft_deleted()
    {
        $this->actingAs($this->user)->get(route('token.delete', $this->token->id));
        $this->assertSoftDeleted($this->table, ['id' => $this->token->id]);
    }

    /**
     * Test the token is not hard deleted
     */
    public function test_token_is_not_hard_deleted()
    {
        $this->actingAs($this->user)->get(route('token.delete', $this->token->id));
        $this->assertDatabaseCount($this->table, 1);
    }

    /**
     * Test transactions are soft deleted on token delete
     */
    public function test_transactions_are_soft_deleted_on_token_delete()
    {
        $transaction = CryptoTransaction::factory()->for($this->token)->create();

        $this->actingAs($this->user)->get(route('token.delete', $this->token->id));
        $this->assertSoftDeleted($transaction->getTable(), ['id' => $transaction->id]);
    }

    /**
     * Test transactions are not hard deleted on token delete
     */
    public function test_transactions_are_not_hard_deleted_on_token_delete()
    {
        CryptoTransaction::factory()->for($this->token)->create();

        $this->actingAs($this->user)->get(route('token.delete', $this->token->id));
        $this->assertDatabaseCount($this->table, 1);
    }
}
