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

    // /**
    //  * Test the transaction can not be updated with invalid symbol
    //  */
    // public function test_token_can_not_be_updated_with_invalid_symbol()
    // {
    //     $token = CryptoToken::factory()->create();
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->post(route('transaction.update', ['token' => $token->id, 'symbol' => 'b a d', 'name' => 'Bad transaction symbol']));
    //     $response = $this->actingAs($user)->post(route('transaction.update', ['token' => $token->id, 'symbol' => 'badbadbadbadbadbadbadbadbadbadbadb', 'name' => 'Bad transaction long']));
    //     $this->assertDatabaseMissing('crypto_tokens', ['symbol' => 'b a d'])->assertDatabaseMissing('crypto_tokens', ['symbol' => 'badbadbadbadbadbadbadbadbadbadbadb']);
    // }

    // /**
    //  * Test the transaction can not updated with invalid name
    //  */
    // public function test_token_can_not_be_updated_with_invalid_name()
    // {
    //     $token = CryptoToken::factory()->create();
    //     $user = User::factory()->create();
    //     $badString = "badbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbadbad";

    //     $response = $this->actingAs($user)->post(route('transaction.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => $badString]));
    //     $this->assertDatabaseMissing('crypto_tokens', ['name' => $badString]);
    // }

    // /**
    //  * Test duplicate transaction symbols can not be updated
    //  */
    // public function test_duplicate_token_can_not_be_updated()
    // {
    //     CryptoToken::create([
    //         'symbol' => 'ABC',
    //         'name' => 'Original ABC token',
    //     ]);
    //     $token = CryptoToken::factory()->create();
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->post(route('transaction.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => 'Second ABC token']));
    //     $this->assertDatabaseMissing('crypto_tokens', ['name' => 'Second ABC token']);
    // }

    // /**
    //  * Test duplicate but deleted transaction symbols can be updated
    //  */
    // public function test_duplicate_but_deleted_token_can_be_updated()
    // {
    //     CryptoToken::create([
    //         'symbol' => 'ABC',
    //         'name' => 'Original ABC token',
    //     ])->delete();
    //     $token = CryptoToken::factory()->create();
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($user)->post(route('transaction.update', ['token' => $token->id, 'symbol' => 'ABC', 'name' => 'A new ABC token']));
    //     $this->assertDatabaseHas('crypto_tokens', ['symbol' => 'ABC', 'name' => 'A new ABC token']);
    // }

}
