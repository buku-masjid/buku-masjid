<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_logout_and_the_old_api_token_is_revoked()
    {
        $user = $this->createUser();
        $oldToken = $user->api_token;

        $this->postJson(route('api.logout'), [], [
            'Authorization' => 'Bearer '.$oldToken,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson(['message' => __('auth.logged_out')]);

        $user->refresh();
        $this->assertNotSame($oldToken, $user->api_token);
        $this->assertSame(24, strlen($user->api_token));

        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer '.$oldToken,
        ]);
        $this->seeStatusCode(401);

        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);
        $this->seeStatusCode(200);
    }

    /** @test */
    public function logout_requires_an_api_token()
    {
        $this->postJson(route('api.logout'));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function logout_with_a_wrong_api_token_is_rejected()
    {
        $this->postJson(route('api.logout'), [], [
            'Authorization' => 'Bearer definitely-not-a-real-token-value',
        ]);

        $this->seeStatusCode(401);
    }

    /** @test */
    public function logout_does_not_change_the_token_of_a_user_that_did_not_authenticate()
    {
        $user = $this->createUser();
        $otherUser = $this->createUser();
        $otherToken = $otherUser->api_token;

        $this->postJson(route('api.logout'), [], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeStatusCode(200);

        $otherUser->refresh();
        $this->assertSame($otherToken, $otherUser->api_token);
    }
}
