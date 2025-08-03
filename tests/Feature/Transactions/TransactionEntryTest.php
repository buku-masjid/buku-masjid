<?php

namespace Tests\Feature\Transactions;

use App\Jobs\Files\OptimizeImage;
use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Models\Partner;
use App\Services\SystemInfo\DiskUsageService;
use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\Fakes\FakeDiskUsageService;
use Tests\TestCase;

class TransactionEntryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_an_income_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_income'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'bank_account_id' => '',
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
        ]);
    }

    /** @test */
    public function selected_date_is_based_on_selected_month()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->seeElement('input', ['id' => 'date', 'value' => $year.'-'.$month.'-'.now()->format('d')]);
    }

    /** @test */
    public function user_can_create_an_income_transaction_with_files()
    {
        Bus::fake();
        Storage::fake(config('filesystem.default'));

        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $partner = factory(Partner::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $book->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_income'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
            'bank_account_id' => '',
            'files' => [
                public_path('screenshots/01-monthly-report-for-public.jpg'),
            ],
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.income_added'));

        $transaction = Transaction::first();
        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'partner_id' => $partner->id,
        ]);

        $this->seeInDatabase('files', [
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'raw_image',
            'title' => null,
            'description' => null,
        ]);

        $file = $transaction->files()->first();
        Storage::assertExists($file->file_path);

        Bus::assertDispatched(OptimizeImage::class, function ($job) use ($file) {
            return $job->file->id = $file->id;
        });
    }

    /** @test */
    public function user_can_create_a_spending_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $bankAccount = factory(BankAccount::class)->create();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-spending', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_spending'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'bank_account_id' => $bankAccount->id,
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.spending_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 0, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'bank_account_id' => $bankAccount->id,
            'partner_id' => null,
        ]);
    }

    /** @test */
    public function user_can_duplicate_a_transaction()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create();
        $transaction = factory(Transaction::class)->create([
            'in_out' => Transaction::TYPE_SPENDING,
            'amount' => 99.99,
            'date' => $date,
            'creator_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->visitRoute('transactions.show', $transaction);
        $this->click('duplicate-transaction-'.$transaction->id);
        $this->seeRouteIs('transactions.create', [
            'action' => 'add-spending',
            'month' => $month,
            'original_transaction_id' => $transaction->id,
            'year' => $year,
        ]);

        $this->seeElement('input', ['type' => 'text', 'name' => 'amount', 'value' => '99,99']);
        $this->seeInElement('textarea#description', $transaction->description);
    }

    /** @test */
    public function user_can_create_a_spending_transaction_with_uploaded_files()
    {
        Bus::fake();
        Storage::fake(config('filesystem.default'));

        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $bankAccount = factory(BankAccount::class)->create();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-spending', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_spending'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'bank_account_id' => $bankAccount->id,
            'files' => [
                public_path('screenshots/01-monthly-report-for-public.jpg'),
            ],
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.spending_added'));

        $transaction = Transaction::first();
        $this->seeInDatabase('transactions', [
            'id' => $transaction->id,
            'in_out' => 0, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Spending description',
            'bank_account_id' => $bankAccount->id,
            'partner_id' => null,
        ]);

        $this->seeInDatabase('files', [
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'raw_image',
            'title' => null,
            'description' => null,
        ]);

        $file = $transaction->files()->first();
        Storage::assertExists($file->file_path);

        Bus::assertDispatched(OptimizeImage::class, function ($job) use ($file) {
            return $job->file->id = $file->id;
        });
    }

    /** @test */
    public function user_cannot_create_a_transaction_with_uploaded_files_when_disk_is_full()
    {
        $month = '01';
        $year = '2017';
        $this->app->instance(DiskUsageService::class, new FakeDiskUsageService());

        $this->loginAsUser();
        $book = factory(Book::class)->create();
        $bankAccount = factory(BankAccount::class)->create();
        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_spending'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-spending', 'month' => $month, 'year' => $year]);

        $this->see(__('transaction.disk_is_full'));
        $this->dontSeeElement('input', [
            'type' => 'file',
            'name' => 'files[]',
        ]);
    }

    /** @test */
    public function new_transaction_book_id_filled_with_the_current_active_book()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create();
        $activeBook = factory(Book::class)->create();
        $category = factory(Category::class)->create([
            'book_id' => $activeBook->id,
            'creator_id' => $user->id,
            'color' => config('masjid.income_color'),
        ]);
        session()->put('active_book_id', $activeBook->id);

        $this->visit(route('transactions.index', ['month' => $month, 'year' => $year]));

        $this->click(__('transaction.add_income'));
        $this->seeRouteIs('transactions.create', ['action' => 'add-income', 'month' => $month, 'year' => $year]);

        $this->submitForm(__('transaction.add_income'), [
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
        ]);

        $this->seeRouteIs('transactions.index', ['month' => $month, 'year' => $year]);
        $this->see(__('transaction.income_added'));

        $this->seeInDatabase('transactions', [
            'in_out' => 1, // 0:spending, 1:income
            'amount' => 99.99,
            'date' => $date,
            'description' => 'Income description',
            'category_id' => $category->id,
            'book_id' => $activeBook->id,
        ]);
    }

    /** @test */
    public function cannot_add_transactions_into_an_in_active_book()
    {
        $month = '01';
        $year = '2017';
        $date = '2017-01-01';
        $user = $this->loginAsUser();
        $inActiveBook = factory(Book::class)->create(['status_id' => Book::STATUS_INACTIVE]);
        session()->put('active_book_id', $inActiveBook->id);

        $this->visitRoute('transactions.index', ['month' => $month, 'year' => $year]);

        $this->dontSeeElement('a', ['href' => route('transactions.index', ['action' => 'add-income', 'month' => $month, 'year' => $year])]);
        $this->dontSeeElement('a', ['href' => route('transactions.index', ['action' => 'add-spending', 'month' => $month, 'year' => $year])]);

        $this->visitRoute('transactions.index', ['action' => 'add-spending', 'month' => $month, 'year' => $year]);
        $this->dontSeeElement('button', ['value' => __('transaction.create')]);
    }
}
