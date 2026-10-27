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
    public function user_returns_null_and_never_queries_the_provider_for_non_string_or_empty_tokens()
    {
        foreach ([true, 123, ['some-token'], null, ''] as $nonStringOrEmptyToken) {
            $this->assertUserIsNullAndProviderIsNeverQueried($nonStringOrEmptyToken);
        }
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
            ->andReturn(new \App\User(['api_token' => 'a-valid-token']));

        $guard = new ApiTokenGuard($provider, Request::create('/', 'GET'));

        $this->assertTrue($guard->validate(['api_token' => 'a-valid-token']));
    }

    /** @test */
    public function user_is_rejected_when_the_stored_token_only_matches_case_insensitively()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn(new \App\User(['api_token' => 'AbCdEfGhIjKlMnOpQrStUvWx']));

        $request = Request::create('/api/user', 'GET', ['api_token' => 'abcdefghijklmnopqrstuvwx']);
        $guard = new ApiTokenGuard($provider, $request);

        $this->assertNull($guard->user());
    }

    /** @test */
    public function user_is_rejected_when_the_token_only_matches_with_trailing_whitespace()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn(new \App\User(['api_token' => 'abcdefghijklmnopqrstuvwx']));

        $request = Request::create('/api/user', 'GET', ['api_token' => 'abcdefghijklmnopqrstuvwx ']);
        $guard = new ApiTokenGuard($provider, $request);

        $this->assertNull($guard->user());
    }

    /** @test */
    public function validate_returns_false_when_the_stored_token_only_matches_case_insensitively()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->once()
            ->andReturn(new \App\User(['api_token' => 'AbCdEfGhIjKlMnOpQrStUvWx']));

        $guard = new ApiTokenGuard($provider, Request::create('/', 'GET'));

        $this->assertFalse($guard->validate(['api_token' => 'abcdefghijklmnopqrstuvwx']));
    }

    /** @test */
    public function user_returns_the_user_that_was_set_on_the_guard()
    {
        $user = new \App\User(['api_token' => 'abcdefghijklmnopqrstuvwx']);

        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldNotReceive('retrieveByCredentials');

        $guard = new ApiTokenGuard($provider, Request::create('/api/user', 'GET'));
        $guard->setUser($user);

        $this->assertTrue($guard->hasUser());
        $this->assertSame($user, $guard->user());
    }

    /** @test */
    public function validate_accepts_a_hashed_token_without_reverifying_the_plain_value()
    {
        $provider = Mockery::mock(UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->once()
            ->with(['api_token' => hash('sha256', 'a-valid-token')])
            ->andReturn(new \App\User(['api_token' => hash('sha256', 'a-valid-token')]));

        $request = Request::create('/', 'GET');
        $guard = new ApiTokenGuard($provider, $request, 'api_token', 'api_token', true);

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
