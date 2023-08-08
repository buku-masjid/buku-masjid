<?php

namespace Tests\Unit\Models;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_has_belongs_to_creator_relation()
    {
        $book = factory(Book::class)->make();

        $this->assertInstanceOf(User::class, $book->creator);
        $this->assertEquals($book->creator_id, $book->creator->id);
    }

    /** @test */
    public function a_book_can_be_belongs_to_system()
    {
        $book = factory(Book::class)->create(['creator_id' => null]);

        $this->assertInstanceOf(User::class, $book->creator);
        $this->assertFalse($book->creator->exists);
        $this->assertEquals(__('app.system'), $book->creator->name);
    }

    /** @test */
    public function book_model_has_has_many_categories_relation()
    {
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id]);

        $this->assertInstanceOf(Collection::class, $book->categories);
        $this->assertInstanceOf(Category::class, $book->categories->first());
    }

    /** @test */
    public function a_book_has_many_transactions_relation()
    {
        $bookOwner = $this->loginAsUser();
        $book = factory(Book::class)->create([
            'creator_id' => $bookOwner->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'book_id' => $book->id,
            'creator_id' => $bookOwner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $book->transactions);
        $this->assertInstanceOf(Transaction::class, $book->transactions->first());
    }

    /** @test */
    public function a_book_has_name_label_attribute()
    {
        $book = factory(Book::class)->make();

        $nameLabel = '<span class="badge badge-pill badge-secondary">'.$book->name.'</span>';
        $this->assertEquals($nameLabel, $book->name_label);
    }

    /** @test */
    public function a_book_has_status_attribute()
    {
        $book = factory(Book::class)->make();
        $this->assertEquals(__('app.active'), $book->status);

        $book->status_id = Book::STATUS_INACTIVE;
        $this->assertEquals(__('app.inactive'), $book->status);
    }

    /** @test */
    public function book_model_has_belongs_to_bank_account_relation()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $book = factory(Book::class)->create([
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->assertInstanceOf(BankAccount::class, $book->bankAccount);
        $this->assertEquals($book->bank_account_id, $book->bankAccount->id);
    }

    /** @test */
    public function book_model_might_have_no_bank_account()
    {
        $book = factory(Book::class)->create([
            'bank_account_id' => null,
        ]);

        $this->assertInstanceOf(BankAccount::class, $book->bankAccount);
        $this->assertFalse($book->bankAccount->exists);
        $this->assertEquals(__('book.no_bank_account'), $book->bankAccount->name);
    }

    /** @test */
    public function book_model_has_nonce_attribute()
    {
        $book = factory(Book::class)->create();
        $nonceString = sha1($book->id.config('app.key'));

        $this->assertEquals($nonceString, $book->nonce);
    }
}
