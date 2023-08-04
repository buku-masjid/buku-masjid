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
        $this->assertTrue($admin->can('create', new Transaction));
    }

    /** @test */
    public function user_can_only_view_their_own_transaction()
    {
        $admin = $this->createUser('admin');
        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('view', $transaction));
        $this->assertFalse($admin->can('view', $othersTransaction));
    }

    /** @test */
    public function user_can_only_update_their_own_transaction()
    {
        $admin = $this->createUser('admin');
        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('update', $transaction));
        $this->assertFalse($admin->can('update', $othersTransaction));
    }

    /** @test */
    public function user_can_only_delete_their_own_transaction()
    {
        $admin = $this->createUser('admin');
        $transaction = factory(Transaction::class)->create(['creator_id' => $admin->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($admin->can('delete', $transaction));
        $this->assertFalse($admin->can('delete', $othersTransaction));
    }
}
