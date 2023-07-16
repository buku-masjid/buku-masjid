<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ManageBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_book_list_in_book_index_page()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->getJson(route('api.books.index'));

        $this->seeJson(['name' => $book->name]);
    }

    /** @test */
    public function user_can_create_a_book()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $this->postJson(route('api.books.store'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);

        $this->seeStatusCode(201);
        $this->seeJson([
            'message' => __('book.created'),
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);
    }

    /** @test */
    public function user_can_update_a_book()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create(['name' => 'Testing 123', 'creator_id' => $user->id]);

        $this->patchJson(route('api.books.update', $book), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('book.updated'),
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
        ]);
    }

    /** @test */
    public function user_can_delete_a_book()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->deleteJson(route('api.books.destroy', $book), [
            'book_id' => $book->id,
        ]);

        $this->dontSeeInDatabase('books', [
            'id' => $book->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('book.deleted'),
        ]);
    }
}
