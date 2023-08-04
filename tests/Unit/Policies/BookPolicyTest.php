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
        $admin = $this->createUser('admin');
        $this->assertTrue($admin->can('create', new Book));
    }

    /** @test */
    public function user_can_view_book_detail()
    {
        $admin = $this->createUser('admin');
        $ownedBook = factory(Book::class)->create(['creator_id' => $admin->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($admin->can('view', $ownedBook));
        $this->assertTrue($admin->can('view', $othersBook));
        $this->assertTrue($admin->can('view', $systemsBook));
    }

    /** @test */
    public function user_can_update_book()
    {
        $admin = $this->createUser('admin');
        $book = factory(Book::class)->create(['creator_id' => $admin->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($admin->can('update', $book));
        $this->assertTrue($admin->can('update', $othersBook));
        $this->assertTrue($admin->can('update', $systemsBook));
    }

    /** @test */
    public function user_can_only_delete_their_own_book()
    {
        $admin = $this->createUser('admin');
        $book = factory(Book::class)->create(['creator_id' => $admin->id]);
        $othersBook = factory(Book::class)->create();
        $systemsBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($admin->can('delete', $book));
        $this->assertFalse($admin->can('delete', $othersBook));
        $this->assertFalse($admin->can('delete', $systemsBook));
    }
}
