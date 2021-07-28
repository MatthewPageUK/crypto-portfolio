<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCollectionTest extends TestCase
{
    use RefreshDatabase;

    private Token $token;
    private Array $result;

    /**
     * Setup a token and transactions
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->token = Token::factory()->create();
        $this->result = $this->createTestTransactions();
    }

    /**
     * Create some test transactions when needed and return the correct results
     * 
     * Avg price = 1.125
     * Avg hodl = 1.1
     * Avg sell = 1.55
     * Total sell = 100
     * Total buy = 200
     * Balance = 100
     */
    private function createTestTransactions(): array
    {
        Transaction::factory()->for($this->token)->create(['type' => Transaction::BUY, 'quantity' => '100', 'price' => 1.15, 'time' => now()->subDays(10)->format('Y-m-d\TH:i:s')]);   
        Transaction::factory()->for($this->token)->create(['type' => Transaction::BUY, 'quantity' => '100', 'price' => 1.10, 'time' => now()->subDays(9)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($this->token)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.6, 'time' => now()->subDays(8)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($this->token)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.5, 'time' => now()->subDays(7)->format('Y-m-d\TH:i:s')]);  

        return [
            'avgprice' => 1.125,
            'avghodl' => 1.1,
            'avgsell' => 1.55,
            'totalsell' => 100,
            'totalbuy' => 200,
            'balance' => 100,
        ];
    }

    /**
     * Test the transaction collection calculates the correct balance
     */
    public function test_transaction_collection_calculates_correct_balance()
    {
        $this->assertTrue( $this->token->transactions->calcBalance() == $this->result['balance'] );
    }

    /**
     * Test the transaction collection calculates the correct average buy price
     */
    public function test_transaction_collection_calculates_correct_avg_buy_price()
    {
        $this->assertTrue( $this->token->transactions->averageBuyPrice() == $this->result['avgprice'] );
    }

    /**
     * Test the transaction collection calculates the correct average sell price
     */
    public function test_transaction_collection_calculates_correct_avg_sell_price()
    {
        $this->assertTrue( $this->token->transactions->averageSellPrice() == $this->result['avgsell'] );
    }

    /**
     * Test the transaction collection calculates the correct average hodl buy price
     */
    public function test_transaction_collection_calculates_correct_avg_hodl_buy_price()
    {
        $this->assertTrue( $this->token->transactions->averageHodlBuyPrice() == $this->result['avghodl'] );
    }

    /**
     * Test the transaction collection fails a negative balance
     */
    public function test_transaction_collection_fails_negative_balance()
    {
        $token2 = Token::factory()->create();
        Transaction::factory()->for($token2)->create(['type' => Transaction::BUY, 'quantity' => '50', 'price' => 1.10, 'time' => now()->subDays(9)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($token2)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.6, 'time' => now()->subDays(8)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($token2)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.5, 'time' => now()->subDays(7)->format('Y-m-d\TH:i:s')]);  

        $this->assertTrue( ! $token2->transactions->validateTransactions() );
    }

    /**
     * Test the transaction collection validates a positive balance
     */
    public function test_transaction_collection_validates_positive_balance()
    {
        $token2 = Token::factory()->create();
        Transaction::factory()->for($token2)->create(['type' => Transaction::BUY, 'quantity' => '50', 'price' => 1.15, 'time' => now()->subDays(10)->format('Y-m-d\TH:i:s')]);   
        Transaction::factory()->for($token2)->create(['type' => Transaction::BUY, 'quantity' => '50', 'price' => 1.10, 'time' => now()->subDays(9)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($token2)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.5, 'time' => now()->subDays(7)->format('Y-m-d\TH:i:s')]);  

        $this->assertTrue( $token2->transactions->validateTransactions() );
    }

    /**
     * Test the transaction collection validates a zero balance
     */
    public function test_transaction_collection_validates_zero_balance()
    {
        $token2 = Token::factory()->create();
        Transaction::factory()->for($token2)->create(['type' => Transaction::BUY, 'quantity' => '50', 'price' => 1.15, 'time' => now()->subDays(10)->format('Y-m-d\TH:i:s')]);   
        Transaction::factory()->for($token2)->create(['type' => Transaction::BUY, 'quantity' => '50', 'price' => 1.10, 'time' => now()->subDays(9)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($token2)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.6, 'time' => now()->subDays(8)->format('Y-m-d\TH:i:s')]);  
        Transaction::factory()->for($token2)->create(['type' => Transaction::SELL, 'quantity' => '50', 'price' => 1.5, 'time' => now()->subDays(7)->format('Y-m-d\TH:i:s')]);  

        $this->assertTrue( $token2->transactions->validateTransactions() );
    }

    /**
     * Test the transaction collection validates with zero transactions
     */
    public function test_transaction_collection_validates_zero_transactions()
    {
        $token2 = Token::factory()->create();

        $this->assertTrue( $token2->transactions->validateTransactions() );
    }

}
