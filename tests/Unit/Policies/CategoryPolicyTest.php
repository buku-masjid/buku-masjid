<?php

namespace Tests\Unit\Policies;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_category()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new Category));
        $this->assertFalse($chairman->can('create', new Category));
        $this->assertFalse($secretary->can('create', new Category));
        $this->assertTrue($finance->can('create', new Category));
    }

    /** @test */
    public function user_can_see_category_details()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('view', $category));
        $this->assertTrue($admin->can('view', $othersCategory));
        $this->assertTrue($chairman->can('view', $category));
        $this->assertTrue($secretary->can('view', $category));
        $this->assertTrue($finance->can('view', $category));
    }

    /** @test */
    public function admin_and_finance_can_update_category()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('update', $category));
        $this->assertTrue($admin->can('update', $othersCategory));
        $this->assertFalse($chairman->can('update', $category));
        $this->assertFalse($secretary->can('update', $category));
        $this->assertTrue($finance->can('update', $category));
    }

    /** @test */
    public function admin_and_finance_can_delete_category()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('delete', $category));
        $this->assertTrue($admin->can('delete', $othersCategory));
        $this->assertFalse($chairman->can('delete', $category));
        $this->assertFalse($secretary->can('delete', $category));
        $this->assertTrue($finance->can('delete', $category));
    }
}
