<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the factories can store data.
     *
     * @return void
     */
    public function test_create_transaction_factory()
    {
        Transaction::factory(5)->create();
        $this->assertDatabaseCount((new Transaction())->getTable(), 5);
    }

    /**
     * Test the transaction factory also creates a token
     */
    public function test_create_transaction_factory_creates_tokens()
    {
        Transaction::factory(5)->create();
        $this->assertDatabaseCount((new Token())->getTable(), 5);
    }

    /**
     * Test the total method of transactions calculates correct valude
     */
    public function test_total_method_calculates_correct_value()
    {
        $transaction = Transaction::factory()->create(['quantity' => 100, 'price' => 1.5]);
        $this->assertTrue($transaction->total()->getValue() === 100 * 1.5);
    }
}
