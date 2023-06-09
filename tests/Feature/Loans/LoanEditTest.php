<?php

namespace Tests\Feature\Loans;

use App\Loan;
use App\Partner;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanEditTest extends TestCase
{
    use RefreshDatabase;

    private function getEditFields(array $overrides = [])
    {
        return array_merge([
            'type_id' => Loan::TYPE_RECEIVABLE,
            'amount' => 2000,
            'planned_payment_count' => 5,
            'start_date' => '2020-01-01',
            'end_date' => '2020-02-29',
            'description' => 'Loan 1 description',
        ], $overrides);
    }

    /** @test */
    public function user_can_edit_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create([
            'partner_id' => $partner->id,
            'creator_id' => $user->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'loan_id' => $loan->id,
            'amount' => $loan->amount,
            'partner_id' => $partner->id,
            'creator_id' => $user->id,
        ]);

        $this->visitRoute('loans.show', $loan);
        $this->click('edit-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', $loan);

        $this->submitForm(__('loan.update'), $this->getEditFields([
            'partner_id' => $loan->partner_id,
            'amount' => 1000,
            'type_id' => Loan::TYPE_RECEIVABLE,
        ]));

        $this->seeRouteIs('loans.show', $loan);

        $this->seeInDatabase('loans', $this->getEditFields([
            'id' => $loan->id,
            'amount' => 1000,
        ]));

        $this->seeInDatabase('transactions', [
            'loan_id' => $loan->id,
            'amount' => 1000,
            'partner_id' => $partner->id,
            'in_out' => 0, // 0:spending, 1:income
        ]);
    }

    /** @test */
    public function validate_loan_partner_id_update_is_required()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id, 'partner_id' => 500]);

        // partner_id empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['partner_id' => '']));
        $this->assertSessionHasErrors('partner_id');
    }

    /** @test */
    public function validate_loan_type_id_update_is_required()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id, 'type_id' => 2]);

        // type_id empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['type_id' => '']));
        $this->assertSessionHasErrors('type_id');
    }

    /** @test */
    public function validate_loan_amount_update_is_required()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id, 'amount' => 500]);

        // amount empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['amount' => '']));
        $this->assertSessionHasErrors('amount');
    }

    /** @test */
    public function validate_loan_description_update_is_required()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id]);

        // description empty
        $this->patch(route('loans.update', $loan), $this->getEditFields(['description' => '']));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function validate_loan_description_update_is_not_more_than_255_characters()
    {
        $user = $this->loginAsUser();
        $loan = factory(Loan::class)->create(['creator_id' => $user->id]);

        // description 256 characters
        $this->patch(route('loans.update', $loan), $this->getEditFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function user_can_delete_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create(['creator_id' => $user->id, 'partner_id' => $partner->id]);
        factory(Loan::class)->create(['creator_id' => $user->id, 'partner_id' => $partner->id]);

        $this->visitRoute('loans.edit', $loan);
        $this->click('del-loan-'.$loan->id);
        $this->seeRouteIs('loans.edit', [$loan, 'action' => 'delete']);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('loans', [
            'id' => $loan->id,
        ]);
    }
}
