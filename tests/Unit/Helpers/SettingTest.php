<?php

namespace Tests\Unit\Helpers;

use App\User;
use Facades\App\Helpers\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function setting_can_be_set()
    {
        Setting::set('testing_key', 'testing_value');

        $this->seeInDatabase('settings', [
            'user_id' => null,
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
    }

    /** @test */
    public function setting_can_be_get()
    {
        DB::table('settings')->insert([
            'user_id' => null,
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);

        $this->assertEquals('testing_value', Setting::get('testing_key'));
    }

    /** @test */
    public function setting_can_has_default_value_if_value_not_exists()
    {
        $this->assertEquals('testing_value', Setting::get('testing_key', 'testing_value'));
    }

    /** @test */
    public function setting_can_be_set_for_specific_user()
    {
        $user = factory(User::class)->create();
        Setting::forUser($user)->set('testing_key', 'testing_value');

        $this->seeInDatabase('settings', [
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
    }

    /** @test */
    public function setting_can_be_set_for_specific_user_id()
    {
        $user = factory(User::class)->create();
        Setting::forUser($user->id)->set('testing_key', 'testing_value');

        $this->seeInDatabase('settings', [
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
    }

    /** @test */
    public function existing_setting_can_be_set_for_specific_user()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'old_value',
        ]);

        Setting::forUser($user)->set('testing_key', 'new_value');

        $this->seeInDatabase('settings', [
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'new_value',
        ]);

        $this->notSeeInDatabase('settings', [
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'old_value',
        ]);
    }

    /** @test */
    public function setting_can_be_retrieved_for_specific_user()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'user_id' => factory(User::class)->create()->id,
            'key' => 'testing_key',
            'value' => 'testing_value_1',
        ]);

        DB::table('settings')->insert([
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'testing_value_2',
        ]);

        $this->assertEquals('testing_value_2', Setting::forUser($user)->get('testing_key'));
    }

    /** @test */
    public function setting_can_be_retrieved_for_specific_user_id()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'user_id' => $user->id,
            'key' => 'testing_key',
            'value' => 'testing_value_1',
        ]);

        DB::table('settings')->insert([
            'user_id' => factory(User::class)->create()->id,
            'key' => 'testing_key',
            'value' => 'testing_value_2',
        ]);

        $this->assertEquals('testing_value_1', Setting::forUser($user->id)->get('testing_key'));
    }
}
