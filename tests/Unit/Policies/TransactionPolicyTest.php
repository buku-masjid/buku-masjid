<?php

namespace Tests\Unit\Policies;

use App\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_transaction()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $this->assertTrue($admin->can('create', new Transaction));
        $this->assertFalse($chairman->can('create', new Transaction));
        $this->assertFalse($secretary->can('create', new Transaction));
        $this->assertTrue($finance->can('create', new Transaction));
    }

    /** @test */
    public function user_can_see_transaction_details()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('view', $transaction));
        $this->assertTrue($admin->can('view', $othersTransaction));
        $this->assertTrue($chairman->can('view', $transaction));
        $this->assertTrue($secretary->can('view', $transaction));
        $this->assertTrue($finance->can('view', $transaction));
    }

    /** @test */
    public function admin_and_finance_can_update_transaction()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('update', $transaction));
        $this->assertTrue($admin->can('update', $othersTransaction));
        $this->assertFalse($chairman->can('update', $transaction));
        $this->assertFalse($secretary->can('update', $transaction));
        $this->assertTrue($finance->can('update', $transaction));
    }

    /** @test */
    public function admin_and_finance_can_delete_transaction()
    {
        $admin = $this->createUser('admin');
        $chairman = $this->createUser('chairman');
        $secretary = $this->createUser('secretary');
        $finance = $this->createUser('finance');

        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('delete', $transaction));
        $this->assertTrue($admin->can('delete', $othersTransaction));
        $this->assertFalse($chairman->can('delete', $transaction));
        $this->assertFalse($secretary->can('delete', $transaction));
        $this->assertTrue($finance->can('delete', $transaction));
    }
}
