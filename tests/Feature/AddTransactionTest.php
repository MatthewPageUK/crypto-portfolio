<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddTransactionTest extends TestCase
{
    use RefreshDatabase;

    private String $table;
    private Token $token;
    private User $user;
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
            'token_id' => $this->token->id, 
            'time' => '2021-06-25T10:32:45',
            'quantity' => 100,
            'price' => 12,
            'type' => Transaction::BUY,
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
    }

    /**
     * Test the Buy / Sell link is shown on the dashboard
     */
    public function test_transaction_buy_sell_link_rendered_on_dashboard()
    {
        Transaction::factory()->for($this->token)->create($this->good);

        $this->actingAs($this->user)->get(route('dashboard'))
            ->assertSee(route('token.buy', $this->token->id))
            ->assertSee(route('token.sell', $this->token->id));
    }

    /**
     * Test the Buy / Sell link is shown on the token info page
     */
    public function test_transaction_buy_sell_link_rendered_on_token_info()
    {
        Transaction::factory()->for($this->token)->create($this->good);

        $this->actingAs($this->user)->get(route('token.show', $this->token->id))
            ->assertSee(route('token.buy', $this->token->id))
            ->assertSee(route('token.sell', $this->token->id));
    }

    /**
     * Test the transaction create page is redirected for guests
     */
    public function test_transaction_create_page_is_redirected_for_guests()
    {
        $this->get(route('token.buy', $this->token->id))
            ->assertStatus(302);
    }

    /**
     * Test the transaction create page is rendered for users
     */
    public function test_transaction_create_page_is_rendered_for_users()
    {
        $this->actingAs($this->user)->get(route('token.buy', $this->token->id))
            ->assertStatus(200);
    }

    /**
     * Test the transaction create page has the correct fields
     */
    public function test_transaction_create_page_has_correct_fields()
    {
        $this->actingAs($this->user)->get(route('token.buy', $this->token->id))
            ->assertSee('name="token_id"', false)
            ->assertSee('name="time"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="price"', false)
            ->assertSee('name="type', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('transaction.store'));
    }

    /**
     * Test the transaction can be stored with valid data
     */
    public function test_transaction_can_be_stored_with_valid_data()
    {
        $this->actingAs($this->user)->post(route('transaction.store', $this->good));
        $this->assertDatabaseHas($this->table, $this->good);
    }

    /**
     * Test the transaction can not be stored with invalid token
     */
    public function test_transaction_can_not_be_stored_with_invalid_token()
    {
        $this->actingAs($this->user)->post(route('transaction.store', array_merge([
            'token_id' => $this->token->id+1, 
        ])));
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test the transaction can not be stored with negative balance
     */
    public function test_transaction_can_not_be_stored_with_negative_balance()
    {
        Transaction::factory()->create($this->good);

        $this->actingAs($this->user)->post(route('transaction.store', array_merge($this->good, [
            'quantity' => $this->good['quantity']+1,
            'type' => Transaction::SELL,
        ])));
        $this->assertDatabaseCount($this->table, 1);
    }

    /**
     * Test the transaction can not be stored with invalid quantity
     */
    public function test_transaction_can_not_be_stored_with_invalid_quantity()
    {
        foreach($this->bad['quantity'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.store', array_merge($this->good, [
                'quantity' => $value
            ])));
        }
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test the transaction can not be stored with invalid price
     */
    public function test_transaction_can_not_be_stored_with_invalid_price()
    {
        foreach($this->bad['price'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.store', array_merge($this->good, [
                'price' => $value
            ])));
        }
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test the transaction can not be stored with invalid time
     */
    public function test_transaction_can_not_be_stored_with_invalid_time()
    {
        foreach($this->bad['time'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.store', array_merge($this->good, [
                'time' => $value
            ])));
        }
        $this->assertDatabaseCount($this->table, 0);
    }

    /**
     * Test the transaction can not be stored with invalid type
     */
    public function test_transaction_can_not_be_stored_with_invalid_type()
    {
        foreach($this->bad['type'] as $key => $value)
        {
            $this->actingAs($this->user)->post(route('transaction.store', array_merge($this->good, [
                'type' => $value
            ])));
        }
        $this->assertDatabaseCount($this->table, 0);
    }
}
