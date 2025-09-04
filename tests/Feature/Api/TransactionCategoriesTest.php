<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransactionCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_income_categories_for_income_transaction_type()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        
        // incomeCategory1
        factory(Category::class)->create([
            'name' => 'Donasi Masjid',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        // incomeCategory2
        factory(Category::class)->create([
            'name' => 'Infaq Jumat',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        
        factory(Category::class)->create([
            'name' => 'Biaya Listrik',
            'color' => config('masjid.spending_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1, // Income
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'color']
            ]
        ]);

        $this->seeJson(['name' => 'Donasi Masjid']);
        $this->seeJson(['name' => 'Infaq Jumat']);
        $this->seeJson(['color' => config('masjid.income_color')]);
        
        $this->dontSeeJson(['name' => 'Biaya Listrik']);
    }

    /** @test */
    public function it_returns_spending_categories_for_spending_transaction_type()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        
        // spendingCategory1
        factory(Category::class)->create([
            'name' => 'Biaya Listrik',
            'color' => config('masjid.spending_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        // spendingCategory2
        factory(Category::class)->create([
            'name' => 'Biaya Air',
            'color' => config('masjid.spending_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        
        factory(Category::class)->create([
            'name' => 'Donasi Masjid',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 0, // Spending
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'color']
            ]
        ]);

        $this->seeJson(['name' => 'Biaya Listrik']);
        $this->seeJson(['name' => 'Biaya Air']);
        $this->seeJson(['color' => config('masjid.spending_color')]);
        
        $this->dontSeeJson(['name' => 'Donasi Masjid']);
    }

    /** @test */
    public function it_returns_empty_array_when_no_categories_exist_for_transaction_type()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        
        factory(Category::class)->create([
            'name' => 'Donasi Masjid',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 0,
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        $this->seeJson(['data' => []]);
    }

    /** @test */
    public function it_returns_empty_array_when_no_categories_exist_for_book()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $otherBook = factory(Book::class)->create();
        
        factory(Category::class)->create([
            'name' => 'Other Book Category',
            'color' => config('masjid.income_color'),
            'book_id' => $otherBook->id,
            'creator_id' => $user->id,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1, // Income
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        $this->seeJson(['data' => []]);
    }

    /** @test */
    public function it_validates_required_in_out_parameter()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->get(route('api.transaction_categories', [
            'book_id' => $book->id,
            // Missing in_out
        ]), [
            'Accept' => 'application/json',
        ]);

        $this->assertResponseStatus(422);
    }

    /** @test */
    public function it_validates_in_out_parameter_values()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 2, // Invalid, 0 or 1
            'book_id' => $book->id,
        ]), [
            'Accept' => 'application/json',
        ]);

        $this->assertResponseStatus(422);
    }

    /** @test */
    public function it_validates_required_book_id_parameter()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1,
            // Missing book_id
        ]), [
            'Accept' => 'application/json',
        ]);

        $this->assertResponseStatus(422);
    }

    /** @test */
    public function it_validates_book_id_exists_in_database()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1,
            'book_id' => 99999,
        ]), [
            'Accept' => 'application/json',
        ]);

        $this->assertResponseStatus(422);
    }

    /** @test */
    public function it_only_returns_active_categories()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        
        // activeCategoryy
        factory(Category::class)->create([
            'name' => 'Active Income Category',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'status_id' => Category::STATUS_ACTIVE,
        ]);
        
        // inactiveCategory
        factory(Category::class)->create([
            'name' => 'Inactive Income Category',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'status_id' => Category::STATUS_INACTIVE,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1,
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        
        $this->seeJson(['name' => 'Active Income Category']);
        $this->dontSeeJson(['name' => 'Inactive Income Category']);
    }

    /** @test */
    public function it_returns_categories_sorted_by_name()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        
        factory(Category::class)->create([
            'name' => 'Zakat',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        factory(Category::class)->create([
            'name' => 'Donasi',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
        factory(Category::class)->create([
            'name' => 'Infaq',
            'color' => config('masjid.income_color'),
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);

        $this->get(route('api.transaction_categories', [
            'in_out' => 1, // Income
            'book_id' => $book->id,
        ]));

        $this->assertResponseOk();
        
        $this->seeJson(['name' => 'Donasi']);
        $this->seeJson(['name' => 'Infaq']);
        $this->seeJson(['name' => 'Zakat']);
    }
}
