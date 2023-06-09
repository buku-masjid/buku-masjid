<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_category_list_in_category_index_page()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.index'));
        $this->see($category->name);
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $this->loginAsUser();
        $this->visit(route('categories.index'));

        $this->click(__('category.create'));
        $this->seePageIs(route('categories.index', ['action' => 'create']));

        $this->submitForm(__('category.create'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
            'status_id' => Category::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_within_search_query()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.index'));
        $this->click('edit-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'edit', 'id' => $category->id]));

        $this->submitForm(__('category.update'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
            'status_id' => Category::STATUS_ACTIVE,
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
            'status_id' => Category::STATUS_ACTIVE,
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_status()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.index'));
        $this->click('edit-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'edit', 'id' => $category->id]));

        $this->submitForm(__('category.update'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
            'status_id' => Category::STATUS_INACTIVE,
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00aabb',
            'status_id' => Category::STATUS_INACTIVE,
        ]);
    }

    /** @test */
    public function user_can_delete_a_category_with_all_corresponding_transactions()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        factory(Transaction::class)->create(['category_id' => $category->id, 'creator_id' => $user->id]);

        $this->visit(route('categories.index', ['action' => 'edit', 'id' => $category->id]));
        $this->click('del-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'delete', 'id' => $category->id]));

        $this->seeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->submitForm(__('app.delete_confirm_button'), [
            'category_id' => $category->id,
            'delete_transactions' => 1,
        ]);

        $this->dontSeeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->dontSeeInDatabase('transactions', [
            'category_id' => $category->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_category_without_deleting_any_transactions()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create(['category_id' => $category->id, 'creator_id' => $user->id]);

        $this->visit(route('categories.index', ['action' => 'delete', 'id' => $category->id]));

        $this->submitForm(__('app.delete_confirm_button'), [
            'category_id' => $category->id,
        ]);

        $this->dontSeeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
            'category_id' => null,
        ]);
    }
}
