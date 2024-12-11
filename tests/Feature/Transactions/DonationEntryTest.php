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
        $partner = factory(Partner::class)->create(['type_code' => ['donatur']]);
        $this->visitRoute('donors.index');

        $this->click(__('donor.add_donation'));
        $this->seeRouteIs('donor_transactions.create');

        $this->submitForm(__('donor.add_donation'), [
            'amount' => 99.99,
            'date' => '2024-11-26',
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
            'date' => '2024-11-26',
            'description' => __('donor.donation_from', ['donor_name' => $partner->name]).'|Doa donatur.',
            'category_id' => null,
            'bank_account_id' => null,
            'book_id' => $book->id,
            'partner_id' => $partner->id,
        ]);
    }

    /** @test */
    public function user_can_entry_transction_from_new_donor()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();

        $this->visitRoute('donors.index');

        $this->click(__('donor.add_donation'));
        $this->seeRouteIs('donor_transactions.create');
        $this->click(__('donor.new'));
        $this->seeRouteIs('donor_transactions.create', ['action' => 'new_donor']);

        $this->submitForm(__('donor.add_donation'), [
            'amount' => 99.99,
            'date' => '2024-11-26',
            'partner_id' => '',
            'partner_name' => 'Abdullah',
            'partner_phone' => '081234567890',
            'partner_gender_code' => Partner::GENDER_MALE,
            'book_id' => $book->id,
            'bank_account_id' => '',
            'notes' => 'Doa donatur.',
        ]);

        $this->seeRouteIs('donors.index');
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('partners', [
            'name' => 'Abdullah',
            'phone' => '081234567890',
            'gender_code' => Partner::GENDER_MALE,
            'type_code' => json_encode(['donatur']),
        ]);

        $partner = Partner::first();

        $this->seeInDatabase('transactions', [
            'in_out' => Transaction::TYPE_INCOME,
            'amount' => 99.99,
            'date' => '2024-11-26',
            'description' => __('donor.donation_from', ['donor_name' => $partner->name]).'|Doa donatur.',
            'category_id' => null,
            'bank_account_id' => null,
            'book_id' => $book->id,
            'partner_id' => $partner->id,
        ]);
    }

    /** @test */
    public function user_can_entry_transction_from_donor_detail_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create(['type_code' => ['donatur']]);

        $this->visitRoute('donors.show', $partner);

        $this->click(__('donor.add_donation'));
        $this->seeRouteIs('donor_transactions.create', ['partner_id' => $partner->id, 'reference_page' => 'donor']);

        $this->submitForm(__('donor.add_donation'), [
            'amount' => 99.99,
            'date' => '2024-11-26',
            'partner_id' => $partner->id,
            'book_id' => $book->id,
            'bank_account_id' => '',
            'notes' => '',
        ]);

        $this->seeRouteIs('donors.show', $partner);
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => Transaction::TYPE_INCOME,
            'amount' => 99.99,
            'date' => '2024-11-26',
            'description' => __('donor.donation_from', ['donor_name' => $partner->name]).'',
            'category_id' => null,
            'bank_account_id' => null,
            'book_id' => $book->id,
            'partner_id' => $partner->id,
        ]);
    }

    /** @test */
    public function validate_when_notes_are_too_long_for_existing_partner()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create(['name' => 'Abdullah bin Abdurrahman', 'type_code' => ['donatur']]);
        $notes = str_repeat('Lorem ipsum dolor sit, amet consectetur adipisicing elit.', 4);

        $this->post(route('donor_transactions.store'), [
            'amount' => 99.99,
            'date' => '2024-11-26',
            'partner_id' => $partner->id,
            'book_id' => $book->id,
            'bank_account_id' => '',
            'notes' => $notes,
        ]);

        $transactionDescription = __('donor.donation_from', ['donor_name' => $partner->name]);
        $noteStringLimit = (255 - strlen($transactionDescription));

        $this->assertSessionHasErrors(['notes' => __('validation.donor.notes.max', ['max' => $noteStringLimit])]);
    }

    /** @test */
    public function validate_when_notes_are_too_long_for_new_partner()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $notes = str_repeat('Lorem ipsum dolor sit, amet consectetur adipisicing elit.', 4);

        $this->post(route('donor_transactions.store'), [
            'amount' => 99.99,
            'date' => '2024-11-26',
            'partner_id' => '',
            'partner_name' => 'Abdullah bin Abdul Hadi',
            'partner_phone' => '081234567890',
            'partner_gender_code' => Partner::GENDER_MALE,
            'book_id' => $book->id,
            'bank_account_id' => '',
            'notes' => $notes,
        ]);

        $transactionDescription = __('donor.donation_from', ['donor_name' => 'Abdullah bin Abdul Hadi']);
        $noteStringLimit = (255 - strlen($transactionDescription));

        $this->assertSessionHasErrors('notes');
        $this->assertSessionHasErrors(['notes' => __('validation.donor.notes.max', ['max' => $noteStringLimit])]);
    }

    /** @test */
    public function validate_to_ensure_selected_partner_id_exists()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $validPartner = factory(Partner::class)->create(['type_code' => ['donatur']]);

        $this->post(route('donor_transactions.store'), [
            'partner_id' => $validPartner->id,
        ]);
        $this->assertSessionMissingErrors('partner_id');

        $this->post(route('donor_transactions.store'), [
            'partner_id' => 9999,
        ]);
        $this->assertSessionHasErrors(['partner_id' => __('validation.exists', ['attribute' => 'partner id'])]);
    }
}
