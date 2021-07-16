<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the Buy / Sell link is shown on the dashboard
     */
    public function test_the_buy_sell_link_shown_on_dashboard()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $transaction = CryptoTransaction::factory()->create(['crypto_token_id' => $token->id, 'quantity' => 1, 'price' => 10, 'type' => 'buy']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertSee(route('token.buy', $token->id))
            ->assertSee(route('token.sell', $token->id));
    }

    /**
     * Test the Buy / Sell link is shown on the token info page
     */
    public function test_the_buy_sell_link_shown_on_token_info()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $transaction = CryptoTransaction::factory()->create(['crypto_token_id' => $token->id, 'quantity' => 1, 'price' => 10, 'type' => 'buy']);

        $response = $this->actingAs($user)->get(route('token.show', $token->id));

        $response->assertSee(route('token.buy', $token->id))
            ->assertSee(route('token.sell', $token->id));
    }

    /**
     * Test the transaction create page is redirected for guests
     */
    public function test_the_transaction_create_page_is_redirected_for_guests()
    {
        $token = CryptoToken::factory()->create();

        $response = $this->get(route('token.buy', $token->id));
        $response->assertStatus(302);
    }

    /**
     * Test the transaction create page is rendered for users
     */
    public function test_the_transaction_create_page_is_rendered_for_users()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->get(route('token.buy', $token->id));
        $response->assertStatus(200);
    }

    /**
     * Test the transaction create page has the correct fields
     */
    public function test_the_transaction_create_page_has_correct_fields()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->get(route('token.buy', $token->id));
        $response->assertSee('name="crypto_token_id"', false)
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
    public function test_the_transaction_can_be_stored_with_valid_data()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->post(route('transaction.store', [
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => '100',
            'price' => '12',
            'type' => 'buy'
        ]));

        dd($response->content());

        $this->assertDatabaseCount('crypto_transactions', 1);
    }

    /**
     * Test the transaction can not be stored with invalid token
     */
    public function test_the_transaction_can_not_be_stored_with_invalid_token()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->post(route('transaction.store', $token->id, [
            'crypto_token_id' => $token->id+1, 
            'time' => now(),
            'quantity' => 100,
            'price' => 12.5,
            'type' => 'buy',
        ]));
        $this->assertDatabaseCount('crypto_transactions', 0);
    }

    /**
     * Test the transaction can not be stored with negative balance
     */
    public function test_the_transaction_can_not_be_stored_with_negative_balance()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $transaction = CryptoTransaction::factory()->create([
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => 100,
            'price' => 12.5,
            'type' => 'buy',
        ]);

        $response = $this->actingAs($user)->post(route('transaction.store', $token->id, [
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => 200,
            'price' => 12.5,
            'type' => 'sell',
        ]));

        $this->assertDatabaseCount('crypto_transactions', 1);
    }

    /**
     * Test the transaction can not be stored with 0 or negative quantity
     */
    public function test_the_transaction_can_not_be_stored_with_zero_or_negative_quantity()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->post(route('transaction.store', $token->id, [
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => 0,
            'price' => 12.5,
            'type' => 'buy',
        ]));
        $response = $this->actingAs($user)->post(route('transaction.store', $token->id, [
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => -100,
            'price' => 12.5,
            'type' => 'buy',
        ]));

        $this->assertDatabaseCount('crypto_transactions', 0);
    }

    /**
     * Test the transaction can not be stored with a negative price
     */
    public function test_the_transaction_can_not_be_stored_with_negative_price()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->post(route('transaction.store', $token->id, [
            'crypto_token_id' => $token->id, 
            'time' => now(),
            'quantity' => 100,
            'price' => -12,
            'type' => 'buy',
        ]));

        $this->assertDatabaseCount('crypto_transactions', 0);
    }

}
