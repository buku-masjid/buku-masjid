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
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new BankAccount));
    }

    /** @test */
    public function user_can_only_view_their_own_bank_account_detail()
    {
        $user = $this->createUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $user->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($user->can('view', $bankAccount));
        $this->assertFalse($user->can('view', $othersBankAccount));
    }

    /** @test */
    public function user_can_only_update_their_own_bank_account()
    {
        $user = $this->createUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $user->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($user->can('update', $bankAccount));
        $this->assertFalse($user->can('update', $othersBankAccount));
    }

    /** @test */
    public function user_can_only_delete_their_own_bank_account()
    {
        $user = $this->createUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $user->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($user->can('delete', $bankAccount));
        $this->assertFalse($user->can('delete', $othersBankAccount));
    }
}
