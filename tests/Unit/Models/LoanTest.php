<?php

namespace Tests\Unit\Models;

use App\Loan;
use App\Partner;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_loan_has_belongs_to_creator_relation()
    {
        $loan = factory(Loan::class)->make();

        $this->assertInstanceOf(User::class, $loan->creator);
        $this->assertEquals($loan->creator_id, $loan->creator->id);
    }

    /** @test */
    public function a_loan_has_belongs_to_partner_relation()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->make(['partner_id' => $partner->id]);

        $this->assertInstanceOf(Partner::class, $loan->partner);
        $this->assertEquals($loan->partner_id, $loan->partner->id);
    }

    /** @test */
    public function a_loan_has_many_transactions_relation()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create(['partner_id' => $partner->id]);
        $transaction = factory(Transaction::class)->create([
            'partner_id' => $partner->id,
            'loan_id' => $loan->id,
            'creator_id' => $user->id,
        ]);

        $this->assertInstanceOf(Collection::class, $loan->transactions);
        $this->assertInstanceOf(Transaction::class, $loan->transactions->first());
    }

    /** @test */
    public function a_loan_has_type_attribute()
    {
        $loan = factory(Loan::class)->make(['type_id' => Loan::TYPE_DEBT]);
        $this->assertEquals(__('loan.types.debt'), $loan->type);

        $loan->type_id = Loan::TYPE_RECEIVABLE;
        $this->assertEquals(__('loan.types.receivable'), $loan->type);
    }

    /** @test */
    public function a_loan_has_amount_string_attribute()
    {
        $amount = 1099.00;

        $loan = factory(Loan::class)->make([
            'amount' => $amount,
        ]);
        $this->assertEquals(number_format($amount, 2), $loan->amount_string);
    }

    /** @test */
    public function a_debt_loan_has_payment_total_attribute()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'type_id' => Loan::TYPE_RECEIVABLE,
            'amount' => 10000,
            'partner_id' => $partner->id,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 0, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 10000,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 1, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 7000,
        ]);

        $this->assertEquals(7000, $loan->payment_total);
        $this->assertEquals('7,000.00', $loan->payment_total_string);
    }

    /** @test */
    public function a_debt_loan_has_payment_remaining_attribute()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'type_id' => Loan::TYPE_RECEIVABLE,
            'amount' => 10000,
            'partner_id' => $partner->id,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 0, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 10000,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 1, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 7000,
        ]);

        $this->assertEquals(3000, $loan->payment_remaining);
        $this->assertEquals('3,000.00', $loan->payment_remaining_string);
    }

    /** @test */
    public function a_receivable_loan_has_payment_total_attribute()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'type_id' => Loan::TYPE_DEBT,
            'amount' => 10000,
            'partner_id' => $partner->id,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 1, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 10000,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 0, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 2000,
        ]);

        $this->assertEquals(2000, $loan->payment_total);
        $this->assertEquals('2,000.00', $loan->payment_total_string);
    }

    /** @test */
    public function a_receivable_loan_has_payment_remaining_attribute()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'type_id' => Loan::TYPE_DEBT,
            'amount' => 10000,
            'partner_id' => $partner->id,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 1, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 10000,
        ]);
        factory(Transaction::class)->create([
            'in_out' => 0, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 2000,
        ]);

        $this->assertEquals(8000, $loan->payment_remaining);
        $this->assertEquals('8,000.00', $loan->payment_remaining_string);
    }

    /** @test */
    public function a_loan_has_type_label_attribute()
    {
        $loan = factory(Loan::class)->make(['type_id' => Loan::TYPE_DEBT]);

        $nameLabel = '<span class="badge" style="background-color: #00aabb">'.$loan->type.'</span>';
        $this->assertEquals($nameLabel, $loan->type_label);

        $loan->type_id = Loan::TYPE_RECEIVABLE;

        $nameLabel = '<span class="badge" style="background-color: #bb004f">'.$loan->type.'</span>';
        $this->assertEquals($nameLabel, $loan->type_label);
    }

    /** @test */
    public function a_loan_type_label_attribute_will_have_checkmark_when_the_loan_has_been_ended()
    {
        $loan = factory(Loan::class)->make([
            'type_id' => Loan::TYPE_DEBT,
            'end_date' => '2020-02-01',
        ]);

        $nameLabel = '<span class="badge" style="background-color: #00aabb">'.$loan->type.' <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
        $this->assertEquals($nameLabel, $loan->type_label);

        $loan->type_id = Loan::TYPE_RECEIVABLE;

        $nameLabel = '<span class="badge" style="background-color: #bb004f">'.$loan->type.' <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>';
        $this->assertEquals($nameLabel, $loan->type_label);
    }

    /** @test */
    public function a_loan_deletion_will_set_loan_id_to_be_null_for_the_related_transactions()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'type_id' => Loan::TYPE_DEBT,
            'amount' => 10000,
            'partner_id' => $partner->id,
        ]);
        $firstTransaction = factory(Transaction::class)->create([
            'in_out' => 1, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 10000,
        ]);
        $secondTransaction = factory(Transaction::class)->create([
            'in_out' => 0, // 0:spending, 1:income
            'creator_id' => $user->id,
            'partner_id' => $loan->partner_id,
            'loan_id' => $loan->id,
            'amount' => 2000,
        ]);

        $result = $loan->delete();

        $this->assertTrue($result);
        $this->assertNull($firstTransaction->fresh()->loan_id);
        $this->assertNull($secondTransaction->fresh()->loan_id);
    }

    /** @test */
    public function a_loan_has_for_user_scope()
    {
        $loanOwner = $this->loginAsUser();
        $loan = factory(Loan::class)->create([
            'creator_id' => $loanOwner->id,
        ]);
        $othersLoan = factory(Loan::class)->create();

        $this->assertCount(1, Loan::all());
    }
}
