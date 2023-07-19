<?php

namespace Tests\Feature\Books;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectActiveBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_active_book_on_any_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['name' => 'Kegiatan Rutin']);

        $this->visitRoute('home');
        $this->see($book->name);
    }
}
