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
    public function user_can_view_book_detail()
    {
        $user = $this->createUser();
        $ownedBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($user->can('view', $ownedBook));
        $this->assertTrue($user->can('view', $othersBook));
        $this->assertTrue($user->can('view', $systemsBook));
    }

    /** @test */
    public function user_can_update_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($user->can('update', $book));
        $this->assertTrue($user->can('update', $othersBook));
        $this->assertTrue($user->can('update', $systemsBook));
    }

    /** @test */
    public function user_can_only_delete_their_own_book()
    {
        $user = $this->createUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($user->can('delete', $book));
        $this->assertFalse($user->can('delete', $othersBook));
        $this->assertFalse($user->can('delete', $systemsBook));
    }
}
