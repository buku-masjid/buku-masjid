<?php

namespace Tests\Feature\Books;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_book_list_in_book_index_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('books.index');
        $this->see($book->name);
    }

    /** @test */
    public function user_can_create_a_book()
    {
        $this->loginAsUser();
        $this->visitRoute('books.index');

        $this->click(__('book.create'));
        $this->seeRouteIs('books.index', ['action' => 'create']);

        $this->submitForm(__('book.create'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);

        $this->seeRouteIs('books.index');

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
    }

    /** @test */
    public function user_can_create_a_book_with_a_bank_account()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $this->loginAsUser();
        $this->visitRoute('books.index');

        $this->click(__('book.create'));
        $this->seeRouteIs('books.index', ['action' => 'create']);

        $this->submitForm(__('book.create'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('books.index');

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'bank_account_id' => $bankAccount->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
    }

    /** @test */
    public function user_can_edit_a_book()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $user->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
        $bankAccount = factory(BankAccount::class)->create();

        $this->visitRoute('books.index');
        $this->click('edit-book-'.$book->id);
        $this->seeRouteIs('books.index', ['action' => 'edit', 'id' => $book->id]);

        $this->submitForm(__('book.update'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'bank_account_id' => $bankAccount->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
        ]);

        $this->seeRouteIs('books.index');

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'bank_account_id' => $bankAccount->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
        ]);
    }

    /** @test */
    public function user_can_delete_a_book()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        factory(Book::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('books.index', ['action' => 'edit', 'id' => $book->id]);
        $this->click('del-book-'.$book->id);
        $this->seeRouteIs('books.index', ['action' => 'delete', 'id' => $book->id]);

        $this->seeInDatabase('books', [
            'id' => $book->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('books', [
            'id' => $book->id,
        ]);
    }

    /** @test */
    public function book_deletion_will_also_deletes_its_transactions_and_categories()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        factory(Transaction::class)->create(['book_id' => $book->id]);
        factory(Category::class)->create(['book_id' => $book->id]);

        $this->visitRoute('books.index', ['action' => 'edit', 'id' => $book->id]);
        $this->click('del-book-'.$book->id);
        $this->seeRouteIs('books.index', ['action' => 'delete', 'id' => $book->id]);

        $this->seeInDatabase('books', ['id' => $book->id]);
        $this->seeInDatabase('categories', ['book_id' => $book->id]);
        $this->seeInDatabase('transactions', ['book_id' => $book->id]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('books', ['id' => $book->id]);
        $this->dontSeeInDatabase('categories', ['book_id' => $book->id]);
        $this->dontSeeInDatabase('transactions', ['book_id' => $book->id]);
    }
}
