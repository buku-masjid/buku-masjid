<?php

namespace Tests\Unit\Models;

use App\Category;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_has_belongs_to_creator_relation()
    {
        $category = factory(Category::class)->make();

        $this->assertInstanceOf(User::class, $category->creator);
        $this->assertEquals($category->creator_id, $category->creator->id);
    }

    /** @test */
    public function a_category_has_for_user_global_scope()
    {
        $categoryOwner = $this->loginAsUser();
        $category = factory(Category::class)->create([
            'creator_id' => $categoryOwner->id,
        ]);
        $othersCategory = factory(Category::class)->create();

        $this->assertCount(1, Category::get());
    }

    /** @test */
    public function a_category_has_many_transactions_relation()
    {
        $categoryOwner = $this->loginAsUser();
        $category = factory(Category::class)->create([
            'creator_id' => $categoryOwner->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'category_id' => $category->id,
            'creator_id' => $categoryOwner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $category->transactions);
        $this->assertInstanceOf(Transaction::class, $category->transactions->first());
    }

    /** @test */
    public function a_category_has_name_label_attribute()
    {
        $category = factory(Category::class)->make();

        $nameLabel = '<span class="badge" style="background-color: '.$category->color.'">'.$category->name.'</span>';
        $this->assertEquals($nameLabel, $category->name_label);
    }

    /** @test */
    public function a_category_has_status_attribute()
    {
        $category = factory(Category::class)->make();
        $this->assertEquals(__('app.active'), $category->status);

        $category->status_id = Category::STATUS_INACTIVE;
        $this->assertEquals(__('app.inactive'), $category->status);
    }
}
