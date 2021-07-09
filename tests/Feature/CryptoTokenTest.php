<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test we can save the data to the database table.
     *
     * @return void
     */
    public function test_create_cryptotoken_data()
    {
        $tokens = CryptoToken::factory(5)->create();
        $this->assertDatabaseCount('crypto_tokens', 5);
    }
}
