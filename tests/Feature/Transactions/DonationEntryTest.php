<?php

namespace Tests\Feature\Transactions;

use App\Models\Book;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationEntryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_entry_transction_from_donors_index_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create(['type_code' => 'donatur']);
        $this->visitRoute('donors.index');

        $this->click(__('donor.add_donation'));
        $this->seeRouteIs('donor_transactions.create');

        $date = '2024-11-26';
        $this->submitForm(__('donor.add_donation'), [
            'amount' => 99.99,
            'date' => $date,
            'partner_id' => $partner->id,
            'book_id' => $book->id,
            'bank_account_id' => '',
            'notes' => 'Doa donatur.',
        ]);

        $this->seeRouteIs('donors.index');
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => Transaction::TYPE_INCOME,
            'amount' => 99.99,
            'date' => $date,
            'description' => __('donor.donation_from', ['donor_name' => $partner->name]).'|Doa donatur.',
            'category_id' => null,
            'bank_account_id' => null,
            'book_id' => $book->id,
            'partner_id' => $partner->id,
        ]);
    }
}
