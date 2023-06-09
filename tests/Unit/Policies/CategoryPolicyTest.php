<?php

namespace Tests\Unit\Policies;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_category()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Category));
    }

    /** @test */
    public function user_can_only_view_their_own_category_detail()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($user->can('view', $category));
        $this->assertFalse($user->can('view', $othersCategory));
    }

    /** @test */
    public function user_can_only_update_their_own_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($user->can('update', $category));
        $this->assertFalse($user->can('update', $othersCategory));
    }

    /** @test */
    public function user_can_only_delete_their_own_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($user->can('delete', $category));
        $this->assertFalse($user->can('delete', $othersCategory));
    }
}
