<?php

namespace Tests\Unit\Policies;

use App\Models\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankAccountPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_bank_account()
    {
        $admin = $this->createUser('admin');
        $this->assertTrue($admin->can('create', new BankAccount));
    }

    /** @test */
    public function user_can_only_view_their_own_bank_account_detail()
    {
        $admin = $this->createUser('admin');
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('view', $bankAccount));
        $this->assertFalse($admin->can('view', $othersBankAccount));
    }

    /** @test */
    public function user_can_only_update_their_own_bank_account()
    {
        $admin = $this->createUser('admin');
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('update', $bankAccount));
        $this->assertFalse($admin->can('update', $othersBankAccount));
    }

    /** @test */
    public function user_can_only_delete_their_own_bank_account()
    {
        $admin = $this->createUser('admin');
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('delete', $bankAccount));
        $this->assertFalse($admin->can('delete', $othersBankAccount));
    }
}
