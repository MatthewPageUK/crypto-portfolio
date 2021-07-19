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

    public function test_create_transaction_factory_creates_tokens()
    {
        CryptoTransaction::factory(5)->create();
        $this->assertDatabaseCount((new CryptoToken())->getTable(), 5);
    }
}
