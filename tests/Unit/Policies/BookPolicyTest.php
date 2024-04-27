<?php

namespace Tests\Unit\Policies;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_book_list()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('view-any', new Book));
        $this->assertTrue($chairman->can('view-any', new Book));
        $this->assertTrue($secretary->can('view-any', new Book));
        $this->assertTrue($finance->can('view-any', new Book));
    }

    /** @test */
    public function user_can_create_book()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new Book));
        $this->assertFalse($chairman->can('create', new Book));
        $this->assertFalse($secretary->can('create', new Book));
        $this->assertTrue($finance->can('create', new Book));
    }

    /** @test */
    public function user_can_see_book_details()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($admin->can('view', $book));
        $this->assertTrue($admin->can('view', $othersBook));
        $this->assertTrue($chairman->can('view', $book));
        $this->assertTrue($secretary->can('view', $book));
        $this->assertTrue($finance->can('view', $book));
    }

    /** @test */
    public function admin_and_finance_user_can_update_book()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($admin->can('update', $book));
        $this->assertTrue($admin->can('update', $othersBook));
        $this->assertFalse($chairman->can('update', $book));
        $this->assertFalse($secretary->can('update', $book));
        $this->assertFalse($finance->can('update', $book));
    }

    /** @test */
    public function finance_user_can_update_book_assigned_to_them()
    {
        $admin = $this->createUser('admin');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id, 'manager_id' => $finance->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($admin->can('update', $book));
        $this->assertTrue($finance->can('update', $book));
        $this->assertFalse($finance->can('update', $othersBook));
    }

    /** @test */
    public function only_admin_can_update_book_manager_attribute()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id, 'manager_id' => $finance->id]);
        $othersBook = factory(Book::class)->create();

        $this->assertTrue($admin->can('change-manager', $book));
        $this->assertTrue($admin->can('change-manager', $othersBook));
        $this->assertFalse($chairman->can('change-manager', $book));
        $this->assertFalse($secretary->can('change-manager', $book));
        $this->assertFalse($finance->can('change-manager', $book));
    }

    /** @test */
    public function admin_can_delete_book()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $adminBook = factory(Book::class)->create(['creator_id' => $admin->id]);
        $financeBook = factory(Book::class)->create(['creator_id' => $finance->id]);
        $systemBook = factory(Book::class)->create(['creator_id' => null]);

        $this->assertTrue($admin->can('delete', $adminBook));
        $this->assertTrue($admin->can('delete', $financeBook));
        $this->assertFalse($admin->can('delete', $systemBook));

        $this->assertFalse($chairman->can('delete', $adminBook));

        $this->assertFalse($secretary->can('delete', $adminBook));

        $this->assertFalse($finance->can('delete', $adminBook));
        $this->assertFalse($finance->can('delete', $financeBook));
        $this->assertFalse($finance->can('delete', $systemBook));
    }

    /** @test */
    public function user_can_manage_transactions_on_active_book()
    {
        $admin = $this->createUser('admin');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id, 'manager_id' => $finance->id]);
        $otherBook = factory(Book::class)->create(['creator_id' => $admin->id]);

        $this->assertTrue($admin->can('manage-transactions', $book));
        $this->assertTrue($finance->can('manage-transactions', $book));
        $this->assertFalse($finance->can('manage-transactions', $otherBook));

        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE, 'manager_id' => $finance->id]);

        $this->assertFalse($admin->can('manage-transactions', $inActiveBook));
        $this->assertFalse($finance->can('manage-transactions', $inActiveBook));
    }

    /** @test */
    public function user_can_manage_categories_on_active_book()
    {
        $admin = $this->createUser('admin');
        $finance = $this->createUser('finance');

        $book = factory(Book::class)->create(['creator_id' => $admin->id, 'manager_id' => $finance->id]);
        $otherBook = factory(Book::class)->create(['creator_id' => $admin->id]);

        $this->assertTrue($admin->can('manage-categories', $book));
        $this->assertTrue($finance->can('manage-categories', $book));
        $this->assertFalse($finance->can('manage-categories', $otherBook));

        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE, 'manager_id' => $finance->id]);

        $this->assertFalse($admin->can('manage-categories', $inActiveBook));
        $this->assertFalse($finance->can('manage-categories', $inActiveBook));
    }
}
