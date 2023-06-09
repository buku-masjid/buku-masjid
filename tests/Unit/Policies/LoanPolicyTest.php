<?php

namespace Tests\Unit\Policies;

use App\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_loan()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Loan));
    }

    /** @test */
    public function user_can_view_loan()
    {
        $user = $this->createUser();
        $loan = factory(Loan::class)->create();
        $this->assertTrue($user->can('view', $loan));
    }

    /** @test */
    public function user_can_update_loan()
    {
        $user = $this->createUser();
        $loan = factory(Loan::class)->create();
        $this->assertTrue($user->can('update', $loan));
    }

    /** @test */
    public function user_can_delete_loan()
    {
        $user = $this->createUser();
        $loan = factory(Loan::class)->create();
        $this->assertTrue($user->can('delete', $loan));
    }
}
