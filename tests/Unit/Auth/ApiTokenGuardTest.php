<?php

namespace Tests\Unit\Auth;

use App\Auth\ApiTokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ApiTokenGuardTest extends TestCase
{
    /** @test */
    public function user_returns_null_and_never_queries_the_provider_for_a_boolean_token()
    {
        $this->assertUserIsNullAndProviderIsNeverQueried(true);
    }

    /** @test */
    public function user_returns_null_and_never_queries_the_provider_for_an_array_token()
    {
        $this->assertUserIsNullAndProviderIsNeverQueried(['some-token']);
    }

    /** @test */
    public function user_returns_null_and_never_queries_the_provider_for_a_null_token()
    {
        $this->assertUserIsNullAndProviderIsNeverQueried(null);
    }

    /** @test */
    public function user_returns_null_and_never_queries_the_provider_for_an_empty_token()
    {
        $this->assertUserIsNullAndProviderIsNeverQueried('');
    }

    /** @test */
    public function validate_returns_false_and_never_queries_the_provider_for_non_string_tokens()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldNotReceive('retrieveByCredentials');

        $guard = new ApiTokenGuard($provider, Request::create('/', 'GET'));

        foreach ([true, 123, ['some-token'], null, ''] as $nonStringOrEmptyToken) {
            $this->assertFalse($guard->validate(['api_token' => $nonStringOrEmptyToken]));
        }
    }

    /** @test */
    public function validate_queries_the_provider_for_a_non_empty_string_token()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->once()
            ->with(['api_token' => 'a-valid-token'])
            ->andReturn(new \App\User);

        $guard = new ApiTokenGuard($provider, Request::create('/', 'GET'));

        $this->assertTrue($guard->validate(['api_token' => 'a-valid-token']));
    }

    private function assertUserIsNullAndProviderIsNeverQueried($token): void
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldNotReceive('retrieveByCredentials');

        $request = Request::create('/api/user', 'GET', ['api_token' => $token]);
        $guard = new ApiTokenGuard($provider, $request);

        $this->assertNull($guard->user());
    }
}
