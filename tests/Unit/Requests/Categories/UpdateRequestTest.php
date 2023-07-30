<?php

namespace Tests\Unit\Requests\Categories;

use App\Http\Requests\Categories\UpdateRequest as CategoryUpdateRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ValidateFormRequest;

class UpdateRequestTest extends TestCase
{
    use RefreshDatabase, ValidateFormRequest;

    /** @test */
    public function it_pass_for_required_attributes()
    {
        $book = factory(Book::class)->create();
        $this->assertValidationPasses(new CategoryUpdateRequest(), $this->getUpdateAttributes(['book_id' => $book->id]));
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $this->assertValidationFails(new CategoryUpdateRequest(), [], function ($errors) {
            $this->assertCount(5, $errors);
            $this->assertEquals(__('validation.required'), $errors->first('report_visibility_code'));
            $this->assertEquals(__('validation.required'), $errors->first('book_id'));
            $this->assertEquals(__('validation.required'), $errors->first('name'));
            $this->assertEquals(__('validation.required'), $errors->first('color'));
            $this->assertEquals(__('validation.required'), $errors->first('status_id'));
        });
    }

    /** @test */
    public function it_fails_if_name_is_more_than_60_characters()
    {
        $attributes = $this->getUpdateAttributes([
            'name' => str_repeat('Category description.', 3),
        ]);

        $this->assertValidationFails(new CategoryUpdateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'name', 'max' => 60]),
                $errors->first('name')
            );
        });
    }

    /** @test */
    public function it_fails_if_color_is_more_than_7_characters()
    {
        $attributes = $this->getUpdateAttributes([
            'color' => '#aabbccdd',
        ]);

        $this->assertValidationFails(new CategoryUpdateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'color', 'max' => 7]),
                $errors->first('color')
            );
        });
    }

    /** @test */
    public function it_fails_if_description_is_more_than_255_characters()
    {
        $attributes = $this->getUpdateAttributes([
            'description' => str_repeat('Category description.', 13),
        ]);

        $this->assertValidationFails(new CategoryUpdateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.max.string', ['attribute' => 'description', 'max' => 255]),
                $errors->first('description')
            );
        });
    }

    /** @test */
    public function it_fails_if_status_id_other_than_active_and_inactive()
    {
        $attributes = $this->getUpdateAttributes([
            'status_id' => 2,
        ]);

        $this->assertValidationFails(new CategoryUpdateRequest(), $attributes, function ($errors) {
            $this->assertEquals(
                __('validation.in', ['attribute' => 'status id']),
                $errors->first('status_id')
            );
        });
    }

    private function getUpdateAttributes($overrides = [])
    {
        return array_merge([
            'name' => 'Category Name',
            'color' => '#aabbcc',
            'description' => 'Category description.',
            'status_id' => Category::STATUS_ACTIVE,
            'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC,
        ], $overrides);
    }
}
