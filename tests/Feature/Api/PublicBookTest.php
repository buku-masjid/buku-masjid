<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_list_of_public_books_with_keyword()
    {
        $publicBook1 = factory(Book::class)->create([
            'report_visibility_code' => 'public',
            'status_id' => 1,
            'name' => 'Book 1',
        ]);

        $publicBook2 = factory(Book::class)->create([
            'report_visibility_code' => 'public',
            'status_id' => 0,
            'name' => 'Book 2',
        ]);

        $nonPublicBook = factory(Book::class)->create([
            'report_visibility_code' => 'internal',
            'status_id' => 1,
            'name' => 'Non-Public Book',
        ]);

        $this->getJson(route('api.public_books.index', [
            'search' => 'Book 1',
        ]));

        $this->seeJson([
            'name' => $publicBook1->name,
        ]);

        $this->dontSeeJson([
            'name' => $publicBook2->name,
        ]);

        $this->dontSeeJson([
            'name' => $nonPublicBook->name,
        ]);
    }

    /** @test */
    public function can_get_list_of_public_books_without_keyword()
    {
        $publicBook1 = factory(Book::class)->create([
            'report_visibility_code' => 'public',
            'status_id' => 1,
            'name' => 'Book 1',
        ]);

        $publicBook2 = factory(Book::class)->create([
            'report_visibility_code' => 'public',
            'status_id' => 0,
            'name' => 'Book 2',
        ]);

        $nonPublicBook = factory(Book::class)->create([
            'report_visibility_code' => 'internal',
            'status_id' => 1,
            'name' => 'Non-Public Book',
        ]);

        $this->getJson(route('api.public_books.index'));

        $this->seeJson([
            'name' => $publicBook1->name,
        ]);

        $this->SeeJson([
            'name' => $publicBook2->name,
        ]);

        $this->dontSeeJson([
            'name' => $nonPublicBook->name,
        ]);
    }
}
