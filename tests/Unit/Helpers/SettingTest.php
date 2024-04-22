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
            'model_id' => null,
            'model_type' => null,
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
    }

    /** @test */
    public function setting_can_be_get()
    {
        DB::table('settings')->insert([
            'model_id' => null,
            'model_type' => null,
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
        Setting::for($user)->set('testing_key', 'testing_value');

        $this->seeInDatabase('settings', [
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
    }

    /** @test */
    public function setting_can_be_set_for_specific_model()
    {
        $user = factory(User::class)->create();
        Setting::for($user)->set('testing_key', 'testing_value');
        Setting::set('more_key', 'more_value');

        $this->seeInDatabase('settings', [
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
        $this->seeInDatabase('settings', [
            'model_id' => null,
            'model_type' => null,
            'key' => 'more_key',
            'value' => 'more_value',
        ]);
    }

    /** @test */
    public function setting_can_be_get_for_specific_model()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'model_id' => $user->getKey(),
            'model_type' => $user->getMorphClass(),
            'key' => 'testing_key',
            'value' => 'testing_value',
        ]);
        DB::table('settings')->insert([
            'model_id' => null,
            'model_type' => null,
            'key' => 'more_key',
            'value' => 'more_value',
        ]);

        $this->assertEquals('testing_value', Setting::for($user)->get('testing_key'));
        $this->assertEquals('more_value', Setting::get('more_key'));
    }

    /** @test */
    public function existing_setting_can_be_set_for_specific_user()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'old_value',
        ]);

        Setting::for($user)->set('testing_key', 'new_value');

        $this->seeInDatabase('settings', [
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'new_value',
        ]);

        $this->notSeeInDatabase('settings', [
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'old_value',
        ]);
    }

    /** @test */
    public function setting_can_be_retrieved_for_specific_user()
    {
        $user = factory(User::class)->create();
        DB::table('settings')->insert([
            'model_id' => factory(User::class)->create()->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'testing_value_1',
        ]);

        DB::table('settings')->insert([
            'model_id' => $user->id,
            'model_type' => 'users',
            'key' => 'testing_key',
            'value' => 'testing_value_2',
        ]);

        $this->assertEquals('testing_value_2', Setting::for($user)->get('testing_key'));
    }

    /** @test */
    public function setting_can_be_set_with_a_null_value()
    {
        Setting::set('testing_key', null);

        $this->seeInDatabase('settings', [
            'model_id' => null,
            'model_type' => null,
            'key' => 'testing_key',
            'value' => null,
        ]);
    }

    /** @test */
    public function setting_can_get_a_null_value()
    {
        DB::table('settings')->insert([
            'model_id' => null,
            'model_type' => null,
            'key' => 'testing_key',
            'value' => null,
        ]);

        $this->assertEquals(null, Setting::get('testing_key'));
    }
}
