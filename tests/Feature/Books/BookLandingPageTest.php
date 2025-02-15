<?php

namespace Tests\Feature\Books;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookLandingPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_book_landing_page_content()
    {
        $adminUser = $this->loginAsUser();

        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
        ]);

        $this->visitRoute('books.show', [$book]);
        $this->seeElement('a', ['id' => 'landing_page']);
        $this->click('landing_page');
        $this->seeRouteIs('books.landing_page.show', [$book]);
        $this->seeElement('a', ['id' => 'edit_landing_page-book-'.$book->id]);
        $this->click('edit_landing_page-book-'.$book->id);
        $this->seeRouteIs('books.landing_page.edit', [$book]);

        $this->submitForm(__('app.save'), [
            'landing_page_content' => 'Book 1 content',
            'due_date' => '2024-05-01',
        ]);

        $this->seeRouteIs('books.landing_page.show', [$book]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'landing_page_content',
            'value' => 'Book 1 content',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'due_date',
            'value' => '2024-05-01',
        ]);
    }
}
