<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CryptoTokenTest extends TestCase
{
    use RefreshDatabase;

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
    private function createTestTransactions(CryptoToken $token): array
    {
        CryptoTransaction::factory()->for($token)->create(['type' => 'buy', 'quantity' => '100', 'price' => 1.15, 'time' => now()->subDays(10)->format('Y-m-d\TH:i:s')]);   
        CryptoTransaction::factory()->for($token)->create(['type' => 'buy', 'quantity' => '100', 'price' => 1.10, 'time' => now()->subDays(9)->format('Y-m-d\TH:i:s')]);  
        CryptoTransaction::factory()->for($token)->create(['type' => 'sell', 'quantity' => '50', 'price' => 1.6, 'time' => now()->subDays(8)->format('Y-m-d\TH:i:s')]);  
        CryptoTransaction::factory()->for($token)->create(['type' => 'sell', 'quantity' => '50', 'price' => 1.5, 'time' => now()->subDays(7)->format('Y-m-d\TH:i:s')]);  

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
     * Test we can save the data to the database table.
     *
     * @return void
     */
    public function test_create_token_factory()
    {
        CryptoToken::factory(5)->create();
        $this->assertDatabaseCount((new CryptoToken())->getTable(), 5);
    }

    /**
     * Test the token info page is redirected for guests
     */
    public function test_token_info_page_is_redirected_to_login_for_guests()
    {
        $token = CryptoToken::factory()->create();
        $this->get(route('token.show', ['token' => $token->id]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** 
     * Test the token info page can be displayed
     */
    public function test_token_info_page_can_rendered()
    {
        $token = CryptoToken::factory()->create();
        $this->actingAs(User::factory()->create())->get(route('token.show', ['token' => $token->id]))
            ->assertSee($token->symbol)
            ->assertSee($token->name);
    }

    /**
     * Test the token calculates the correct balance
     */
    public function test_token_calculates_correct_balance()
    {
        $token = CryptoToken::factory()->create();
        $result = $this->createTestTransactions($token);
        $token->updateBalance();
        
        $this->assertTrue( $token->getBalance() == $result['balance'] );
    }

    /**
     * Test the token calculates the correct average buy price
     */
    public function test_token_calculates_correct_avg_buy_price()
    {
        $token = CryptoToken::factory()->create();
        $result = $this->createTestTransactions($token);

        $this->assertTrue( $token->averageBuyPrice() == $result['avgprice'] );
    }

    /**
     * Test the token calculates the correct average sell price
     * Todo
     */

    /**
     * Test the token calculates the correct average hodl buy price
     * Todo
     */
    public function test_token_calculates_correct_avg_hodl_buy_price()
    {
        $token = CryptoToken::factory()->create();
        $result = $this->createTestTransactions($token);

        $this->assertTrue( $token->averageHodlBuyPrice() == $result['avghodl'] );
    }

}
