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
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new BankAccount));
        $this->assertFalse($chairman->can('create', new BankAccount));
        $this->assertFalse($secretary->can('create', new BankAccount));
        $this->assertTrue($finance->can('create', new BankAccount));
    }

    /** @test */
    public function user_can_see_bank_account_details()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('view', $bankAccount));
        $this->assertTrue($admin->can('view', $othersBankAccount));
        $this->assertTrue($chairman->can('view', $bankAccount));
        $this->assertTrue($secretary->can('view', $bankAccount));
        $this->assertTrue($finance->can('view', $bankAccount));
    }

    /** @test */
    public function admin_and_finance_can_update_bank_account()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('update', $bankAccount));
        $this->assertTrue($admin->can('update', $othersBankAccount));
        $this->assertFalse($chairman->can('update', $bankAccount));
        $this->assertFalse($secretary->can('update', $bankAccount));
        $this->assertTrue($finance->can('update', $bankAccount));
    }

    /** @test */
    public function admin_and_finance_can_delete_bank_account()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $admin->id]);
        $othersBankAccount = factory(BankAccount::class)->create();

        $this->assertTrue($admin->can('delete', $bankAccount));
        $this->assertTrue($admin->can('delete', $othersBankAccount));
        $this->assertFalse($chairman->can('delete', $bankAccount));
        $this->assertFalse($secretary->can('delete', $bankAccount));
        $this->assertTrue($finance->can('delete', $bankAccount));
    }
}
