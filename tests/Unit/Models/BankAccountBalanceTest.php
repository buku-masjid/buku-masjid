<?php

namespace Tests\Unit\Models;

use App\Models\BankAccountBalance;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankAccountBalanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bank_account_balance_model_has_belongs_to_creator_relation()
    {
        $bankAccountBalance = factory(BankAccountBalance::class)->create();

        $this->assertInstanceOf(User::class, $bankAccountBalance->creator);
        $this->assertEquals($bankAccountBalance->creator_id, $bankAccountBalance->creator->id);
    }

    /** @test */
    public function bank_account_balance_model_has_amount_string_attribute()
    {
        $bankAccountBalance = factory(BankAccountBalance::class)->make();

        $this->assertEquals('1,000,001.00', $bankAccountBalance->amount_string);
    }
}
