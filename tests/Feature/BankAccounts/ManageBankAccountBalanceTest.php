<?php

namespace Tests\Feature\BankAccounts;

use App\Models\BankAccount;
use App\Models\BankAccountBalance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageBankAccountBalanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_bank_account_balance()
    {
        $user = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['description' => 'Testing 123', 'creator_id' => $user->id]);

        $this->visitRoute('bank_accounts.show', $bankAccount);
        $this->seeElement('a', ['id' => 'create-bank_account_balance']);
        $this->click('create-bank_account_balance');
        $this->seeRouteIs('bank_accounts.show', [$bankAccount, 'action' => 'create_bank_account_balance']);

        $this->submitForm(__('bank_account_balance.create'), [
            'date' => '2023-03-01',
            'amount' => '1000000',
            'description' => 'Some procedure description',
        ]);

        $this->seeRouteIs('bank_accounts.show', $bankAccount);
        $this->seeText(__('bank_account_balance.created'));
        $this->seeInDatabase('bank_account_balances', [
            'bank_account_id' => $bankAccount->id,
            'date' => '2023-03-01',
            'amount' => '1000000',
            'description' => 'Some procedure description',
            'creator_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_edit_bank_account_balance()
    {
        $user = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['description' => 'Testing 123', 'creator_id' => $user->id]);
        $bankAccountBalance = factory(BankAccountBalance::class)->create(['bank_account_id' => $bankAccount->id, 'creator_id' => $user->id]);

        $firstItem = $bankAccount->balances->first();
        $this->visitRoute('bank_accounts.show', $bankAccount);
        $this->click('edit-bank_account_balance-'.$firstItem->id);
        $this->seeRouteIs('bank_accounts.show', [
            $bankAccount,
            'action' => 'edit_bank_account_balance',
            'bank_account_balance_id' => $firstItem->id,
        ]);

        $this->submitForm(__('bank_account_balance.update'), [
            'date' => '2023-03-01',
            'description' => 'Some procedure description',
            'amount' => '1000000',
        ]);

        $this->seeRouteIs('bank_accounts.show', $bankAccount);
        $this->seeText(__('bank_account_balance.updated'));
        $this->seeInDatabase('bank_account_balances', [
            'bank_account_id' => $bankAccount->id,
            'date' => '2023-03-01',
            'description' => 'Some procedure description',
            'amount' => '1000000',
        ]);
    }

    /** @test */
    public function user_can_delete_bank_account_balance()
    {
        $user = $this->loginAsUser();
        $bankAccount = factory(BankAccount::class)->create(['description' => 'Testing 123', 'creator_id' => $user->id]);
        $bankAccountBalance = factory(BankAccountBalance::class)->create(['bank_account_id' => $bankAccount->id, 'creator_id' => $user->id]);
        $firstItem = $bankAccount->balances->first();

        $this->visitRoute('bank_accounts.show', $bankAccount);
        $this->click('edit-bank_account_balance-'.$firstItem->id);
        $this->click('delete-bank_account_balance-'.$firstItem->id);
        $this->seeRouteIs('bank_accounts.show', [
            $bankAccount,
            'action' => 'delete_bank_account_balance',
            'bank_account_balance_id' => $firstItem->id,
        ]);

        $this->press('delete-bank_account_balance-'.$firstItem->id);

        $this->seeRouteIs('bank_accounts.show', $bankAccount);
        $this->seeText(__('bank_account_balance.deleted'));
        $this->dontSeeInDatabase('bank_account_balances', [
            'id' => $firstItem->id,
        ]);
    }
}
