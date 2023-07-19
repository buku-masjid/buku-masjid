<?php

namespace Tests\Unit\Helpers;

use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceFunctionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function balance_function_is_exists()
    {
        $this->assertTrue(function_exists('balance'), 'The balance() function does not exists.');
    }

    /** @test */
    public function balance_can_returns_current_balance_of_alltime_transactions()
    {
        $user = $this->loginAsUser();

        // Income transaction
        factory(Transaction::class)->create(['amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        // Spending transaction
        factory(Transaction::class)->create(['amount' => 3000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Assert balance with no specific date range
        $this->assertEquals(7000, balance());
    }

    /** @test */
    public function balance_can_returns_balance_until_specified_date()
    {
        $user = $this->loginAsUser();

        // Income transaction
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        // Spending transaction
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Other transaction after specified date
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);

        // Assert balance until date '2015-01-30'
        $this->assertEquals(6000, balance('2015-01-30'));
    }

    /** @test */
    public function balance_can_returns_balance_within_date_ranges()
    {
        $user = $this->loginAsUser();

        // Other transaction outside date range
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'creator_id' => $user->id]);

        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-31', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(16000, balance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function make_sure_balance_only_for_authenticated_user()
    {
        $user = $this->loginAsUser();

        // Other transaction outside specified date
        factory(Transaction::class)->create(['date' => '2015-01-01', 'amount' => 900, 'in_out' => 1, 'creator_id' => $user->id]);

        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 0, 'creator_id' => $user->id]);

        // Other user's transaction within date range
        factory(Transaction::class)->create(['date' => '2015-01-18', 'amount' => 10000, 'in_out' => 1, 'creator_id' => 999]);

        // Assert balance from '2015-01-03' until '2015-01-30'
        $this->assertEquals(6000, balance('2015-01-31', '2015-01-03'));
    }

    /** @test */
    public function balance_function_accepts_category_id_parameter()
    {
        $user = $this->loginAsUser();

        // Other transaction with different category_id
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id, 'category_id' => 1]);

        factory(Transaction::class)->create(['date' => '2015-01-05', 'amount' => 900, 'in_out' => 0, 'creator_id' => $user->id, 'category_id' => 2]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 1, 'creator_id' => $user->id, 'category_id' => 2]);

        // Assert balance from '2015-01-03' until '2015-01-30' with category_id 2
        $this->assertEquals(3100, balance('2015-01-31', '2015-01-03', 2));
    }

    /** @test */
    public function balance_function_accepts_book_id_parameter()
    {
        $user = $this->loginAsUser();

        // Other transaction with different book_id
        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id, 'book_id' => 1]);

        factory(Transaction::class)->create(['date' => '2015-01-05', 'amount' => 900, 'in_out' => 0, 'creator_id' => $user->id, 'book_id' => 2]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 1, 'creator_id' => $user->id, 'book_id' => 2]);

        // Assert balance from '2015-01-03' until '2015-01-30' with no category and book_id 2
        $this->assertEquals(3100, balance('2015-01-31', '2015-01-03', null, 2));
    }

    /** @test */
    public function balance_function_accepts_category_id_and_book_id_parameter()
    {
        $user = $this->loginAsUser();

        factory(Transaction::class)->create(['date' => '2015-01-03', 'amount' => 10000, 'in_out' => 1, 'creator_id' => $user->id, 'category_id' => 1, 'book_id' => 1]);
        factory(Transaction::class)->create(['date' => '2015-01-05', 'amount' => 900, 'in_out' => 0, 'creator_id' => $user->id, 'category_id' => 1, 'book_id' => 2]);
        factory(Transaction::class)->create(['date' => '2015-01-20', 'amount' => 4000, 'in_out' => 1, 'creator_id' => $user->id, 'category_id' => 2, 'book_id' => 2]);

        // Assert balance from '2015-01-03' until '2015-01-30' with category_id 1 and book_id 2
        $this->assertEquals(-900, balance('2015-01-31', '2015-01-03', 1, 2));
    }

    /** @test */
    public function unauthenticated_user_has_0_balance()
    {
        factory(Transaction::class)->create();

        $this->assertEquals(0, balance());
    }
}
