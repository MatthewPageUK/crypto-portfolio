<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTransactionTest extends TestCase
{
    use RefreshDatabase;

    private String $table;
    private User $user;
    private Token $token;
    private Transaction $transaction;
    private Array $good;

    /**
     * Setup some defaults, bad data and a user
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->table = (new Transaction())->getTable();
        $this->token = Token::factory()->create();
        $this->user = User::factory()->create();
        $this->good = [
            'token_id' => $this->token->id, 
            'time' => '2021-06-25T10:32:45',
            'quantity' => 100,
            'price' => 12,
            'type' => Transaction::BUY,
        ];

        $this->transaction = Transaction::factory()->for($this->token)->create($this->good);
    }

    /**
     * Test the delete transaction link is shown on token info page
     */
    public function test_delete_transaction_link_is_on_token_info_page()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee(route('transaction.delete', $this->transaction->id));
    }

    /**
     * Test the delete transaction link has a JS confirm
     */
    public function test_delete_transaction_link_has_js_confirm()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee('Delete this transaction');
    }

    /**
     * Test the delete transaction page is redirected for guests
     */
    public function test_delete_transaction_page_is_redirected_for_guests()
    {
        $this->get(route('transaction.delete', $this->transaction->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the transaction is soft deleted
     */
    public function test_transaction_is_soft_deleted()
    {
        $this->actingAs($this->user)->get(route('transaction.delete', $this->transaction->id));
        $this->assertSoftDeleted($this->table, ['id' => $this->transaction->id]);
    }

    /**
     * Test the transaction is not hard deleted
     */
    public function test_transaction_is_not_hard_deleted()
    {
        $this->actingAs($this->user)->get(route('transaction.delete', $this->transaction->id));
        $this->assertDatabaseCount($this->table, 1);
    }

    /**
     * Test deleting the transaction will not result in negeative balance
     */
    public function test_delete_transaction_not_result_negeative_balance()
    {
        Transaction::factory()->for($this->token)->create(['type' => Transaction::SELL, 'quantity' => 1]);
        $this->actingAs($this->user)->get(route('transaction.delete', $this->transaction->id));
        $this->assertDatabaseHas($this->table, ['id' => $this->transaction->id, 'deleted_at' => NULL]);
    }
}
