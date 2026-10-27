<?php

namespace Tests\Feature\Categories;

use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTransactionListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function category_transaction_list_shows_only_current_month_by_default()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        session()->put('active_book_id', $book->id);

        $this->createCategoryTransaction($book, $category, $user, today()->subDays(31)->format('Y-m-d'), 'Transaksi bulan lalu');
        $this->createCategoryTransaction($book, $category, $user, today()->format('Y-m-d'), 'Transaksi bulan ini');

        $this->visitRoute('categories.show', $category);

        $this->see('Transaksi bulan ini');
        $this->dontSee('Transaksi bulan lalu');
        $this->seeElement('a', ['id' => 'show-all-months']);
    }

    /** @test */
    public function user_can_see_all_months_of_category_transactions()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        session()->put('active_book_id', $book->id);

        $firstDate = today()->subDays(31)->format('Y-m-d');
        $lastDate = today()->format('Y-m-d');
        $this->createCategoryTransaction($book, $category, $user, $firstDate, 'Transaksi bulan lalu');
        $this->createCategoryTransaction($book, $category, $user, $lastDate, 'Transaksi bulan ini');

        $this->visitRoute('categories.show', $category);
        $this->click('show-all-months');

        $this->see('Transaksi bulan lalu');
        $this->see('Transaksi bulan ini');
        $this->seeElement('input', ['name' => 'start_date', 'value' => $firstDate]);
        $this->seeElement('input', ['name' => 'end_date', 'value' => $lastDate]);
    }

    /** @test */
    public function all_months_link_is_not_shown_for_category_without_transactions()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        session()->put('active_book_id', $book->id);

        $this->visitRoute('categories.show', $category);

        $this->dontSeeElement('a', ['id' => 'show-all-months']);
        $this->see(__('transaction.not_found'));
    }

    /** @test */
    public function all_months_only_shows_transactions_of_the_active_book()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $otherBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        session()->put('active_book_id', $book->id);

        $firstDate = today()->subDays(31)->format('Y-m-d');
        $this->createCategoryTransaction($book, $category, $user, $firstDate, 'Transaksi buku aktif');
        $this->createCategoryTransaction($otherBook, $category, $user, '2020-01-01', 'Transaksi buku lain');

        $this->visitRoute('categories.show', $category);
        $this->click('show-all-months');

        $this->see('Transaksi buku aktif');
        $this->dontSee('Transaksi buku lain');
        $this->seeElement('input', ['name' => 'start_date', 'value' => $firstDate]);
    }

    /** @test */
    public function all_months_link_preserves_the_search_query()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        session()->put('active_book_id', $book->id);

        $firstDate = today()->subDays(31)->format('Y-m-d');
        $this->createCategoryTransaction($book, $category, $user, $firstDate, 'Transaksi bulan lalu');
        $this->createCategoryTransaction($book, $category, $user, today()->format('Y-m-d'), 'Transaksi bulan ini');

        $this->visitRoute('categories.show', [$category, 'query' => 'lalu']);
        $this->click('show-all-months');

        $this->see('Transaksi bulan lalu');
        $this->dontSee('Transaksi bulan ini');
        $this->seeElement('input', ['name' => 'query', 'value' => 'lalu']);
        $this->seeElement('input', ['name' => 'start_date', 'value' => $firstDate]);
    }

    private function createCategoryTransaction(Book $book, Category $category, User $user, string $date, string $description)
    {
        return factory(Transaction::class)->create([
            'date' => $date,
            'description' => $description,
            'category_id' => $category->id,
            'book_id' => $book->id,
            'creator_id' => $user->id,
        ]);
    }
}
