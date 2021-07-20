<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the factories can store data.
     *
     * @return void
     */
    public function test_create_transaction_factory()
    {
        CryptoTransaction::factory(5)->create();
        $this->assertDatabaseCount((new CryptoTransaction())->getTable(), 5);
    }

    /**
     * Test the transaction factory also creates a token
     */
    public function test_create_transaction_factory_creates_tokens()
    {
        CryptoTransaction::factory(5)->create();
        $this->assertDatabaseCount((new CryptoToken())->getTable(), 5);
    }

    /**
     * Test the total method of transactions calculates correct valude
     */
    public function test_total_method_calculates_correct_value()
    {
        $transaction = CryptoTransaction::factory()->create(['quantity' => 100, 'price' => 1.5]);
        $this->assertTrue($transaction->total() === 100 * 1.5);
    }
}
