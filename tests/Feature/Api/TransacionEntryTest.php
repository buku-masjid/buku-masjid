<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TransacionEntryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->postJson(route('api.transactions.store'), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'book_id' => $book->id,
        ]);

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'book_id' => $book->id,
        ]);

        $this->seeStatusCode(201);
        $this->seeJson([
            'message' => __('transaction.income_added'),
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category' => $category->name,
            'book_id' => $book->id,
        ]);
    }

    /** @test */
    public function user_can_create_an_spending_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->createUser();
        Passport::actingAs($user);
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->postJson(route('api.transactions.store'), [
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category_id' => $category->id,
            'book_id' => $book->id,
        ]);

        $this->seeInDatabase('transactions', [
            'in_out' => 0, // 0:spending, 1:spending
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category_id' => $category->id,
            'book_id' => $book->id,
        ]);

        $this->seeStatusCode(201);
        $this->seeJson([
            'message' => __('transaction.spending_added'),
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category' => $category->name,
            'book_id' => $book->id,
        ]);
    }

    /** @test */
    public function user_can_edit_a_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->createUser();
        Passport::actingAs($user);
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'creator_id' => $user->id,
        ]);
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->patchJson(route('api.transactions.update', $transaction), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category_id' => $category->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('transaction.updated'),
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category' => $category->name,
        ]);

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:spending
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $transaction = factory(Transaction::class)->create([
            'creator_id' => $user->id,
        ]);

        $this->deleteJson(route('api.transactions.destroy', $transaction), [
            'transaction_id' => $transaction->id,
        ]);

        $this->seeStatusCode(200);
        $this->seeJson([
            'message' => __('transaction.deleted'),
        ]);

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
