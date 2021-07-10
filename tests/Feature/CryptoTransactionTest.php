<?php

namespace Tests\Feature;

use App\Models\CryptoTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test we can save the data to the database table.
     *
     * @return void
     */
    public function test_create_cryptotransaction_data()
    {
        $transactions = CryptoTransaction::factory(5)->create();
        $this->assertDatabaseCount('crypto_transactions', 5);
    }
    public function test_create_cryptotransaction_tokens()
    {
        $transactions = CryptoTransaction::factory(5)->create();
        $this->assertDatabaseCount('crypto_tokens', 5);
    }
}
