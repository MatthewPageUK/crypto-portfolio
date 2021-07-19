<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the edit transaction link is displayed on token info page
     */
    public function test_edit_transaction_link_is_rendered_on_token_info_page()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $transaction = CryptoTransaction::factory()->for($token)->create();

        $response = $this->actingAs($user)->get(route('token.show', $token->id));
        $response->assertSee(route('transaction.edit', $transaction->id));
    }

    /**
     * Test the transaction edit page is redirected for guests
     */
    public function test_edit_transaction_page_is_redirected_for_guests()
    {
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create();

        $response = $this->get(route('transaction.edit', $transaction));
        $response->assertStatus(302);
    }

    /**
     * Test the transaction edit page is rendered for users
     */
    public function test_edit_transaction_page_is_rendered_for_users()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create();

        $response = $this->actingAs($user)->get(route('transaction.edit', $transaction->id));
        $response->assertStatus(200);
    }

    /**
     * Test the transaction edit page has correct fields
     */
    public function test_edit_transaction_page_has_correct_fields()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create();

        $response = $this->actingAs($user)->get(route('transaction.edit', $transaction->id));
        $response->assertSee('name="crypto_token_id"', false)
            ->assertSee('name="time"', false)
            ->assertSee('name="quantity"', false)
            ->assertSee('name="price"', false)
            ->assertSee('name="type', false)
            ->assertSee('type="submit"', false)
            ->assertSee(route('transaction.update', $transaction->id));
    }

    /**
     * Test the transaction can be updated with valid data
     */
    public function test_transaction_can_be_updated_with_valid_data()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create(['quantity' => 5, 'type' => 'buy']);

        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'crypto_token_id' => $transaction->crypto_token_id, 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => 100,
            'price' => $transaction->price,
            'type' => $transaction->type,
        ]));
        $this->assertDatabaseHas('crypto_transactions', ['quantity' => 100]);
    }

    /**
     * Test the transaction can not be updated with invalid token
     */
    public function test_transaction_can_not_be_updated_with_invalid_token()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create(['quantity' => 5, 'type' => 'buy']);

        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'crypto_token_id' => $transaction->crypto_token_id + 1, 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => 100,
            'price' => $transaction->price,
            'type' => $transaction->type,
        ]));
        $this->assertDatabaseHas('crypto_transactions', ['quantity' => 5]);
    }

    /**
     * Test the transaction can not be updated with negative balance
     */
    public function test_transaction_can_not_be_updated_with_negative_balance()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();
        $transaction = CryptoTransaction::factory()->for($token)->create(['quantity' => 5, 'type' => 'buy']);
        $transaction2 = CryptoTransaction::factory()->for($token)->create(['quantity' => 3, 'type' => 'sell']);

        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction2->id,
            'crypto_token_id' => $transaction2->crypto_token_id, 
            'time' => $transaction2->time->format('Y-m-d\TH:i:s'), 
            'quantity' => 10,
            'price' => $transaction2->price,
            'type' => $transaction2->type,
        ]));
        $this->assertDatabaseHas('crypto_transactions', ['quantity' => 3]);
    }

    /**
     * Test the transaction can not be updated with a negative price
     */
    public function test_transaction_can_not_be_updated_with_negative_price()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create(['price' => 5, 'type' => 'buy']);

        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'crypto_token_id' => $transaction->crypto_token_id , 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => $transaction->quantity,
            'price' => -5,
            'type' => $transaction->type,
        ]));
        $this->assertDatabaseHas('crypto_transactions', ['price' => 5]);
    }

    /**
     * Test the transaction can not be updated with 0 or negative quantity
     */
    public function test_transaction_can_not_be_updated_with_zero_or_negative_quantity()
    {
        $user = User::factory()->create();
        $transaction = CryptoTransaction::factory()->for(CryptoToken::factory()->create())->create(['quantity' => 5, 'type' => 'buy']);

        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'crypto_token_id' => $transaction->crypto_token_id , 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => 0,
            'price' => $transaction->price,
            'type' => $transaction->type,
        ]));
        $response = $this->actingAs($user)->post(route('transaction.update', [
            'transaction' => $transaction->id,
            'crypto_token_id' => $transaction->crypto_token_id , 
            'time' => $transaction->time->format('Y-m-d\TH:i:s'), 
            'quantity' => -5,
            'price' => $transaction->price,
            'type' => $transaction->type,
        ]));
        $this->assertDatabaseHas('crypto_transactions', ['quantity' => 5]);
    }

     /**
      * To do
      * Change token - not neg balance
      * Change type - not neg balance
      */

}
