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
        $this->assertTrue($admin->can('create', new Category));
    }

    /** @test */
    public function user_can_only_view_their_own_category_detail()
    {
        $admin = $this->createUser('admin');
        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('view', $category));
        $this->assertFalse($admin->can('view', $othersCategory));
    }

    /** @test */
    public function user_can_only_update_their_own_category()
    {
        $admin = $this->createUser('admin');
        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('update', $category));
        $this->assertFalse($admin->can('update', $othersCategory));
    }

    /** @test */
    public function user_can_only_delete_their_own_category()
    {
        $admin = $this->createUser('admin');
        $category = factory(Category::class)->create(['creator_id' => $admin->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($admin->can('delete', $category));
        $this->assertFalse($admin->can('delete', $othersCategory));
    }
}
