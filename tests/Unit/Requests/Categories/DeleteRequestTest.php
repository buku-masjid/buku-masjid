<?php

namespace Tests\Unit\Requests\Categories;

use App\Http\Requests\Categories\DeleteRequest as CategoryDeleteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\ValidateFormRequest;

class DeleteRequestTest extends TestCase
{
    use RefreshDatabase, ValidateFormRequest;

    /** @test */
    public function it_pass_for_required_attributes()
    {
        $this->assertValidationPasses(new CategoryDeleteRequest(), $this->getDeleteAttributes());
    }

    /** @test */
    public function it_fails_for_empty_attributes()
    {
        $this->assertValidationFails(new CategoryDeleteRequest(), [], function ($errors) {
            $this->assertCount(1, $errors);
            $this->assertEquals(__('validation.required'), $errors->first('category_id'));
        });
    }

    private function getDeleteAttributes($overrides = [])
    {
        return array_merge([
            'category_id' => '1',
            'delete_transactions' => '1',
        ], $overrides);
    }
}
