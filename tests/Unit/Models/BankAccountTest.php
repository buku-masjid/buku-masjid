<?php

namespace Tests\Unit\Models;

use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bank_account_has_status_attribute()
    {
        $bankAccount = factory(BankAccount::class)->make(['is_active' => 1]);
        $this->assertEquals(__('app.active'), $bankAccount->status);

        $bankAccount->is_active = 0;
        $this->assertEquals(__('app.inactive'), $bankAccount->status);
    }

    /** @test */
    public function bank_account_model_has_has_many_balances_relation()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $balance = factory(BankAccountBalance::class)->create(['bank_account_id' => $bankAccount->id]);

        $this->assertInstanceOf(Collection::class, $bankAccount->balances);
        $this->assertInstanceOf(BankAccountBalance::class, $bankAccount->balances->first());
    }

    /** @test */
    public function bank_account_model_has_has_one_last_balance_relation()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $balance = factory(BankAccountBalance::class)->create(['date' => '2023-03-31', 'bank_account_id' => $bankAccount->id]);
        factory(BankAccountBalance::class)->create(['date' => '2023-01-31', 'bank_account_id' => $bankAccount->id]);

        $this->assertInstanceOf(BankAccountBalance::class, $bankAccount->lastBalance);
    }
}
