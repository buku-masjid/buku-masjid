<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
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
    public function user_cannot_see_category_list_from_other_books()
    {
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $nonVisibleCategory = factory(Category::class)->create(['name' => 'Non visible category', 'book_id' => $inActiveBook->id]);
        $activeBook = factory(Book::class)->create(['creator_id' => $user->id]);
        $visibleCategory = factory(Category::class)->create(['name' => 'Visible category', 'book_id' => $activeBook->id]);

        session()->put('active_book_id', $activeBook->id);

        $this->visit(route('categories.index'));
        $this->see('Visible category');
        $this->dontSee('Non visible category');
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $this->visit(route('categories.index'));

        $this->click(__('category.create'));
        $this->seePageIs(route('categories.index', ['action' => 'create']));
        $this->submitForm(__('category.create'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'book_id' => $book->id,
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'status_id' => Category::STATUS_ACTIVE,
            'book_id' => $book->id,
            'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC,
        ]);
    }

    /** @test */
    public function user_cannot_create_a_category_on_an_inactive_book()
    {
        $book = factory(Book::class)->create();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);

        $this->loginAsUser();
        session()->put('active_book_id', $inActiveBook->id);

        $this->visitRoute('categories.index');
        $this->dontSeeElement('a', ['href' => route('categories.index', ['action' => 'create'])]);

        $this->visitRoute('categories.index', ['action' => 'create']);
        $this->dontSeeElement('input', ['value' => __('category.create')]);
    }

    /** @test */
    public function user_can_create_a_category_for_active_book()
    {
        $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create();
        $activeBook = factory(Book::class)->create();
        session()->put('active_book_id', $activeBook->id);
        $this->visit(route('categories.index'));

        $this->click(__('category.create'));
        $this->seePageIs(route('categories.index', ['action' => 'create']));
        $this->submitForm(__('category.create'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'book_id' => $activeBook->id,
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'status_id' => Category::STATUS_ACTIVE,
            'book_id' => $activeBook->id,
            'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC,
        ]);
    }

    /** @test */
    public function category_index_page_does_not_have_any_edit_link()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.index'));
        $this->see($category->name);
        $this->dontSeeElement('a', ['id' => 'edit-category-'.$category->id]);

        $this->visitRoute('categories.index', ['action' => 'edit', 'id' => $category->id]);
        $this->dontSeeElement('input', ['value' => __('category.update')]);
    }

    /** @test */
    public function user_can_see_the_edit_link_on_the_category_detail_page()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.show', $category));
        $this->seeElement('a', [
            'id' => 'edit-category-'.$category->id,
            'href' => route('categories.show', ['category' => $category->id, 'action' => 'edit']),
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_from_the_category_detail_page()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.show', $category));
        $this->click('edit-category-'.$category->id);
        $this->seePageIs(route('categories.show', ['category' => $category->id, 'action' => 'edit']));
        $this->seeElement('input', ['value' => __('category.update')]);

        $this->submitForm(__('category.update'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'status_id' => Category::STATUS_ACTIVE,
            'book_id' => $category->book_id,
            'report_visibility_code' => Category::REPORT_VISIBILITY_INTERNAL,
        ]);

        $this->seePageIs(route('categories.show', $category));

        $this->seeInDatabase('categories', [
            'id' => $category->id,
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'status_id' => Category::STATUS_ACTIVE,
            'book_id' => $category->book_id,
            'report_visibility_code' => Category::REPORT_VISIBILITY_INTERNAL,
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_status_from_the_category_detail_page()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.show', ['category' => $category->id, 'action' => 'edit']));
        $this->submitForm(__('category.update'), [
            'name' => 'Category 1 name',
            'description' => 'Category 1 description',
            'color' => '#00AABB',
            'status_id' => Category::STATUS_INACTIVE,
            'book_id' => $category->book_id,
        ]);

        $this->seePageIs(route('categories.show', $category));

        $this->seeInDatabase('categories', [
            'id' => $category->id,
            'name' => 'Category 1 name',
            'status_id' => Category::STATUS_INACTIVE,
            'book_id' => $category->book_id,
        ]);
    }

    /** @test */
    public function user_cannot_edit_a_category_on_an_inactive_book()
    {
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);
        $category = factory(Category::class)->create(['creator_id' => $user->id, 'book_id' => $inActiveBook]);

        $this->loginAsUser();
        session()->put('active_book_id', $inActiveBook->id);

        $this->visitRoute('categories.show', $category);
        $this->dontSeeElement('a', ['id' => 'edit-category-'.$category->id]);

        $this->visitRoute('categories.show', ['category' => $category->id, 'action' => 'edit']);
        $this->dontSeeElement('input', ['value' => __('category.update')]);
    }

    /** @test */
    public function user_can_delete_a_category_with_all_corresponding_transactions()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        factory(Transaction::class)->create(['category_id' => $category->id, 'creator_id' => $user->id]);

        $this->visit(route('categories.show', ['category' => $category->id, 'action' => 'edit']));
        $this->click('del-category-'.$category->id);
        $this->seePageIs(route('categories.show', ['category' => $category->id, 'action' => 'delete']));

        $this->seeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->submitForm(__('app.delete_confirm_button'), [
            'category_id' => $category->id,
            'delete_transactions' => 1,
        ]);

        $this->seePageIs(route('categories.index'));

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

        $this->visit(route('categories.show', ['category' => $category->id, 'action' => 'delete']));

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

    /** @test */
    public function user_cannot_delete_a_category_on_an_inactive_book()
    {
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);
        $category = factory(Category::class)->create(['creator_id' => $user->id, 'book_id' => $inActiveBook]);

        $this->loginAsUser();
        session()->put('active_book_id', $inActiveBook->id);

        $this->visitRoute('categories.show', ['category' => $category->id, 'action' => 'delete']);
        $this->dontSeeElement('input', ['value' => __('app.delete_confirm_button')]);
    }
}
