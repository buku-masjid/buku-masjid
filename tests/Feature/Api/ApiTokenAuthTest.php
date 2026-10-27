<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_access_api_using_their_api_token_as_bearer_header()
    {
        $user = $this->createUser();

        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function user_can_access_api_using_their_api_token_as_query_parameter()
    {
        $user = $this->createUser();

        $this->getJson(route('api.user', ['api_token' => $user->api_token]));

        $this->seeStatusCode(200);
        $this->seeJson([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function request_without_token_is_rejected_with_401()
    {
        $this->getJson(route('api.user'));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function request_without_token_is_rejected_with_401_even_without_an_accept_json_header()
    {
        $this->get(route('api.user'));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function every_api_route_without_token_is_rejected_with_401_even_without_an_accept_json_header()
    {
        foreach ([route('api.user'), route('api.transactions.index'), route('api.categories.index'), route('api.books.index')] as $url) {
            $this->get($url);

            $this->assertSame(401, $this->response->getStatusCode(), 'Failed on '.$url);
        }

        $this->post(route('api.logout'));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function request_with_wrong_token_is_rejected_with_401()
    {
        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer definitely-not-a-real-token-value',
        ]);

        $this->seeStatusCode(401);
    }

    /** @test */
    public function request_with_non_string_token_is_rejected_with_401()
    {
        $this->getJson(route('api.user', ['api_token' => ['not-a-string']]));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function api_token_returned_by_login_can_be_used_to_create_a_transaction()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['manager_id' => $user->id]);

        $this->postJson(route('api.transactions.store'), [
            'date' => '2026-09-29',
            'amount' => 291000,
            'in_out' => 0,
            'description' => 'Beli 11 dus air mineral gelas dan botol kecil',
            'book_id' => $book->id,
        ], [
            'Authorization' => 'Bearer '.$user->api_token,
        ]);

        $this->seeStatusCode(201);
        $this->seeInDatabase('transactions', [
            'date' => '2026-09-29',
            'amount' => 291000.00,
            'in_out' => 0,
            'description' => 'Beli 11 dus air mineral gelas dan botol kecil',
            'book_id' => $book->id,
        ]);
    }

    /** @test */
    public function user_can_login_and_use_the_returned_api_token_on_a_protected_endpoint()
    {
        $user = $this->createUser('admin', ['password' => bcrypt('testing')]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'testing',
        ]);

        $this->seeStatusCode(200);
        $apiToken = $this->response->json('api_token');

        $this->assertIsString($apiToken);
        $this->assertSame(24, strlen($apiToken));
        $this->assertSame($user->api_token, $apiToken);

        $this->getJson(route('api.user'), [
            'Authorization' => 'Bearer '.$apiToken,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function login_generates_an_api_token_when_the_user_does_not_have_one()
    {
        $user = $this->createUser('admin', [
            'password' => bcrypt('testing'),
            'api_token' => '',
        ]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'testing',
        ]);

        $this->seeStatusCode(200);
        $apiToken = $this->response->json('api_token');

        $this->assertIsString($apiToken);
        $this->assertSame(24, strlen($apiToken));
        $this->seeInDatabase('users', [
            'id' => $user->id,
            'api_token' => $apiToken,
        ]);
    }

    /** @test */
    public function every_protected_api_route_accepts_the_api_token()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['manager_id' => $user->id]);

        $protectedRoutes = [
            route('api.user'),
            route('api.transactions.index'),
            route('api.categories.index'),
            route('api.books.index'),
        ];

        foreach ($protectedRoutes as $url) {
            $this->getJson($url, [
                'Authorization' => 'Bearer '.$user->api_token,
            ]);

            $this->assertSame(200, $this->response->getStatusCode(), 'Failed on '.$url);
        }
    }

    /** @test */
    public function every_protected_api_route_rejects_a_request_without_the_api_token()
    {
        $protectedRoutes = [
            route('api.user'),
            route('api.transactions.index'),
            route('api.categories.index'),
            route('api.books.index'),
        ];

        foreach ($protectedRoutes as $url) {
            $this->getJson($url);

            $this->assertSame(401, $this->response->getStatusCode(), 'Failed on '.$url);
        }
    }

    /** @test */
    public function a_token_that_only_matches_case_insensitively_is_rejected_through_the_http_layer()
    {
        $user = $this->createUser('admin', ['api_token' => 'AbCdEfGhIjKlMnOpQrStUvWx']);

        $provider = \Mockery::mock(\Illuminate\Contracts\Auth\UserProvider::class);
        $provider->shouldReceive('retrieveByCredentials')
            ->andReturn($user);

        auth()->guard('api')->setProvider($provider);

        $this->getJson(route('api.user', ['api_token' => 'abcdefghijklmnopqrstuvwx']));

        $this->seeStatusCode(401);
    }

    /** @test */
    public function the_api_guard_is_the_apps_own_token_guard()
    {
        $user = $this->createUser();
        $this->getJson(route('api.user', ['api_token' => $user->api_token]));

        $this->seeStatusCode(200);
        $this->assertInstanceOf(\App\Auth\ApiTokenGuard::class, auth()->guard('api'));
    }

    /** @test */
    public function active_book_id_query_parameter_selects_the_active_book()
    {
        $user = $this->createUser();
        // Create TWO books so the second one's id differs from
        // config('masjid.default_book_id') (which is 1). With a single book
        // the assertion below would pass even if the macro were broken.
        factory(Book::class)->create(['manager_id' => $user->id]);
        $otherBook = factory(Book::class)->create(['manager_id' => $user->id]);

        $this->assertNotEquals(config('masjid.default_book_id'), $otherBook->id);

        $this->getJson(route('api.transactions.index', [
            'api_token' => $user->api_token,
            'active_book_id' => $otherBook->id,
        ]));

        $this->seeStatusCode(200);
        $this->assertEquals($otherBook->id, auth()->guard('api')->activeBookId());
    }

    /** @test */
    public function the_api_guard_class_does_not_depend_on_passport()
    {
        $reflection = new \ReflectionClass(\App\Auth\ApiTokenGuard::class);
        $source = file_get_contents($reflection->getFileName());

        $this->assertStringNotContainsString('Passport', $source);
    }

    /** @test */
    public function the_service_provider_does_not_reference_passport()
    {
        $reflection = new \ReflectionClass(\App\Providers\AppServiceProvider::class);
        $source = file_get_contents($reflection->getFileName());

        $this->assertStringNotContainsString('Passport', $source);
    }
}
