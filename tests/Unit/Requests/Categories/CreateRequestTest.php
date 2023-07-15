<?php

namespace Tests\Unit\Requests\Categories;

use App\Http\Requests\Categories\CreateRequest as CategoryCreateRequest;
use App\Models\Book;
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
        $this->assertValidationPasses(new CategoryCreateRequest(), $this->getCreateAttributes(['book_id' => $book->id]));
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $this->assertValidationFails(new CategoryCreateRequest(), [], function ($errors) {
            $this->assertCount(3, $errors);
            $this->assertEquals(__('validation.required'), $errors->first('book_id'));
            $this->assertEquals(__('validation.required'), $errors->first('name'));
            $this->assertEquals(__('validation.required'), $errors->first('color'));
        });
    }

    /** @test */
    public function it_fails_if_name_is_more_than_60_characters()
    {
        $attributes = $this->getCreateAttributes([
            'name' => str_repeat('Category description.', 3),
        ]);

        $this->assertValidationFails(new CategoryCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'name', 'max' => 60]),
                $errors->first('name')
            );
        });
    }

    /** @test */
    public function it_fails_if_color_is_more_than_7_characters()
    {
        $attributes = $this->getCreateAttributes([
            'color' => '#aabbccdd',
        ]);

        $this->assertValidationFails(new CategoryCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'color', 'max' => 7]),
                $errors->first('color')
            );
        });
    }

    /** @test */
    public function it_fails_if_description_is_more_than_255_characters()
    {
        $attributes = $this->getCreateAttributes([
            'description' => str_repeat('Category description.', 13),
        ]);

        $this->assertValidationFails(new CategoryCreateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'description', 'max' => 255]),
                $errors->first('description')
            );
        });
    }

    private function getCreateAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Category Name',
            'color' => '#aabbcc',
            'description' => 'Category description.',
        ], $overrides);
    }
}
