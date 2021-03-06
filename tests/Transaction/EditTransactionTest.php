<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTransactionTest extends TestCase
{
    use RefreshDatabase;

    private String $table;
    private User $user;
    private Token $token;
    private Transaction $transaction;
    private Array $good;
    private Array $bad;

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
            'original' => [
                'token_id' => $this->token->id, 
                'time' => '2021-06-25T10:32:45',
                'quantity' => 100,
                'price' => 12,
                'type' => Transaction::BUY,
            ],
            'edited' => [
                'token_id' => $this->token->id, 
                'time' => '2021-06-21T10:32:45',
                'quantity' => 99,
                'price' => 11,
                'type' => Transaction::BUY,            
            ],
        ];
        $this->bad = [
            'time' => [
                'empty' => '',
                'future' => now()->addDays(1)->format('Y-m-d\TH:i:s'), 
                'future2' => now()->addHours(2)->format('Y-m-d\TH:i:s'), 
                'future3' => now()->addMinutes(2)->format('Y-m-d\TH:i:s'),
                'nottime' => 'half past one',
            ],
            'quantity' => [
                'empty' => '',
                'zero' => 0,
                'negative' => -10, 
                'notnum' => 'ten',
            ],
            'price' => [
                'empty' => '',
                'negative' => -10, 
                'notnum' => 'ten quid',
            ],
            'type' => [
                'empty' => '',
                'wrong' => 'dump them', 
            ],
        ];

        $this->transaction = Transaction::factory()->for($this->token)->create($this->good['original']);
        $this->good['edited']['transaction'] = $this->transaction->id;
    }

    /**
     * Test the edit transaction link is displayed on token info page
     */
    public function test_edit_transaction_link_is_rendered_on_token_info_page()
    {
        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee(route('transaction.edit', $this->transaction->id));
    }

    /**
     * Test the transaction edit page is redirected for guests
     */
    public function test_edit_transaction_page_is_redirected_for_guests()
    {
        $this->get(route('transaction.edit', $this->transaction->id))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /**
     * Test the transaction edit page is rendered for users
     */
    public function test_edit_transaction_page_is_rendered_for_users()
    {
        $this->actingAs($this->user)->get(route('transaction.edit', $this->transaction->id))
            ->assertStatus(200);
    }

    /**
     * Test the transaction edit page has correct fields
     */
    public function test_edit_transaction_page_has_correct_fields()
    {
        $this->actingAs($this->user)->get(route('transaction.edit', $this->transaction->id))
            ->assertSee('name="token_id"', false)
            ->assertSee('name="time"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="price"', false)
            ->assertSee('name="type', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('transaction.update', $this->transaction->id));
    }

    /**
     * Test the transaction can be updated with valid data
     */
    public function test_transaction_can_be_updated_with_valid_data()
    {
        $this->actingAs($this->user)->post(route('transaction.update', $this->good['edited']));
        unset($this->good['edited']['transaction']);
        
        $this->assertDatabaseHas($this->table, $this->good['edited']);
    }

    /**
     * Test the transaction can not be updated with invalid token
     */
    public function test_transaction_can_not_be_updated_with_invalid_token()
    {
        $this->actingAs($this->user)->post(route('transaction.update', array_merge($this->good['edited'], [
            'token_id' => $this->token->id + 1
        ])));

        $this->assertDatabaseHas($this->table, $this->good['original']);
    }

    /**
     * Test the transaction can not be updated with negative balance
     */
    public function test_transaction_can_not_be_updated_with_negative_balance()
    {
        $transaction = Transaction::factory()->for($this->token)->create(['quantity' => 1, 'type' => Transaction::SELL]);

        $this->actingAs($this->user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'token_id' => $transaction->token_id, 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => 101,
            'price' => $transaction->price->getValue(),
            'type' => $transaction->type,
        ]));
        $this->assertDatabaseHas($this->table, ['quantity' => 1]);
    }

    /**
     * Test the transaction can not be updated with a invalid price
     */
    public function test_transaction_can_not_be_updated_with_invlaid_price()
    {
        foreach($this->bad['price'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.update', array_merge($this->good['original'], [
                'transaction' => $this->transaction->id,
                'price' => $value,
            ])));
        }
        $this->assertDatabaseHas($this->table, ['price' => $this->good['original']['price']]);
    }

    /**
     * Test the transaction can not be updated with a invalid quantity
     */
    public function test_transaction_can_not_be_updated_with_invlaid_quantity()
    {
        foreach($this->bad['quantity'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.update', array_merge($this->good['original'], [
                'transaction' => $this->transaction->id,
                'quantity' => $value,
            ])));
        }
        $this->assertDatabaseHas($this->table, ['quantity' => $this->good['original']['quantity']]);
    }

    /**
     * Test the transaction token can not be changed causing a negative balance
     */
    public function test_transaction_token_change_can_not_be_stored_with_negative_balance()
    {
        // Add a sell transaction
        Transaction::factory()->for($this->token)->create([
            'token_id' => $this->token->id, 
            'time' => '2021-06-25T11:32:45',
            'quantity' => 50,
            'price' => 12,
            'type' => Transaction::SELL,            
        ]);

        // Change the original buy order to a different token
        $token2 = Token::factory()->create();

        $this->actingAs($this->user)->post(route('transaction.update', array_merge($this->good['original'], [
            'transaction' => $this->transaction->id,
            'token_id' => $token2->id,
        ])));

        $this->assertDatabaseHas($this->table, $this->good['original']);
        
    }
    
}
