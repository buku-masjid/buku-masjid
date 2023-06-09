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
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Transaction));
    }

    /** @test */
    public function user_can_only_view_their_own_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($user->can('view', $transaction));
        $this->assertFalse($user->can('view', $othersTransaction));
    }

    /** @test */
    public function user_can_only_update_their_own_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($user->can('update', $transaction));
        $this->assertFalse($user->can('update', $othersTransaction));
    }

    /** @test */
    public function user_can_only_delete_their_own_transaction()
    {
        $user = $this->createUser();
        $transaction = factory(Transaction::class)->create(['creator_id' => $user->id]);
        $othersTransaction = factory(Transaction::class)->create();

        $this->assertTrue($user->can('delete', $transaction));
        $this->assertFalse($user->can('delete', $othersTransaction));
    }
}
