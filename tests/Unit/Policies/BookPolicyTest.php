<?php

namespace Tests\Unit\Policies;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_book()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Book));
    }

    /** @test */
    public function user_view_book_detail()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create();
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('view', $book));
        $this->assertTrue($user->can('view', $othersBook));
    }

    /** @test */
    public function user_can_update_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create();
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('update', $book));
        $this->assertTrue($user->can('update', $othersBook));
    }

    /** @test */
    public function user_can_delete_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create();
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('delete', $book));
        $this->assertTrue($user->can('delete', $othersBook));
    }
}
