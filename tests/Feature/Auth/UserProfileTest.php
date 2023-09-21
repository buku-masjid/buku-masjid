<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_visit_their_profile_page()
    {
        $user = $this->loginAsUser();
        $this->visitRoute('profile.show');
        $this->see($user->name);
        $this->see($user->email);
    }

    /** @test */
    public function user_can_update_their_profile_data()
    {
        $user = $this->loginAsUser();

        $this->visitRoute('profile.show');
        $this->click(__('user.profile_edit'));
        $this->seeRouteIs('profile.edit');
        $this->submitForm(__('user.profile_update'), [
            'name' => 'User Baru',
            'email' => 'user3@mail.com',
        ]);

        $this->seeRouteIs('profile.show');
        $this->seeText(__('user.profile_updated'));
        $this->seeText('User Baru');

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'name' => 'User Baru',
            'email' => 'user3@mail.com',
        ]);
    }
}
