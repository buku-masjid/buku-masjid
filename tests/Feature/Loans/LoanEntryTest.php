<?php

namespace Tests\Feature\Loans;

use App\Loan;
use App\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanEntryTest extends TestCase
{
    use RefreshDatabase;

    private function getCreateFields(array $overrides = [])
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
    public function user_can_create_a_loan()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $this->visitRoute('loans.index');

        $this->click(__('loan.create'));
        $this->seeRouteIs('loans.create');

        $this->submitForm(__('loan.create'), $this->getCreateFields([
            'partner_id' => $partner->id,
            'type_id' => Loan::TYPE_DEBT,
            'amount' => 2000,
        ]));

        $loan = Loan::first();
        $this->seeRouteIs('loans.show', $loan);

        $this->seeInDatabase('loans', $this->getCreateFields([
            'partner_id' => $partner->id,
            'type_id' => Loan::TYPE_DEBT,
            'amount' => 2000,
        ]));

        $this->seeInDatabase('transactions', [
            'loan_id' => $loan->id,
            'in_out' => 1, // 0:spending, 1:income
            'amount' => '2000',
            'date' => '2020-01-01',
            'description' => 'Loan 1 description',
            'category_id' => null,
            'partner_id' => $partner->id,
        ]);
    }

    /** @test */
    public function validate_loan_partner_id_is_required()
    {
        $this->loginAsUser();

        // partner_id empty
        $this->post(route('loans.store'), $this->getCreateFields(['partner_id' => '']));
        $this->assertSessionHasErrors('partner_id');
    }

    /** @test */
    public function validate_loan_type_id_is_required()
    {
        $this->loginAsUser();

        // type_id empty
        $this->post(route('loans.store'), $this->getCreateFields(['type_id' => '']));
        $this->assertSessionHasErrors('type_id');
    }

    /** @test */
    public function validate_loan_amount_is_required()
    {
        $this->loginAsUser();

        // amount empty
        $this->post(route('loans.store'), $this->getCreateFields(['amount' => '']));
        $this->assertSessionHasErrors('amount');
    }

    /** @test */
    public function validate_loan_description_is_required()
    {
        $this->loginAsUser();

        // description empty
        $this->post(route('loans.store'), $this->getCreateFields(['description' => '']));
        $this->assertSessionHasErrors('description');
    }

    /** @test */
    public function validate_loan_description_is_not_more_than_255_characters()
    {
        $this->loginAsUser();

        // description 256 characters
        $this->post(route('loans.store'), $this->getCreateFields([
            'description' => str_repeat('Long description', 16),
        ]));
        $this->assertSessionHasErrors('description');
    }
}
