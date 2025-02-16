<?php

namespace Tests\Feature\Books;

use App\Models\BankAccount;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EditBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_book_settings()
    {
        $adminUser = $this->loginAsUser();
        $financeUser = $this->createUser('finance');
        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
        $bankAccount = factory(BankAccount::class)->create();

        $this->visitRoute('books.show', $book);
        $this->click('edit-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book]);

        $this->submitForm(__('book.update'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'bank_account_id' => $bankAccount->id,
            'manager_id' => $financeUser->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            'transaction_files_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            'budget' => 1000000,
            'report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS,
            'management_title' => 'Panitia Ramadhan',
            'has_pdf_page_number' => 0,
            'income_partner_codes' => ['partner' => 'partner'],
            'income_partner_null' => 'Hamba Allah',
            'spending_partner_codes' => ['partner' => 'partner'],
            'spending_partner_null' => 'Muzakki',
            'start_week_day_code' => 'friday',
        ]);

        $this->seeRouteIs('books.show', [$book]);

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'bank_account_id' => $bankAccount->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_PUBLIC,
            'budget' => 1000000,
            'report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS,
            'start_week_day_code' => 'friday',
            'manager_id' => $financeUser->id,
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'transaction_files_visibility_code',
            'value' => Book::REPORT_VISIBILITY_PUBLIC,
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'management_title',
            'value' => 'Panitia Ramadhan',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'has_pdf_page_number',
            'value' => '0',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'income_partner_codes',
            'value' => json_encode(['partner']),
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'income_partner_null',
            'value' => 'Hamba Allah',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'spending_partner_codes',
            'value' => json_encode(['partner']),
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'spending_partner_null',
            'value' => 'Muzakki',
        ]);
    }

    /** @test */
    public function user_can_edit_book_signatures()
    {
        $adminUser = $this->loginAsUser();
        $financeUser = $this->createUser('finance');
        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);

        $this->visitRoute('books.show', [$book, 'tab' => 'signatures']);
        $this->click('edit_signatures-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book, 'tab' => 'signatures']);

        $this->submitForm(__('book.update'), [
            'acknowledgment_text_left' => 'Mengetahui',
            'sign_position_left' => 'Ketua Umum',
            'sign_name_left' => 'H. Andi',
            'acknowledgment_text_mid' => 'Penanggung Jawab',
            'sign_position_mid' => 'Bendahara',
            'sign_name_mid' => 'H. Denny',
            'acknowledgment_text_right' => 'Pembuat Laporan',
            'sign_position_right' => 'Sekretariat',
            'sign_name_right' => 'H. Dedy',
        ]);

        $this->seeRouteIs('books.show', [$book, 'tab' => 'signatures']);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'acknowledgment_text_left',
            'value' => 'Mengetahui',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_position_left',
            'value' => 'Ketua Umum',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_name_left',
            'value' => 'H. Andi',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'acknowledgment_text_mid',
            'value' => 'Penanggung Jawab',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_position_mid',
            'value' => 'Bendahara',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_name_mid',
            'value' => 'H. Denny',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'acknowledgment_text_right',
            'value' => 'Pembuat Laporan',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_position_right',
            'value' => 'Sekretariat',
        ]);
        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_name_right',
            'value' => 'H. Dedy',
        ]);
    }

    /** @test */
    public function bugfix_user_can_remove_book_attributes_from_settings_table()
    {
        $adminUser = $this->loginAsUser();
        $financeUser = $this->createUser('finance');
        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
        DB::table('settings')->insert([
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_name_right',
            'value' => 'H. Dedy',
        ]);
        $bankAccount = factory(BankAccount::class)->create();

        $this->visitRoute('books.show', [$book, 'tab' => 'signatures']);
        $this->click('edit_signatures-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book, 'tab' => 'signatures']);
        $this->seeElement('input', ['id' => 'sign_name_right', 'value' => 'H. Dedy']);

        $this->submitForm(__('book.update'), [
            'acknowledgment_text_left' => '',
            'sign_position_left' => '',
            'sign_name_left' => '',
            'acknowledgment_text_mid' => '',
            'sign_position_mid' => '',
            'sign_name_mid' => '',
            'acknowledgment_text_right' => '',
            'sign_position_right' => '',
            'sign_name_right' => '',
        ]);

        $this->seeRouteIs('books.show', [$book, 'tab' => 'signatures']);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'sign_name_right',
            'value' => null,
        ]);
    }

    /** @test */
    public function finance_user_can_edit_a_book_except_manager_id_attribute()
    {
        $financeUser = $this->loginAsUser('finance');
        $adminUser = $this->createUser('admin');
        $otherFinanceUser = $this->createUser('finance');
        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
            'manager_id' => $financeUser->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);

        $this->visitRoute('books.edit', $book);
        $this->dontSeeElement('select', ['name' => 'manager_id']);
    }

    /** @test */
    public function user_can_add_book_landing_page_content()
    {
        $adminUser = $this->loginAsUser();

        $book = factory(Book::class)->create([
            'name' => 'Testing 123',
            'creator_id' => $adminUser->id,
        ]);

        $this->visitRoute('books.show', [$book, 'tab' => 'landing_page']);
        $this->seeElement('a', ['id' => 'edit_landing_page-book-'.$book->id]);
        $this->click('edit_landing_page-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book, 'tab' => 'landing_page']);

        $this->submitForm(__('book.update'), [
            'landing_page_content' => 'Book 1 content',
            'due_date' => '2024-05-01',
        ]);

        $this->seeRouteIs('books.show', [$book, 'tab' => 'landing_page']);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'landing_page_content',
            'value' => 'Book 1 content',
        ]);

        $this->seeInDatabase('settings', [
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'due_date',
            'value' => '2024-05-01',
        ]);
    }
}
