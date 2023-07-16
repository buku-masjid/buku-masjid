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
    public function user_can_only_view_their_own_book_detail()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('view', $book));
        $this->assertFalse($user->can('view', $othersBook));
    }

    /** @test */
    public function user_can_only_update_their_own_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('update', $book));
        $this->assertFalse($user->can('update', $othersBook));
    }

    /** @test */
    public function user_can_only_delete_their_own_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($user->can('delete', $book));
        $this->assertFalse($user->can('delete', $othersBook));
    }
}
