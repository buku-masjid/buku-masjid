<?php

namespace Tests\Feature\Loans;

use App\Loan;
use App\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_loan_list_in_loan_index_page()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create(['creator_id' => $user->id]);
        $loan = factory(Loan::class)->create(['partner_id' => $partner->id]);

        $this->visitRoute('loans.index');
        $this->see($loan->name);
    }
}
