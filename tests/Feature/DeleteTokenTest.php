<?php

namespace Tests\Feature;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the delete token link is shown on token info page
     */
    public function test_delete_token_link_is_on_token_info_page()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->get(route('token.show', $token->id));
        $response->assertSee(route('token.delete', $token->id));
    }

    /**
     * Test the delete token link has a JS confirm
     */
    public function test_delete_token_link_has_js_confirm()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create();

        $response = $this->actingAs($user)->get(route('token.show', $token->id));
        $response->assertSee('Delete this token and ALL transactions');
    }

    /**
     * Test the delete token page is redirected for guests
     */
    public function test_delete_token_page_is_redirected_for_guests()
    {
        $token = CryptoToken::factory()->create();

        $response = $this->get(route('token.delete', $token->id));
        $response->assertStatus(302);
    }

    /**
     * Test the token is soft deleted
     */
    public function test_token_is_soft_deleted()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create(['symbol' => 'DEL', 'name' => 'Delete token']);

        $response = $this->actingAs($user)->get(route('token.delete', $token->id));
        $this->assertSoftDeleted('crypto_tokens', ['symbol' => 'DEL']);
    }

    /**
     * Test the token is not hard deleted
     */
    public function test_token_is_not_hard_deleted()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create(['symbol' => 'DEL', 'name' => 'Delete token']);

        $response = $this->actingAs($user)->get(route('token.delete', $token->id));
        $this->assertDatabaseCount('crypto_tokens', 1);
    }

    /**
     * Test transactions are soft deleted on token delete
     */
    public function test_trabsactions_are_soft_deleted_on_token_delete()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create(['symbol' => 'DEL', 'name' => 'Delete token']);
        $trans = CryptoTransaction::factory()->create(['crypto_token_id' => $token->id]);

        $response = $this->actingAs($user)->get(route('token.delete', $token->id));
        $this->assertSoftDeleted('crypto_transactions', ['crypto_token_id' => $token->id]);
    }

    /**
     * Test transactions are not hard deleted on token delete
     */
    public function test_trabsactions_are_not_hard_deleted_on_token_delete()
    {
        $user = User::factory()->create();
        $token = CryptoToken::factory()->create(['symbol' => 'DEL', 'name' => 'Delete token']);
        $trans = CryptoTransaction::factory()->create(['crypto_token_id' => $token->id]);

        $response = $this->actingAs($user)->get(route('token.delete', $token->id));
        $this->assertDatabaseCount('crypto_transactions', 1);
    }
}
