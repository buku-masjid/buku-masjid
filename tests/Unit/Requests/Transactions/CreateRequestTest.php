<?php

namespace Tests\Unit\Requests\Transactions;

use App\Http\Requests\Transactions\CreateRequest as TransactionCreateRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ValidateFormRequest;

class CreateRequestTest extends TestCase
{
    use RefreshDatabase, ValidateFormRequest;

    /** @test */
    public function it_pass_for_required_attributes()
    {
        $book = factory(Book::class)->create();
        $this->assertValidationPasses(
            new TransactionCreateRequest(), $this->getCreateAttributes(['book_id' => $book->id])
        );
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $this->assertValidationFails(new TransactionCreateRequest(), [], function ($errors) {
            $this->assertCount(5, $errors);
            $this->assertEquals(__('validation.required'), $errors->first('book_id'));
            $this->assertEquals(__('validation.required'), $errors->first('date'));
            $this->assertEquals(__('validation.required'), $errors->first('in_out'));
            $this->assertEquals(__('validation.required'), $errors->first('amount'));
            $this->assertEquals(__('validation.required'), $errors->first('description'));
        });
    }

    /** @test */
    public function it_fails_if_description_is_more_than_255_characters()
    {
        $attributes = $this->getCreateAttributes([
            'description' => str_repeat('Transaction description.', 11),
        ]);

        $this->assertValidationFails(new TransactionCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'description', 'max' => 255]),
                $errors->first('description')
            );
        });
    }

    /** @test */
    public function it_fails_if_in_out_filled_with_non_boolean_value()
    {
        $attributes = $this->getCreateAttributes(['in_out' => '2']);

        $this->assertValidationFails(new TransactionCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.boolean', ['attribute' => 'in out']),
                $errors->first('in_out')
            );
        });

        $attributes = $this->getCreateAttributes(['in_out' => 'text']);
        $this->assertValidationFails(new TransactionCreateRequest(), $attributes);
    }

    /** @test */
    public function it_pass_for_user_category_selection()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        $attributes = $this->getCreateAttributes([
            'book_id' => $book->id,
            'category_id' => $category->id,
        ]);

        $this->assertValidationPasses(new TransactionCreateRequest(), $attributes);
    }

    /** @test */
    public function it_fails_if_selected_category_does_not_exists()
    {
        $attributes = $this->getCreateAttributes(['category_id' => 999]);

        $this->assertValidationFails(new TransactionCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.exists', ['attribute' => 'category id']),
                $errors->first('category_id')
            );
        });
    }

    /** @test */
    public function it_passes_if_selected_category_that_belongs_to_other_user()
    {
        $category = factory(Category::class)->create();
        $book = factory(Book::class)->create();
        $attributes = $this->getCreateAttributes([
            'book_id' => $book->id,
            'category_id' => $category->id,
        ]);

        $this->assertValidationPasses(new TransactionCreateRequest(), $attributes);
    }

    private function getCreateAttributes($overrides = [])
    {
        return array_merge([
            'date' => '2018-03-03',
            'amount' => '150000',
            'in_out' => '1', // 0:spending, 1:income
            'description' => 'Transaction description.',
        ], $overrides);
    }
}
