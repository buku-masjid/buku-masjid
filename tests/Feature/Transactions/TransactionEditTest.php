<?php

namespace Tests\Feature\Transactions;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Models\Partner;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_a_transaction_within_month_and_year_query()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        DB::table('settings')->insert([
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'income_partner_codes',
            'value' => '["donatur"]',
        ]);
        $bankAccount = factory(BankAccount::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        $partner = factory(Partner::class)->create(['type_code' => ['donatur']]);

        $this->visitRoute('transactions.index', ['month' => $month, 'year' => $year]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [$transaction->id, 'month' => $month, 'reference_page' => 'transactions', 'year' => $year]);

        $this->submitForm(__('transaction.update'), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $transaction->month, 'year' => $transaction->year]);
        $this->see(__('transaction.updated'));

        $this->seeInDatabase('transactions', [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'bank_account_id' => $bankAccount->id,
        ]);
    }

    /** @test */
    public function user_cannot_edit_a_transaction_from_an_in_active_book()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'creator_id' => $user->id,
            'book_id' => $inActiveBook->id,
        ]);
        $category = factory(Category::class)->create(['book_id' => $inActiveBook->id, 'creator_id' => $user->id]);

        $this->visitRoute('transactions.index', ['month' => $month, 'year' => $year]);
        $this->dontSeeElement('a', ['id' => 'edit-transaction-'.$transaction->id]);
        $this->visitRoute('transactions.index', ['action' => 'edit', 'id' => $transaction->id, 'month' => $month, 'year' => $year]);
        $this->dontSeeElement('button', ['value' => __('transaction.update')]);
    }

    /** @test */
    public function user_can_edit_a_transaction_within_search_and_category_query()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'category_id' => $category->id,
            'description' => 'Transaction Unique Description',
        ]);

        $this->visitRoute('transactions.index', ['month' => $month, 'year' => $year, 'query' => 'Unique', 'category_id' => $category->id]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [
            $transaction,
            'category_id' => $category->id,
            'month' => $month,
            'query' => 'Unique',
            'reference_page' => 'transactions',
            'year' => $year,
        ]);

        $this->submitForm(__('transaction.update'), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
        ]);

        $this->seeRouteIs('transactions.index', [
            'category_id' => $category->id,
            'month' => $transaction->month,
            'query' => 'Unique',
            'year' => $transaction->year,
            'partner_id' => null,
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);

        $this->visitRoute('transactions.edit', $transaction);
        $this->click('del-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [$transaction, 'action' => 'delete']);

        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->seeRouteIs('transactions.index', ['month' => $transaction->month, 'year' => $transaction->year]);
        $this->see(__('transaction.deleted'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }

    /** @test */
    public function user_can_edit_a_transaction_from_category_transactions_page()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        $bankAccount = factory(BankAccount::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->visitRoute('categories.show', [
            $category->id,
            'start_date' => $date,
            'end_date' => $year.'-'.$month.'-28',
        ]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [
            $transaction,
            'category_id' => $category->id,
            'end_date' => $year.'-'.$month.'-28',
            'reference_page' => 'category',
            'start_date' => $date,
        ]);

        $this->submitForm(__('transaction.update'), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('categories.show', [
            $category->id,
            'end_date' => $year.'-'.$month.'-28',
            'start_date' => $date,
        ]);
        $this->see(__('transaction.updated'));

        $this->seeInDatabase('transactions', [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'category_id' => $category->id,
            'bank_account_id' => $bankAccount->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction_from_category_transactions_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $category = factory(Category::class)->create(['book_id' => $book->id, 'creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => '2017-01-01',
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->visitRoute('categories.show', [
            $category->id,
            'start_date' => '2017-01-01',
            'end_date' => '2017-01-31',
        ]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->click('del-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [
            $transaction,
            'action' => 'delete',
            'category_id' => $category->id,
            'end_date' => '2017-01-31',
            'reference_page' => 'category',
            'start_date' => '2017-01-01',
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->seeRouteIs('categories.show', [
            $category->id,
            'end_date' => '2017-01-31',
            'start_date' => '2017-01-01',
        ]);
        $this->see(__('transaction.deleted'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }

    /** @test */
    public function user_cannot_delete_a_transaction_from_an_in_active_book()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'creator_id' => $user->id,
            'book_id' => $inActiveBook->id,
        ]);
        $category = factory(Category::class)->create(['book_id' => $inActiveBook->id, 'creator_id' => $user->id]);

        $this->visitRoute('transactions.index', ['action' => 'delete', 'id' => $transaction->id, 'month' => $month, 'year' => $year]);
        $this->dontSeeInElement('button', __('app.delete_confirm_button'));
    }

    /** @test */
    public function user_can_edit_a_transaction_from_partner_transactions_page()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        DB::table('settings')->insert([
            'model_type' => 'books',
            'model_id' => $book->id,
            'key' => 'income_partner_codes',
            'value' => '["donatur"]',
        ]);
        $partner = factory(Partner::class)->create(['type_code' => ['donatur']]);
        $bankAccount = factory(BankAccount::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => $date,
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'partner_id' => $partner->id,
        ]);

        $this->visitRoute('partners.show', [
            $partner->id,
            'start_date' => $date,
            'end_date' => $year.'-'.$month.'-28',
        ]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [
            $transaction,
            'end_date' => $year.'-'.$month.'-28',
            'partner_id' => $partner->id,
            'reference_page' => 'partner',
            'start_date' => $date,
        ]);

        $this->submitForm(__('transaction.update'), [
            'in_out' => 1,
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'partner_id' => $partner->id,
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('partners.show', [
            $partner->id,
            'end_date' => $year.'-'.$month.'-28',
            'start_date' => $date,
        ]);
        $this->see(__('transaction.updated'));

        $this->seeInDatabase('transactions', [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Transaction 1 description',
            'partner_id' => $partner->id,
            'bank_account_id' => $bankAccount->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_transaction_from_partner_transactions_page()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => 0,
            'amount' => 99.99,
            'date' => '2017-01-01',
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'partner_id' => $partner->id,
        ]);

        $this->visitRoute('partners.show', [
            $partner->id,
            'start_date' => '2017-01-01',
            'end_date' => '2017-01-31',
        ]);
        $this->click('edit-transaction-'.$transaction->id);
        $this->click('del-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.edit', [
            $transaction,
            'action' => 'delete',
            'end_date' => '2017-01-31',
            'partner_id' => $partner->id,
            'reference_page' => 'partner',
            'start_date' => '2017-01-01',
        ]);

        $this->press(__('app.delete_confirm_button'));

        $this->seeRouteIs('partners.show', [
            $partner->id,
            'end_date' => '2017-01-31',
            'start_date' => '2017-01-01',
        ]);
        $this->see(__('transaction.deleted'));

        $this->dontSeeInDatabase('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
