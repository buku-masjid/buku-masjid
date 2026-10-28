<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_their_profile_from_api()
    {
        $user = $this->loginAsApiUser('admin', ['password' => bcrypt('testing')]);

        $this->getJson(route('api.user'));

        $this->seeJson([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->dontSeeJson([
            'api_token' => $user->api_token,
        ]);
    }
}
