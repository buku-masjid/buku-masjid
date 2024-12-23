<?php

namespace Tests\Unit\Models;

use App\Models\BankAccount;
use App\Models\Book;
use App\Models\Category;
use App\Models\File;
use App\Models\Partner;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_transaction_has_belongs_to_creator_relation()
    {
        $transaction = factory(Transaction::class)->make();

        $this->assertInstanceOf(User::class, $transaction->creator);
        $this->assertEquals($transaction->creator_id, $transaction->creator->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_category_relation()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $transaction->category);
        $this->assertEquals($transaction->category_id, $transaction->category->id);
    }

    /** @test */
    public function transaction_model_has_belongs_to_partner_relation()
    {
        $user = $this->loginAsUser();
        $partner = factory(Partner::class)->create();
        $transaction = factory(Transaction::class)->make(['partner_id' => $partner->id]);

        $this->assertInstanceOf(Partner::class, $transaction->partner);
        $this->assertEquals($transaction->partner_id, $transaction->partner->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_bank_account_relation()
    {
        $user = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['bank_account_id' => $bankAccount->id]);

        $this->assertInstanceOf(BankAccount::class, $transaction->bankAccount);
        $this->assertEquals($transaction->bank_account_id, $transaction->bankAccount->id);
    }

    /** @test */
    public function a_transaction_has_belongs_to_book_relation()
    {
        $user = $this->loginAsUser();
        $book = factory(Book::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->make(['book_id' => $book->id]);

        $this->assertInstanceOf(Book::class, $transaction->book);
        $this->assertEquals($transaction->book_id, $transaction->book->id);
    }

    /** @test */
    public function a_transaction_has_type_attribute()
    {
        $transaction = factory(Transaction::class)->make(['in_out' => 1]);
        $this->assertEquals(__('transaction.income'), $transaction->type);

        $transaction->in_out = 0;
        $this->assertEquals(__('transaction.spending'), $transaction->type);
    }

    /** @test */
    public function transaction_model_has_date_alert_attribute()
    {
        Carbon::setTestNow('2024-10-20');
        $transaction = factory(Transaction::class)->make(['date' => '2024-10-22']);
        $this->assertEquals(
            '<i class="fe fe-alert-circle text-danger" title="'.__('transaction.forward_date_alert').'"></i>',
            $transaction->date_alert
        );

        $transaction->date = '2024-10-20';
        $this->assertEquals('', $transaction->date_alert);

        $transaction->date = '2024-10-19';
        $this->assertEquals('', $transaction->date_alert);
        Carbon::setTestNow();
    }

    /** @test */
    public function a_transaction_has_amount_string_attribute()
    {
        $amount = 1099.00;

        $transaction = factory(Transaction::class)->make([
            'in_out' => 1,
            'amount' => $amount,
        ]);
        $this->assertEquals(format_number($amount), $transaction->amount_string);

        $transaction->in_out = 0;
        $this->assertEquals(format_number(-$amount), $transaction->amount_string);
    }

    /** @test */
    public function a_transaction_has_year_month_and_date_only_attribute()
    {
        $transaction = factory(Transaction::class)->make(['date' => '2017-01-31']);

        $this->assertEquals('2017', $transaction->year);
        $this->assertEquals('01', $transaction->month);
        $this->assertEquals(Carbon::parse('2017-01-31')->isoFormat('MMM'), $transaction->month_name);
        $this->assertEquals('31', $transaction->date_only);
    }

    /** @test */
    public function a_transaction_has_day_name_attribute()
    {
        $date = '2017-01-31';
        $transaction = factory(Transaction::class)->make(['date' => $date]);

        $this->assertEquals(Carbon::parse($date)->isoFormat('dddd'), $transaction->day_name);

        $transaction = factory(Transaction::class)->make(['date' => null]);
        $this->assertEquals(null, $transaction->day_name);
    }

    /** @test */
    public function a_transaction_has_change_day_name_minggu_to_ahad_attribute()
    {
        $date = '2017-01-29';
        $transaction = factory(Transaction::class)->make(['date' => $date]);

        $this->assertEquals('Ahad', $transaction->day_name);
    }

    /** @test */
    public function transaction_model_has_has_many_files_relation()
    {
        $transaction = factory(Transaction::class)->create();
        $file = File::create([
            'fileable_id' => $transaction->id,
            'fileable_type' => 'transactions',
            'type_code' => 'image',
            'file_path' => 'File title',
            'title' => 'File title',
            'description' => 'Some transaction description',
        ]);

        $this->assertInstanceOf(Collection::class, $transaction->files);
        $this->assertInstanceOf(File::class, $transaction->files->first());
    }
}
