<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Lecturing;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_user_list_in_user_index_page()
    {
        $user = $this->createUser();

        $this->loginAsUser();
        $this->visitRoute('users.index');
        $this->see($user->name);
    }

    private function getCreateFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'Username',
            'email' => 'user@email.com',
            'role_id' => User::ROLE_ADMIN,
        ], $overrides);
    }

    /** @test */
    public function user_can_create_a_user()
    {
        $this->loginAsUser();
        $this->visitRoute('users.index');

        $this->click(__('user.create'));
        $this->seeRouteIs('users.create');

        $this->submitForm(__('user.create'), $this->getCreateFields());

        $this->seeInDatabase('users', $this->getCreateFields([
            'is_active' => 1,
        ]));
    }

    /** @test */
    public function validate_user_name_is_required()
    {
        $this->loginAsUser();

        // name empty
        $this->post(route('users.store'), $this->getCreateFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_user_name_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // name 255 characters
        $this->post(route('users.store'), $this->getCreateFields([
            'name' => str_repeat('Test Title', 26),
        ]));
        $this->assertSessionHasErrors('name');
    }
    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'name' => 'User 1 name',
            'email' => 'user@email.com',
            'role_id' => User::ROLE_ADMIN,
            'is_active' => 1,
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_user()
    {
        $this->loginAsUser();
        $user = $this->createUser('finance', ['name' => 'Testing 123']);

        $this->visitRoute('users.show', $user);
        $this->click('edit-user-'.$user->id);
        $this->seeRouteIs('users.edit', $user);

        $this->submitForm(__('user.update'), $this->getEditFields());

        $this->seeRouteIs('users.show', $user);

        $this->seeInDatabase('users', $this->getEditFields([
            'id' => $user->id,
        ]));
    }

     /** @test */
     public function user_can_inactive_a_user()
     {
         $this->loginAsUser();
         $user = $this->createUser('finance', ['name' => 'Testing Inactive User']);

         $this->visitRoute('users.show', $user);
         $this->click('edit-user-'.$user->id);
         $this->seeRouteIs('users.edit', $user);

         $this->submitForm(__('user.update'), $this->getEditFields([
             'is_active' => 0,
         ]));

         $this->seeRouteIs('users.show', $user);

         $this->seeInDatabase('users', $this->getEditFields([
             'id' => $user->id,
             'is_active' => 0,
         ]));
     }

    /** @test */
    public function validate_user_name_update_is_required()
    {
        $this->loginAsUser();
        $user = $this->createUser(['name' => 'Testing 123']);

        // name empty
        $this->patch(route('users.update', $user), $this->getEditFields(['name' => '']));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function validate_user_name_update_is_not_more_than_255_characters()
    {
        $this->loginAsUser();
        $user = $this->createUser(['name' => 'Testing 123']);

        // name 255 characters
        $this->patch(route('users.update', $user), $this->getEditFields([
            'name' => str_repeat('Test Title', 26),
        ]));
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function user_can_delete_a_user_who_has_no_data_entries()
    {
        $this->loginAsUser();
        $user = $this->createUser();
        $this->createUser();

        $this->visitRoute('users.edit', $user);
        $this->click('del-user-'.$user->id);
        $this->seeRouteIs('users.edit', [$user, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('users', [
            'id' => $user->id,
        ]);
    }
    /** @test */
    public function user_cannot_delete_a_user_who_has_data_entries()
    {
        $this->loginAsUser();
        $user = $this->createUser();
        factory(Transaction::class)->create(['creator_id' => $user->id]);
        factory(Category::class)->create(['creator_id' => $user->id]);
        factory(Book::class)->create(['creator_id' => $user->id]);
        factory(Lecturing::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('users.edit', $user);
        $this->click('del-user-'.$user->id);
        $this->seeRouteIs('users.edit', [$user, 'action' => 'delete']);

        $this->dontSeeText(__('app.delete_confirm_button'));
        $this->seeInElement('div.card-body.text-danger', __('user.undeleteable'));
    }
}
