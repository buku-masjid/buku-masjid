<?php

namespace Tests\Unit\Models;

use App\Book;
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
    public function a_book_has_for_user_scope()
    {
        $bookOwner = $this->loginAsUser();
        $book = factory(Book::class)->create([
            'creator_id' => $bookOwner->id,
        ]);
        $othersBook = factory(Book::class)->create();

        $this->assertCount(1, Book::all());
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
}
