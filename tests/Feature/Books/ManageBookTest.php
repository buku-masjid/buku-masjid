<?php

namespace Tests\Feature\Books;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_book_list_in_book_index_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('books.index');
        $this->see($book->name);
    }

    /** @test */
    public function user_can_create_a_book()
    {
        $this->loginAsUser();
        $this->visitRoute('books.index');

        $this->click(__('book.create'));
        $this->seeRouteIs('books.index', ['action' => 'create']);

        $this->submitForm(__('book.create'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'budget' => 1000000,
        ]);

        $this->seeRouteIs('books.index');

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'budget' => 1000000,
            'status_id' => Book::STATUS_ACTIVE,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
    }

    /** @test */
    public function user_can_create_a_book_with_a_bank_account()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $this->loginAsUser();
        $this->visitRoute('books.index');

        $this->click(__('book.create'));
        $this->seeRouteIs('books.index', ['action' => 'create']);

        $this->submitForm(__('book.create'), [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'budget' => 1000000,
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('books.index');

        $this->seeInDatabase('books', [
            'name' => 'Book 1 name',
            'description' => 'Book 1 description',
            'status_id' => Book::STATUS_ACTIVE,
            'budget' => 1000000,
            'bank_account_id' => $bankAccount->id,
            'report_visibility_code' => Book::REPORT_VISIBILITY_INTERNAL,
        ]);
    }

    /** @test */
    public function user_can_edit_a_book()
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
            'budget' => 1000000,
            'report_periode_code' => Book::REPORT_PERIODE_IN_WEEKS,
            'start_week_day_code' => 'friday',
            'management_title' => 'Panitia Ramadhan',
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
            'key' => 'management_title',
            'value' => 'Panitia Ramadhan',
        ]);
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
    public function user_can_delete_a_book()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        factory(Book::class)->create(['creator_id' => $user->id]);

        $this->visitRoute('books.edit', [$book]);
        $this->click('del-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book, 'action' => 'delete']);

        $this->seeInDatabase('books', [
            'id' => $book->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('books', [
            'id' => $book->id,
        ]);
    }

    /** @test */
    public function book_deletion_will_also_deletes_its_transactions_and_categories()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        factory(Transaction::class)->create(['book_id' => $book->id]);
        factory(Category::class)->create(['book_id' => $book->id]);

        $this->visitRoute('books.edit', [$book]);
        $this->click('del-book-'.$book->id);
        $this->seeRouteIs('books.edit', [$book, 'action' => 'delete']);

        $this->seeInDatabase('books', ['id' => $book->id]);
        $this->seeInDatabase('categories', ['book_id' => $book->id]);
        $this->seeInDatabase('transactions', ['book_id' => $book->id]);

        $this->press(__('app.delete_confirm_button'));

        $this->dontSeeInDatabase('books', ['id' => $book->id]);
        $this->dontSeeInDatabase('categories', ['book_id' => $book->id]);
        $this->dontSeeInDatabase('transactions', ['book_id' => $book->id]);
    }
}
